<?php
/**
 * WordPress configuration for Docker deployments.
 *
 * Values are read from environment variables (see .env.example).
 * Defaults match the original local wp-config.php so local tooling stays familiar.
 *
 * @package WordPress
 */

if ( ! function_exists( 'getenv_docker' ) ) {
	/**
	 * Read env var, Docker *_FILE secret, or fallback default.
	 */
	function getenv_docker( $env, $default ) {
		if ( $file_env = getenv( $env . '_FILE' ) ) {
			return rtrim( file_get_contents( $file_env ), "\r\n" );
		}
		if ( ( $val = getenv( $env ) ) !== false ) {
			return $val;
		}
		return $default;
	}
}

define( 'DB_NAME', getenv_docker( 'WORDPRESS_DB_NAME', 'amber_web' ) );
define( 'DB_USER', getenv_docker( 'WORDPRESS_DB_USER', 'root' ) );
define( 'DB_PASSWORD', getenv_docker( 'WORDPRESS_DB_PASSWORD', '' ) );
define( 'DB_HOST', getenv_docker( 'WORDPRESS_DB_HOST', 'db:3306' ) );
define( 'DB_CHARSET', getenv_docker( 'WORDPRESS_DB_CHARSET', 'utf8mb4' ) );
define( 'DB_COLLATE', getenv_docker( 'WORDPRESS_DB_COLLATE', '' ) );

define( 'AUTH_KEY', getenv_docker( 'WORDPRESS_AUTH_KEY', 'put your unique phrase here' ) );
define( 'SECURE_AUTH_KEY', getenv_docker( 'WORDPRESS_SECURE_AUTH_KEY', 'put your unique phrase here' ) );
define( 'LOGGED_IN_KEY', getenv_docker( 'WORDPRESS_LOGGED_IN_KEY', 'put your unique phrase here' ) );
define( 'NONCE_KEY', getenv_docker( 'WORDPRESS_NONCE_KEY', 'put your unique phrase here' ) );
define( 'AUTH_SALT', getenv_docker( 'WORDPRESS_AUTH_SALT', 'put your unique phrase here' ) );
define( 'SECURE_AUTH_SALT', getenv_docker( 'WORDPRESS_SECURE_AUTH_SALT', 'put your unique phrase here' ) );
define( 'LOGGED_IN_SALT', getenv_docker( 'WORDPRESS_LOGGED_IN_SALT', 'put your unique phrase here' ) );
define( 'NONCE_SALT', getenv_docker( 'WORDPRESS_NONCE_SALT', 'put your unique phrase here' ) );

$table_prefix = getenv_docker( 'WORDPRESS_TABLE_PREFIX', 'wp_' );

define( 'WP_DEBUG', filter_var( getenv_docker( 'WORDPRESS_DEBUG', '' ), FILTER_VALIDATE_BOOLEAN ) );

/* Reverse proxy / TLS termination (common behind nginx, Traefik, Caddy) */
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && strpos( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) !== false ) {
	$_SERVER['HTTPS'] = 'on';
}

$site_url = getenv_docker( 'WORDPRESS_SITEURL', '' );
$home_url = getenv_docker( 'WORDPRESS_HOME', '' );
if ( $site_url ) {
	define( 'WP_SITEURL', $site_url );
}
if ( $home_url ) {
	define( 'WP_HOME', $home_url );
}

if ( $config_extra = getenv_docker( 'WORDPRESS_CONFIG_EXTRA', '' ) ) {
	eval( $config_extra );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
