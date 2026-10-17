<?php
/**
 * The Section Builder render engine.
 *
 * amber_render_sections() walks the flexible-content "sections" field and
 * renders the matching template part for each row. If ACF is inactive OR a
 * page has no sections yet, the front page falls back to a curated demo set so
 * the design is never empty. The key trick is amber_sub(): section parts read
 * every value through it, so the SAME part renders correctly whether it's
 * inside a real ACF row or in defaults-only demo mode.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get/set the "demo mode" flag.
 *
 * Demo mode is on while the front page renders its curated fallback sections
 * (no ACF data). In that state every amber_sub() call returns its default,
 * which is what makes the homepage look complete out of the box. Pass a bool
 * to set, call with no args to read.
 *
 * @param bool|null $set Optional new state.
 * @return bool
 */
function amber_is_demo( $set = null ) {
	static $demo = false;
	if ( null !== $set ) {
		$demo = (bool) $set;
	}
	return $demo;
}

/**
 * Read a flexible-content sub-field with a fallback default.
 *
 * Inside an ACF flexible-content row this returns the live value (including a
 * repeater's full array of rows). In demo mode — or when ACF is unavailable —
 * it returns the default, which is how sections render premium placeholder
 * content. Note: outside an ACF row, get_sub_field() returns false, so demo
 * mode (not the value test) is what guarantees defaults are used.
 *
 * @param string $name    Sub-field name.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function amber_sub( $name, $default = '' ) {
	$is_empty = static function ( $v ) {
		// An ACF image field with nothing selected also returns bare `false`
		// (not null/''/[]), so it must be treated as empty here too — no
		// true_false field is actually registered anywhere in this theme
		// (amber_f_truefalse() exists but is unused), so there is currently
		// no legitimate "off" state this would incorrectly short-circuit
		// past. Without this, an empty image field returned `false` straight
		// through to amber_image(), which doesn't recognise `false` as
		// either an attachment or a URL and falls back to the generic
		// placeholder.svg instead of this field's own bundled default image.
		return null === $v || false === $v || '' === $v || ( is_array( $v ) && empty( $v ) );
	};

	// 1) Inside an ACF PRO flexible-content row: use the live sub-field value.
	//    Only trust get_sub_field() when an ACF loop is actually active — outside
	//    a row (e.g. a normal page template using amber_sub for editable content)
	//    it returns a bare `false`, which is NOT caught by $is_empty (true_false
	//    fields keep false), so it would short-circuit past the real default.
	if ( ! amber_is_demo() && function_exists( 'get_sub_field' ) && function_exists( 'acf_get_loop' ) && acf_get_loop( 'active' ) ) {
		$value = get_sub_field( $name );
		if ( ! $is_empty( $value ) ) {
			return $value;
		}
	}

	// 2) ACF Free (or front-page demo render): read the matching field from the
	//    current page, so section content stays editable without Flexible Content.
	if ( function_exists( 'get_field' ) ) {
		$pid = get_the_ID();
		if ( $pid ) {
			$value = get_field( $name, $pid );
			if ( ! $is_empty( $value ) ) {
				return $value;
			}
		}
	}

	// 3) Premium default.
	return $default;
}

/**
 * Build the wrapper <section> attributes from the shared style sub-fields.
 *
 * @param string $prefix      Field prefix used by the layout (e.g. 'features').
 * @param string $default_bg  Default background token.
 * @param string $extra_class Extra classes for the section.
 * @return string Ready-to-print attribute string (class + id).
 */
function amber_section_attrs( $prefix, $default_bg = 'white', $extra_class = '' ) {
	$bg      = amber_sub( $prefix . '_bg', $default_bg );
	$spacing = amber_sub( $prefix . '_spacing', 'normal' );
	$anchor  = amber_sub( $prefix . '_anchor', '' );

	$classes = array(
		'section',
		'section--' . sanitize_html_class( $prefix ),
		'is-bg-' . sanitize_html_class( $bg ),
		'is-space-' . sanitize_html_class( $spacing ),
	);
	if ( $extra_class ) {
		$classes[] = $extra_class;
	}

	$attr = 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	if ( $anchor ) {
		$attr .= ' id="' . esc_attr( sanitize_title( $anchor ) ) . '"';
	}
	return $attr;
}

/**
 * Render all page sections (or the demo fallback on the front page).
 *
 * @return void
 */
function amber_render_sections() {
	$has_sections = function_exists( 'have_rows' ) && have_rows( 'sections' );

	if ( $has_sections ) {
		while ( have_rows( 'sections' ) ) {
			the_row();
			amber_section_part( get_row_layout() );
		}
		return;
	}

	// Fallback: render a curated homepage so the theme looks complete with no
	// data (and so it still shines on ACF Free). Only on the front page.
	if ( is_front_page() ) {
		amber_is_demo( true );
		foreach ( amber_default_sections() as $slug ) {
			amber_section_part( $slug, array( 'demo' => true ) );
		}
		amber_is_demo( false );
	}
}

/**
 * The default homepage composition used when no ACF data exists.
 *
 * @return array
 */
function amber_default_sections() {
	// Amber Technologies homepage composition (mirrors the reference design).
	return array(
		'hero',        // Navy ecosystem hero
		'reveal',      // Scroll-zoom video/mockup reveal
		'textreveal',  // Scroll word-fill statement + Learn More
		'products',    // Powerful Solutions — 4 products (moved right after textreveal)
		'concept',     // From Concept To Success — ambient grid + client marquee
		'erp',         // Amber ERP Software — violet showcase band (dashboards + stats)
		'industries',  // Solutions for Every Industry
		'whatsapp',    // WhatsApp Business integration — checklist + chat mockup
		'bento',       // Technology That Works for You
		'faq',         // Got Questions? We've Got Answers (accordion)
		'testimonials', // Reviews — centered auto-playing card slider
		'cta',         // Ready to Transform Your Business?
	);
}
