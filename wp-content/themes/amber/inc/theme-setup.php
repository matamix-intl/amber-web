<?php
/**
 * Theme setup: supports, menus, image sizes, i18n, editor styles.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register core theme supports.
 *
 * @return void
 */
function amber_setup() {
	// Make the theme translation-ready. /languages holds .po/.mo files.
	load_theme_textdomain( 'amber', AMBER_DIR . 'languages' );

	// Let WordPress manage the document <title>.
	add_theme_support( 'title-tag' );

	// Feed links in <head>.
	add_theme_support( 'automatic-feed-links' );

	// Featured images everywhere + custom crops used by sections.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'amber-card', 720, 480, true );        // Blog/service cards.
	add_image_size( 'amber-wide', 1600, 900, true );       // Hero / showcase.
	add_image_size( 'amber-square', 640, 640, true );      // Team / testimonial avatars.
	add_image_size( 'amber-portrait', 900, 1200, true );   // Case-study cards (tall).
	add_image_size( 'amber-logo', 240, 120, false );       // Trusted-by logos (no crop).

	// Custom logo (used as a fallback if the ACF options logo is empty).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// HTML5 markup for core-generated output.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	// Gutenberg / block editor: responsive embeds, wide & full alignments,
	// custom spacing, a curated colour palette and a fluid type scale so the
	// editor experience matches the front-end design tokens.
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Brand', 'amber' ),
				'slug'  => 'brand',
				'color' => '#0ea5e9',
			),
			array(
				'name'  => __( 'Sky', 'amber' ),
				'slug'  => 'sky',
				'color' => '#38bdf8',
			),
			array(
				'name'  => __( 'Navy', 'amber' ),
				'slug'  => 'navy',
				'color' => '#071226',
			),
			array(
				'name'  => __( 'Ink', 'amber' ),
				'slug'  => 'ink',
				'color' => '#0f172a',
			),
			array(
				'name'  => __( 'Muted', 'amber' ),
				'slug'  => 'muted',
				'color' => '#64748b',
			),
			array(
				'name'  => __( 'Surface', 'amber' ),
				'slug'  => 'surface',
				'color' => '#f8fafc',
			),
			array(
				'name'  => __( 'White', 'amber' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
		)
	);

	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Small', 'amber' ),
				'slug' => 'small',
				'size' => 15,
			),
			array(
				'name' => __( 'Normal', 'amber' ),
				'slug' => 'normal',
				'size' => 18,
			),
			array(
				'name' => __( 'Large', 'amber' ),
				'slug' => 'large',
				'size' => 24,
			),
			array(
				'name' => __( 'Heading', 'amber' ),
				'slug' => 'heading',
				'size' => 40,
			),
			array(
				'name' => __( 'Display', 'amber' ),
				'slug' => 'display',
				'size' => 64,
			),
		)
	);

	// Navigation menus.
	register_nav_menus(
		array(
			'primary'        => __( 'Primary Menu (Header)', 'amber' ),
			'footer_quick'   => __( 'Footer — Quick Links', 'amber' ),
			'footer_company' => __( 'Footer — Company', 'amber' ),
			'legal'          => __( 'Legal / Bottom Bar', 'amber' ),
		)
	);
}
add_action( 'after_setup_theme', 'amber_setup' );

/**
 * Set the global content width (used by oEmbeds and wide images).
 *
 * @return void
 */
function amber_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'amber_content_width', 1200 );
}
add_action( 'after_setup_theme', 'amber_content_width', 0 );

/**
 * Register widget areas (footer + an optional sidebar for the blog).
 *
 * @return void
 */
function amber_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'amber' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Appears beside single posts and the blog archive.', 'amber' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'amber_widgets_init' );

/**
 * Tighten the excerpt: length + a cleaner "read more" marker.
 *
 * @param int $length Word count.
 * @return int
 */
function amber_excerpt_length( $length ) {
	return is_admin() ? $length : 28;
}
add_filter( 'excerpt_length', 'amber_excerpt_length' );

/**
 * Replace the default [...] excerpt suffix.
 *
 * @param string $more Suffix.
 * @return string
 */
function amber_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'amber_excerpt_more' );
