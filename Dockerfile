# Amber Web — WordPress 7.0
#
# Built on the official WordPress image, which supplies Apache, PHP 8.3 and the
# extensions WordPress needs (mysqli, gd, zip, imagick, opcache).
#
# The base image's entrypoint generates wp-config.php at container start from
# WORDPRESS_* environment variables, so no credentials are baked into the image.
FROM wordpress:php8.3-apache

# Overlay this site's code on top of the base image's WordPress core.
# .dockerignore keeps wp-config.php, uploads and local cruft out.
COPY --chown=www-data:www-data . /var/www/html/

# Permalinks need mod_rewrite plus AllowOverride so .htaccess is honoured.
RUN a2enmod rewrite \
    && printf '<Directory /var/www/html/>\n\tAllowOverride All\n</Directory>\n' \
       > /etc/apache2/conf-available/wordpress-htaccess.conf \
    && a2enconf wordpress-htaccess

# Let WordPress write .htaccess, uploads and updates.
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD curl -fsS http://localhost/wp-login.php > /dev/null || exit 1
