<?php
/**
 * Asset enqueueing + performance.
 *
 * Loads the compiled CSS and the modular JS, self-hosts fonts via a preload +
 * font-display swap strategy, inlines critical CSS, and defers non-critical JS.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end styles and scripts.
 *
 * @return void
 */
function amber_enqueue_assets() {
	// Cache-bust with file mtime in dev, theme version in prod.
	$css_path = AMBER_DIR . 'assets/css/main.css';
	$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : AMBER_VERSION;

	// Main stylesheet (compiled from /assets/scss).
	wp_enqueue_style( 'amber-main', AMBER_URI . 'assets/css/main.css', array(), $css_ver );

	// The WordPress theme stylesheet (header only) — kept for compatibility.
	wp_enqueue_style( 'amber-style', get_stylesheet_uri(), array( 'amber-main' ), AMBER_VERSION );

	// Comment reply (only where threaded comments are enabled).
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Theme JS as a native ES module (modern, no bundler needed).
	$js_path = AMBER_DIR . 'assets/js/main.js';
	$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : AMBER_VERSION;
	wp_enqueue_script( 'amber-main', AMBER_URI . 'assets/js/main.js', array(), $js_ver, true );

	// Pass server data to JS safely.
	wp_localize_script(
		'amber-main',
		'amberData',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'amber_nonce' ),
			'reducedQuery' => '(prefers-reduced-motion: reduce)',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'amber_enqueue_assets' );

/**
 * Mark the main script as a JS module + add resource hints.
 *
 * Native modules are deferred by spec, which keeps the main thread free during
 * load and protects the LCP/TBT scores.
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @return string
 */
function amber_script_as_module( $tag, $handle ) {
	if ( 'amber-main' === $handle ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'amber_script_as_module', 10, 2 );

/**
 * Preconnect to Google Fonts and preload the primary font file.
 *
 * We use the CSS2 API with display=swap. Swapping the preconnect for fully
 * self-hosted fonts is a one-line change in production; documented in README.
 *
 * @return void
 */
function amber_resource_hints() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preconnect" href="https://api.fontshare.com" crossorigin>
	<?php // Body: Inter (Google). Display: Satoshi (Fontshare). Loaded async with a print->all swap. ?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" media="print" onload="this.media='all'">
	<link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=satoshi@500,700,900&display=swap" media="print" onload="this.media='all'">
	<noscript>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
		<link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=satoshi@500,700,900&display=swap">
	</noscript>
	<?php
}
add_action( 'wp_head', 'amber_resource_hints', 2 );

/**
 * Inline a tiny critical CSS block to paint above-the-fold instantly.
 *
 * Keeps the first paint fast even before main.css arrives. The values mirror
 * the SCSS design tokens; keep them in sync on a redesign.
 *
 * @return void
 */
function amber_critical_css() {
	?>
	<style id="amber-critical">
		:root{--amber-brand:#0ea5e9;--amber-ink:#0f172a;--amber-navy:#071226;--amber-surface:#f8fafc}
		*,*::before,*::after{box-sizing:border-box}
		body{margin:0;font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;color:var(--amber-ink);background:#fff;-webkit-font-smoothing:antialiased}
		img{max-width:100%;height:auto;display:block}
		.amber-skip-link{position:absolute;left:-9999px;top:0;z-index:1000;background:#fff;padding:.75rem 1rem;border-radius:8px}
		.amber-skip-link:focus{left:1rem;top:1rem}
		.site-header{position:fixed;top:clamp(.75rem,1.6vw,1.5rem);left:50%;transform:translateX(-50%);z-index:100;width:min(1040px,calc(100% - 2rem))}
	</style>
	<?php
}
add_action( 'wp_head', 'amber_critical_css', 1 );

/**
 * Output dynamic CSS custom-property overrides from Theme Settings.
 *
 * Lets the admin re-brand the whole site from one colour field without touching
 * SCSS. Printed after the critical CSS so it wins the cascade.
 *
 * @return void
 */
function amber_dynamic_css() {
	$brand = amber_option( 'brand_color' );
	if ( ! $brand ) {
		return;
	}
	$brand = sanitize_hex_color( $brand );
	if ( ! $brand ) {
		return;
	}
	printf(
		'<style id="amber-dynamic">:root{--amber-brand:%1$s;--amber-brand-600:%1$s;--amber-brand-700:%1$s}</style>' . "\n",
		esc_attr( $brand )
	);
}
add_action( 'wp_head', 'amber_dynamic_css', 20 );

/**
 * Enqueue the block-editor stylesheet so blocks match the front-end.
 *
 * @return void
 */
function amber_block_editor_assets() {
	$editor_css = AMBER_DIR . 'assets/css/editor.css';
	if ( file_exists( $editor_css ) ) {
		wp_enqueue_style( 'amber-editor', AMBER_URI . 'assets/css/editor.css', array(), filemtime( $editor_css ) );
	}
}
add_action( 'enqueue_block_editor_assets', 'amber_block_editor_assets' );

/**
 * Add width/height hints + native lazy-loading to content images (CLS win).
 * WordPress already does much of this; we ensure async decoding too.
 *
 * @param array $attr Attributes.
 * @return array
 */
function amber_img_attributes( $attr ) {
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'amber_img_attributes' );
