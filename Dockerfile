# Amber — WordPress 7.0 on PHP 8.2 / Apache (mod_php), Alpine base.
# The Debian-based wordpress:php8.2-apache image made this ~1.2 GB: the base
# alone is ~1.1 GB (build tools plus a second copy of WordPress core), and a
# recursive chmod/chown layer duplicated all of wp-content. Here PHP comes
# prebuilt from Alpine packages and the application code lands in a single,
# pre-pruned layer.

# --- Stage 1: assemble and prune the document root -------------------------
FROM alpine:3.22 AS code

# Application code: core, themes, plugins. Backups, caches and docs are
# excluded by .dockerignore.
COPY . /src/

# Container-specific config and permalinks at web root.
COPY docker/wp-config-docker.php /src/wp-config.php
COPY docker/htaccess-root /src/.htaccess

# Drop build helpers, and the unminified copies of core/plugin JS and CSS that
# have a .min sibling. WordPress only loads those with SCRIPT_DEBUG on.
RUN set -eux; \
	rm -rf /src/docker; \
	find /src/wp-includes /src/wp-admin /src/wp-content/plugins -type f \
		\( -name '*.js' -o -name '*.css' \) ! -name '*.min.*' \
	| while read -r f; do \
		[ -f "${f%.*}.min.${f##*.}" ] && rm "$f" || true; \
	done; \
	mkdir -p /src/wp-content/uploads /src/wp-content/upgrade \
		/src/wp-content/ai1wm-backups /src/wp-content/cache

# --- Stage 2: runtime ------------------------------------------------------
FROM alpine:3.22

# HTTP mirror: HTTPS is intercepted on some networks; apk verifies signatures.
RUN set -eux; \
	sed -i 's#https://#http://#' /etc/apk/repositories; \
	apk add --no-cache \
		apache2 curl unzip \
		php82 php82-apache2 php82-opcache php82-phar \
		php82-mysqli php82-pdo_mysql \
		php82-curl php82-gd php82-intl php82-mbstring php82-zip php82-exif \
		php82-dom php82-xml php82-xmlreader php82-xmlwriter php82-simplexml \
		php82-fileinfo php82-iconv php82-sodium php82-ctype php82-tokenizer \
		php82-session php82-bcmath php82-openssl \
	; \
	ln -sf /usr/bin/php82 /usr/bin/php; \
	# Run as uid/gid 33 (www-data), the same ids as the old Debian image, so
	# the uploads volume already on the server stays writable.
	deluser xfs 2>/dev/null || true; \
	delgroup www-data 2>/dev/null || true; \
	addgroup -S -g 33 www-data; \
	adduser -S -D -H -u 33 -G www-data -h /var/www www-data; \
	sed -ri \
		-e 's/^#(LoadModule (rewrite|expires|headers)_module)/\1/' \
		-e 's/^(User|Group) apache$/\1 www-data/' \
		-e 's#^ErrorLog .*#ErrorLog /dev/stderr#' \
		-e 's#^(\s*)CustomLog .*#\1CustomLog /dev/stdout combined#' \
		-e 's/^ServerTokens OS/ServerTokens Prod/' \
		-e 's/^ServerSignature On/ServerSignature Off/' \
		/etc/apache2/httpd.conf; \
	mkdir -p /var/www/html /run/apache2

# PHP limits (400 MB uploads for migration imports), opcache and Apache vhost
# settings. All live outside /var/www/html so a wp-content mount cannot
# shadow them.
COPY docker/uploads.ini /etc/php82/conf.d/90-uploads.ini
COPY docker/opcache.ini /etc/php82/conf.d/90-opcache.ini
COPY docker/apache-wordpress.conf /etc/apache2/conf.d/zz-wordpress.conf

# Runtime permission repair. Build-time ownership cannot reach a volume that
# the server's compose file mounts over wp-content/uploads, so fix it on boot.
COPY docker/fix-permissions.sh /usr/local/bin/fix-permissions.sh
RUN chmod +x /usr/local/bin/fix-permissions.sh

WORKDIR /var/www/html

# Ownership is set during COPY; a separate chown/chmod RUN would copy every
# file into a second layer.
COPY --from=code --chown=www-data:www-data /src/ /var/www/html/

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/fix-permissions.sh"]
CMD ["httpd", "-D", "FOREGROUND"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
	CMD curl -fsS http://localhost/ > /dev/null || exit 1
