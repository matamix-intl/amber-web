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

WORKDIR /var/www/html

# Application code (themes, plugins, core, uploads in repo)
COPY --chown=www-data:www-data . /var/www/html/

# Docker-specific config and permalinks at web root
COPY --chown=www-data:www-data docker/wp-config-docker.php /var/www/html/wp-config.php
COPY --chown=www-data:www-data docker/htaccess-root /var/www/html/.htaccess

# Writable uploads; plugins may write cache under wp-content
RUN set -eux; \
	find /var/www/html/wp-content -type d -exec chmod 775 {} \; ; \
	find /var/www/html/wp-content -type f -exec chmod 664 {} \; ; \
	chown -R www-data:www-data /var/www/html/wp-content

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
	CMD curl -f http://localhost/ || exit 1
