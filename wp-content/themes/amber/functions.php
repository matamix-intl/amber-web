<?php
/**
 * Amber functions and definitions.
 *
 * This is the theme bootstrap. It defines a few constants and then loads the
 * modular includes from /inc. Keeping functions.php thin and delegating to
 * focused modules is the core of the theme's "clean PHP architecture".
 *
 * @package Amber
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit; // Prevent direct access.

/**
 * Useful theme constants.
 *
 * AMBER_VERSION is used to cache-bust enqueued assets. Bump it on release.
 */
define( 'AMBER_VERSION', '1.2.0' );
define( 'AMBER_DIR', trailingslashit( get_template_directory() ) );        // Server path.
define( 'AMBER_URI', trailingslashit( get_template_directory_uri() ) );    // Public URL.
define( 'AMBER_INC', AMBER_DIR . 'inc/' );

/**
 * Require a module from /inc with a clear failure message.
 *
 * @param string $relative_path Path relative to /inc, without extension.
 * @return void
 */
function amber_require( $relative_path ) {
	$file = AMBER_INC . $relative_path . '.php';

	if ( is_readable( $file ) ) {
		require_once $file;
	} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		// Surface missing modules loudly during development, silently in production.
		trigger_error( esc_html( sprintf( 'Amber: missing module %s', $file ) ), E_USER_WARNING );
	}
}

/**
 * Load theme modules in dependency order.
 *
 * helpers first (used everywhere), then setup, assets, security, CPTs, and
 * finally the ACF layer (field groups + the page-builder render engine).
 */
$amber_modules = array(
	'helpers',            // Reusable getters, escaping wrappers, default content.
	'theme-setup',        // add_theme_support, menus, image sizes, content width.
	'enqueue',            // Styles, scripts, fonts, async/defer, preloads.
	'security',           // Hardening: header cleanup, SVG handling, escaping helpers.
	'custom-post-types',  // Service & Testimonial CPTs + taxonomies.
	'template-functions', // Body classes, schema, breadcrumbs, pagination.
	'acf/acf-init',       // ACF guard, JSON sync, options page, field registration.
	'acf/page-builder',   // Flexible-content render engine (amber_render_sections).
);

foreach ( $amber_modules as $amber_module ) {
	amber_require( $amber_module );
}
unset( $amber_module );
