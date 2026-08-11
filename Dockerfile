# WordPress 7.0 + PHP 8.2 (Apache). Matches this project's core version.
FROM wordpress:php8.2-apache

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libzip-dev \
		unzip \
	; \
	docker-php-ext-install zip; \
	apt-get clean; \
	rm -rf /var/lib/apt/lists/*

# Raise upload limits — migration imports need well above the 2M/8M defaults.
# post_max_size must be >= upload_max_filesize or uploads fail silently.
# mod_php (apache2handler) reads conf.d/*.ini at startup.
RUN printf '%s\n' \
	'upload_max_filesize = 400M' \
	'post_max_size = 400M' \
	'memory_limit = 512M' \
	'max_execution_time = 600' \
	'max_input_time = 600' \
	> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

# Application code (themes, plugins, core, uploads in repo)
COPY --chown=www-data:www-data . /var/www/html/

# Docker-specific config and permalinks at web root
COPY --chown=www-data:www-data docker/wp-config-docker.php /var/www/html/wp-config.php
COPY --chown=www-data:www-data docker/htaccess-root /var/www/html/.htaccess

# Writable uploads; plugins may write cache under wp-content.
# uploads/ is gitignored so it is absent from the build context — create it
# here, or WordPress cannot write media and AI1WM cannot stage its backups.
RUN set -eux; \
	mkdir -p /var/www/html/wp-content/uploads; \
	mkdir -p /var/www/html/wp-content/upgrade; \
	mkdir -p /var/www/html/wp-content/ai1wm-backups; \
	find /var/www/html/wp-content -type d -exec chmod 775 {} \; ; \
	find /var/www/html/wp-content -type f -exec chmod 664 {} \; ; \
	chown -R www-data:www-data /var/www/html/wp-content

# Runtime permission repair. Build-time chown cannot reach a volume that the
# server's compose file mounts over wp-content/uploads, so fix it on boot.
COPY docker/fix-permissions.sh /usr/local/bin/fix-permissions.sh
RUN chmod +x /usr/local/bin/fix-permissions.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/fix-permissions.sh"]
CMD ["apache2-foreground"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
	CMD curl -f http://localhost/ || exit 1
