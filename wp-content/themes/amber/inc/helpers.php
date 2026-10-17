<?php
/**
 * Helper utilities.
 *
 * Small, dependency-free functions used across templates. The most important
 * pattern here is amber_field(): a single, safe accessor for ACF data that
 * works whether or not ACF (Pro) is active, and always returns a sensible
 * fallback so the front-end never renders empty.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get an ACF field value with a graceful fallback.
 *
 * Wraps get_field() so templates don't have to care whether ACF is installed.
 * If ACF is missing (or the field is empty) the provided default is returned,
 * which keeps the design intact during evaluation or before content is added.
 *
 * @param string          $selector Field name/key.
 * @param mixed           $default  Value returned when empty/unavailable.
 * @param int|string|bool $post_id  Optional post ID, 'option', etc.
 * @return mixed
 */
function amber_field( $selector, $default = '', $post_id = false ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $selector, $post_id );

	// ACF returns false for empty true_false; preserve real booleans/zeros.
	if ( null === $value || '' === $value || ( is_array( $value ) && empty( $value ) ) ) {
		return $default;
	}

	return $value;
}

/**
 * Is ACF PRO active? (Options pages are Pro-only, so a reliable signal.)
 *
 * @return bool
 */
function amber_acf_is_pro() {
	return function_exists( 'acf_add_options_page' );
}

/**
 * Where global ("site settings") fields live.
 *
 * - ACF PRO: the dedicated Options page ('option').
 * - ACF Free: the static front page, where the "Site Settings" field group is
 *   attached (no Options page available in Free).
 *
 * @return int|string
 */
function amber_options_context() {
	if ( amber_acf_is_pro() ) {
		return 'option';
	}
	return (int) get_option( 'page_on_front' );
}

/**
 * Convenience accessor for a global option with fallback.
 *
 * Works with ACF PRO (Options page) and ACF Free (fields on the front page).
 *
 * @param string $selector Field name.
 * @param mixed  $default  Fallback value.
 * @return mixed
 */
function amber_option( $selector, $default = '' ) {
	// Social profiles are a repeater in Pro / fixed fields in Free; normalise.
	if ( 'social_links' === $selector ) {
		$links = amber_social_links();
		return ! empty( $links ) ? $links : $default;
	}
	return amber_field( $selector, $default, amber_options_context() );
}

/**
 * The social profiles to show, as network => URL.
 *
 * Theme Settings are the source of truth; the literals here are the official
 * Matamix International profiles, kept as a fallback so the icon rows still
 * render if an option is ever cleared. The footer and the contact panel both
 * read this, so the two rows can never drift apart.
 *
 * X/Twitter has no profile of its own, so its icon points at the official site
 * — resolved through home_url() rather than stored, so it follows the domain
 * instead of freezing whatever host the value was saved on. A network left at
 * '#' renders as a non-clickable circle; empty ones are dropped.
 *
 * @return array<string,string> network => URL
 */
function amber_social_profiles() {
	$profiles = array(
		'instagram' => 'https://www.instagram.com/matamix_international',
		'facebook'  => 'https://www.facebook.com/profile.php?id=61585201327065',
		'twitter'   => home_url( '/' ),
		'linkedin'  => 'https://www.linkedin.com/company/matamix-international',
		'youtube'   => '',
		'github'    => '',
	);

	foreach ( $profiles as $network => $default ) {
		$url = amber_option( 'social_' . $network );
		if ( $url ) {
			$profiles[ $network ] = $url;
		}
	}

	return array_filter( $profiles );
}

/**
 * Return the social profiles as a list of array( 'network', 'url' ).
 *
 * Reads the ACF PRO repeater when available, otherwise assembles the list from
 * the fixed per-network URL fields used in ACF Free.
 *
 * @return array<int,array<string,string>>
 */
function amber_social_links() {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	if ( amber_acf_is_pro() ) {
		$rows = get_field( 'social_links', 'option' );
		return is_array( $rows ) ? $rows : array();
	}

	$pid = (int) get_option( 'page_on_front' );
	if ( ! $pid ) {
		return array();
	}

	$out = array();
	foreach ( array( 'twitter', 'linkedin', 'facebook', 'instagram', 'youtube', 'github' ) as $network ) {
		$url = get_field( 'social_' . $network, $pid );
		if ( $url ) {
			$out[] = array( 'network' => $network, 'url' => $url );
		}
	}
	return $out;
}

/**
 * Echo an ACF image array (or attachment ID/URL) as a responsive <img>.
 *
 * Accepts the ACF "array" return format (recommended), an attachment ID, or a
 * raw URL string. Falls back to a bundled placeholder when nothing is set so
 * layouts keep their proportions in the demo state.
 *
 * @param mixed  $image  ACF image array | attachment ID | URL.
 * @param string $size   Registered image size.
 * @param array  $attrs  Extra HTML attributes (class, loading, sizes, alt...).
 * @return void
 */
function amber_image( $image, $size = 'large', $attrs = array() ) {
	$defaults = array(
		'class'    => '',
		'loading'  => 'lazy',
		'decoding' => 'async',
		'alt'      => '',
	);
	$attrs = wp_parse_args( $attrs, $defaults );

	// Attachment ID or ACF array -> let WordPress build srcset/sizes.
	$attachment_id = 0;
	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$attachment_id = (int) $image['ID'];
		if ( '' === $attrs['alt'] && ! empty( $image['alt'] ) ) {
			$attrs['alt'] = $image['alt'];
		}
	} elseif ( is_numeric( $image ) ) {
		$attachment_id = (int) $image;
	}

	if ( $attachment_id ) {
		$html_attrs = array(
			'class'    => $attrs['class'],
			'loading'  => $attrs['loading'],
			'decoding' => $attrs['decoding'],
			'alt'      => $attrs['alt'],
		);
		echo wp_get_attachment_image( $attachment_id, $size, false, $html_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core escapes.
		return;
	}

	// Raw URL or placeholder fallback.
	$src = '';
	if ( is_string( $image ) && '' !== $image ) {
		$src = $image;
	} elseif ( is_array( $image ) && ! empty( $image['url'] ) ) {
		$src = $image['url'];
	} else {
		$src = AMBER_URI . 'assets/images/placeholder.svg';
	}

	printf(
		'<img src="%1$s" class="%2$s" loading="%3$s" decoding="%4$s" alt="%5$s" />',
		esc_url( $src ),
		esc_attr( $attrs['class'] ),
		esc_attr( $attrs['loading'] ),
		esc_attr( $attrs['decoding'] ),
		esc_attr( $attrs['alt'] )
	);
}

/**
 * Render a CTA/link button from an ACF "link" field array.
 *
 * ACF link fields return array( 'title', 'url', 'target' ). This normalises
 * that shape, applies a style variant, and outputs an accessible <a>.
 *
 * @param array  $link    ACF link array.
 * @param string $variant Visual variant: primary|secondary|ghost|link.
 * @param array  $extra   Extra options: class, icon (bool), attrs (assoc).
 * @return void
 */
function amber_button( $link, $variant = 'primary', $extra = array() ) {
	if ( empty( $link ) || empty( $link['url'] ) ) {
		return;
	}

	$title  = ! empty( $link['title'] ) ? $link['title'] : __( 'Learn more', 'amber' );
	$target = ! empty( $link['target'] ) ? $link['target'] : '';
	$class  = 'btn btn--' . sanitize_html_class( $variant );

	if ( ! empty( $extra['class'] ) ) {
		$class .= ' ' . $extra['class'];
	}

	$rel = ( '_blank' === $target ) ? ' rel="noopener noreferrer"' : '';

	$icon = '';
	if ( ! empty( $extra['icon'] ) ) {
		$icon = ' <span class="btn__icon" aria-hidden="true">' . amber_icon_svg( 'arrow-right' ) . '</span>';
	}

	printf(
		'<a class="%1$s" href="%2$s"%3$s%4$s>%5$s%6$s</a>',
		esc_attr( $class ),
		esc_url( $link['url'] ),
		$target ? ' target="' . esc_attr( $target ) . '"' : '',
		$rel, // Static, safe.
		esc_html( $title ),
		$icon // From trusted icon map below.
	);
}

/**
 * Return an inline SVG icon from a small, curated set.
 *
 * Inline SVGs avoid an icon-font HTTP request and are styleable via currentColor.
 * Unknown keys return an empty string so callers stay simple.
 *
 * @param string $name Icon key.
 * @return string SVG markup (already safe, hand-authored).
 */
function amber_icon_svg( $name ) {
	$icons = array(
		'arrow-right' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'check'       => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" focusable="false"><polyline points="20 6 9 17 4 12"/></svg>',
		'chevron'     => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><polyline points="6 9 12 15 18 9"/></svg>',
		'star'        => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" focusable="false"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.8 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>',
		'quote'       => '<svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor" focusable="false"><path d="M9.5 7H6a3 3 0 0 0-3 3v3a3 3 0 0 0 3 3h1.5v-2H6a1 1 0 0 1-1-1v-.5h4.5V7zm9 0H15a3 3 0 0 0-3 3v3a3 3 0 0 0 3 3h1.5v-2H15a1 1 0 0 1-1-1v-.5h4.5V7z"/></svg>',
		'phone'       => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'mail'        => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>',
		'pin'         => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
		'clock'       => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>',
		'whatsapp'    => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1s-.7.8-.9 1c-.2.2-.3.2-.6.1a6.7 6.7 0 0 1-2-1.2 7.4 7.4 0 0 1-1.4-1.7c-.1-.2 0-.4.1-.5l.4-.4c.1-.1.2-.3.2-.4a.5.5 0 0 0 0-.5c-.1-.1-.6-1.4-.8-1.9-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2c0 1.3.9 2.6 1.1 2.8s1.7 2.6 4.2 3.6a13 13 0 0 0 1.4.5 3.4 3.4 0 0 0 1.6.1c.5-.1 1.5-.6 1.7-1.2s.2-1.1.1-1.2-.2-.2-.4-.3z"/></svg>',
		'menu'        => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" focusable="false"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>',
		'close'       => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" focusable="false"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>',
		'bolt'        => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
		'shield'      => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
		'rocket'      => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>',
		'layers'      => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
		'chart'       => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
		'globe'       => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
		'users'       => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'cloud'       => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>',
		'support'     => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M4.9 4.9 9 9M15 15l4.1 4.1M19.1 4.9 15 9M9 15l-4.1 4.1"/></svg>',
		'cart'        => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><circle cx="9" cy="21" r="1.5"/><circle cx="19" cy="21" r="1.5"/><path d="M2.5 3h2l2.2 12.4a2 2 0 0 0 2 1.6h8.4a2 2 0 0 0 2-1.6L21.5 7H6"/></svg>',
		'utensils'    => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M4 3v7a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V3M6 12v9M18 3c-1.7 0-3 2-3 5s1 4 3 4v9"/></svg>',
		'washer'      => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="4" y="2.5" width="16" height="19" rx="2"/><circle cx="12" cy="14" r="4.5"/><path d="M9.5 13a3 3 0 0 1 5 1"/><circle cx="7.5" cy="6" r=".6" fill="currentColor"/><circle cx="10" cy="6" r=".6" fill="currentColor"/></svg>',
		'factory'     => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M3 21V9l6 4V9l6 4V6h3v15z"/><path d="M3 21h18"/></svg>',
		'truck'       => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M1 4h13v12H1z"/><path d="M14 8h4l3 3v5h-7"/><circle cx="6" cy="18.5" r="1.6"/><circle cx="17.5" cy="18.5" r="1.6"/></svg>',
		'cap'         => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M22 9 12 4 2 9l10 5 10-5z"/><path d="M6 11.5V16c0 1.4 2.7 3 6 3s6-1.6 6-3v-4.5"/></svg>',
		'health'      => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M3 12h4l2-5 4 10 2-5h6"/></svg>',
		'briefcase'   => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="2.5" y="7" width="19" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M2.5 12.5h19"/></svg>',
		'cube'        => '<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>',
		'pos'         => '<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M3 16h18M8 20h8M12 16v4M7 7h4M7 10h2"/></svg>',
		'play'        => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" focusable="false"><path d="M8 5.14v14l11-7-11-7z"/></svg>',
		'spark'       => '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" focusable="false"><path d="M12 2l1.6 5.4L19 9l-5.4 1.6L12 16l-1.6-5.4L5 9l5.4-1.6L12 2z"/></svg>',

		// ---- POS "Benefits" badge icons -----------------------------------
		// Lucide icon set (ISC license, free for commercial use, no
		// attribution required — https://lucide.dev/license), sized larger
		// than the general-purpose set above to read as a standalone card
		// badge rather than an inline glyph.
		'mobile-billing'    => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect width="7" height="12" x="2" y="6" rx="1"/><path d="M13 8.32a7.43 7.43 0 0 1 0 7.36"/><path d="M16.46 6.21a11.76 11.76 0 0 1 0 11.58"/><path d="M19.91 4.1a15.91 15.91 0 0 1 .01 15.8"/></svg>',
		'omnichannel'       => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>',
		'purchase-mgmt'     => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>',
		'smart-reports'     => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 16h8"/><path d="M7 11h12"/><path d="M7 6h3"/></svg>',
		'reduce-waste'      => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
		'flexible-payment'  => '<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Echo a social-network icon (used by header/footer social lists).
 *
 * @param string $network Network key (twitter, linkedin, facebook, instagram, youtube, github).
 * @return string
 */
function amber_social_svg( $network ) {
	$icons = array(
		'twitter'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7 8 8.3 12h-6.5l-5-7.3L6 22H2.9l7.5-8.6L2 2h6.6l4.6 6.7zm-1.1 18h1.8L7.3 4H5.4z"/></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.4 8.65 21 11 21 14.1V21h-4v-6.1c0-1.45-.03-3.3-2-3.3s-2.3 1.57-2.3 3.2V21H9z"/></svg>',
		'facebook'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.76-1.6 1.5V12h2.7l-.43 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2.5" y="2.5" width="19" height="19" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.3 5 12 5 12 5s-7.3 0-8.8.5A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8C4.7 19 12 19 12 19s7.3 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l5.7 3.3z"/></svg>',
		'github'    => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 2A10 10 0 0 0 8.8 21.5c.5.1.7-.2.7-.5v-1.7c-2.8.6-3.4-1.3-3.4-1.3-.5-1.2-1.1-1.5-1.1-1.5-.9-.6.1-.6.1-.6 1 .1 1.5 1 1.5 1 .9 1.6 2.4 1.1 3 .9.1-.7.3-1.1.6-1.4-2.2-.25-4.6-1.1-4.6-5a3.9 3.9 0 0 1 1-2.7 3.6 3.6 0 0 1 .1-2.7s.8-.3 2.7 1a9.3 9.3 0 0 1 5 0c1.9-1.3 2.7-1 2.7-1 .5 1.4.2 2.4.1 2.7a3.9 3.9 0 0 1 1 2.7c0 3.9-2.3 4.7-4.6 5 .4.3.7.9.7 1.8v2.6c0 .3.2.6.7.5A10 10 0 0 0 12 2z"/></svg>',
	);

	return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}

/**
 * Locate a section template part and pass it args.
 *
 * Thin wrapper around get_template_part() that namespaces section parts under
 * /template-parts/sections so the page-builder render engine stays tidy.
 *
 * @param string $slug Section slug (file = section-{slug}.php).
 * @param array  $args Variables exposed to the part as $args.
 * @return void
 */
function amber_section_part( $slug, $args = array() ) {
	get_template_part( 'template-parts/sections/section', $slug, $args );
}

/**
 * Resolve the permalink of the page using a given theme page template.
 *
 * Lets hard-coded navigation (header default nav, product cards) point at the
 * real pages regardless of their slugs. Cached per request.
 *
 * @param string $template Template file name without prefix/extension
 *                         (e.g. 'about' => page-templates/page-about.php).
 * @param string $fallback URL returned when no page uses the template.
 * @return string
 */
function amber_template_page_url( $template, $fallback = '#' ) {
	static $cache = array();
	if ( ! isset( $cache[ $template ] ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'numberposts'    => 1,
				'fields'         => 'ids',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => 'page-templates/page-' . $template . '.php', // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		$cache[ $template ] = $ids ? get_permalink( $ids[0] ) : '';
	}
	return $cache[ $template ] ? $cache[ $template ] : $fallback;
}

/**
 * The canonical Amber Technologies product list.
 *
 * Single source of truth reused by the header mega-menu and (optionally) the
 * Products section. Filterable so a child theme / plugin can adjust it.
 *
 * @return array<int,array<string,mixed>>
 */
function amber_products() {
	$products = array(
		array(
			'name'     => 'Amber ERP Software',
			'tagline'  => __( 'Enterprise Resource Planning Software', 'amber' ),
			'icon'     => 'cube',
			'accent'   => '#38bdf8',
			'url'      => amber_template_page_url( 'amber-erp' ),
			'features' => array( __( 'Finance Management', 'amber' ), __( 'Inventory Management', 'amber' ), __( 'HR & Payroll', 'amber' ) ),
		),
		array(
			'name'     => 'Amber POS Software',
			'tagline'  => __( 'Smart Point-of-Sale Solution', 'amber' ),
			'icon'     => 'pos',
			'accent'   => '#f59e0b',
			'url'      => amber_template_page_url( 'pos' ),
			'features' => array( __( 'Billing', 'amber' ), __( 'Inventory Control', 'amber' ), __( 'Multi-store Management', 'amber' ) ),
		),
		array(
			'name'     => 'Amber Restaurant Software',
			'tagline'  => __( 'Smart Restaurant Management Software', 'amber' ),
			'icon'     => 'utensils',
			'accent'   => '#10b981',
			'url'      => amber_template_page_url( 'zoomie' ),
			'features' => array( __( 'QR Ordering', 'amber' ), __( 'Table Management', 'amber' ), __( 'Kitchen Display System', 'amber' ) ),
		),
		array(
			'name'     => 'Amber Laundry Software',
			'tagline'  => __( 'Smart Laundry Management Software', 'amber' ),
			'icon'     => 'washer',
			'accent'   => '#8b5cf6',
			'url'      => amber_template_page_url( 'gozzbe' ),
			'features' => array( __( 'Pickup & Delivery', 'amber' ), __( 'Order Tracking', 'amber' ), __( 'Customer Notifications', 'amber' ) ),
		),
	);

	/**
	 * Filter the canonical product list.
	 *
	 * @param array $products Product definitions.
	 */
	return apply_filters( 'amber_products', $products );
}

/**
 * Output the Amber Technologies brand lockup (triangle mark + wordmark).
 *
 * Used as the header/footer logo fallback when no image logo is set, so the
 * brand always renders with strong contrast on the navy header.
 *
 * @param bool $with_sub Whether to show the "TECHNOLOGIES" sub-label.
 * @return void
 */
function amber_brand_lockup( $with_sub = true ) {
	?>
	<span class="brand-lockup">
		<span class="brand-lockup__mark" aria-hidden="true">
			<svg viewBox="0 0 32 28" width="30" height="26" fill="none" focusable="false"><path d="M16 2 30 26H2L16 2Z" fill="#38bdf8"/><path d="M16 11 21.5 21H10.5L16 11Z" fill="#071226"/></svg>
		</span>
		<span class="brand-lockup__text">
			<span class="brand-lockup__name">amber</span>
			<?php if ( $with_sub ) : ?>
				<span class="brand-lockup__sub"><?php esc_html_e( 'TECHNOLOGIES', 'amber' ); ?></span>
			<?php endif; ?>
		</span>
	</span>
	<?php
}

/**
 * Build an autoplaying, muted, looping background-video embed from a URL.
 *
 * Accepts a self-hosted file (.mp4/.webm/.ogg), a Vimeo URL, a YouTube URL or
 * any other embeddable URL. Returns ready-to-print, escaped HTML (or '').
 *
 * @param string $url   Video URL.
 * @param string $class Class for the media element.
 * @param string $title Accessible iframe title.
 * @return string
 */
function amber_video_embed( $url, $class = 'reveal__video', $title = '' ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	$title = $title ? $title : __( 'Product video', 'amber' );

	// Self-hosted file.
	if ( preg_match( '/\.(mp4|webm|ogg)(\?.*)?$/i', $url ) ) {
		return '<video class="' . esc_attr( $class ) . '" autoplay muted loop playsinline preload="metadata"><source src="' . esc_url( $url ) . '"></video>';
	}

	// Vimeo.
	if ( preg_match( '#vimeo\.com/(?:video/)?(\d+)#i', $url, $m ) ) {
		$src = 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&muted=1&loop=1&controls=0&background=1';
		return '<iframe class="' . esc_attr( $class ) . '" src="' . esc_url( $src ) . '" title="' . esc_attr( $title ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
	}

	// YouTube.
	if ( preg_match( '#(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([A-Za-z0-9_-]{11})#i', $url, $m ) ) {
		$id  = $m[1];
		$src = 'https://www.youtube.com/embed/' . $id . '?autoplay=1&mute=1&loop=1&controls=0&playlist=' . $id . '&modestbranding=1&rel=0&playsinline=1';
		return '<iframe class="' . esc_attr( $class ) . '" src="' . esc_url( $src ) . '" title="' . esc_attr( $title ) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
	}

	// Generic embeddable URL.
	return '<iframe class="' . esc_attr( $class ) . '" src="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>';
}

/**
 * Generate a CSS-safe unique id (for ARIA wiring of accordions, menus, etc.).
 *
 * @param string $prefix Prefix.
 * @return string
 */
function amber_uid( $prefix = 'amber' ) {
	static $counter = 0;
	$counter++;
	return sanitize_html_class( $prefix . '-' . $counter );
}

/**
 * Default content for the Amber ERP Software "Key Features" tabbed section.
 *
 * Single source of truth shared by the front-end template
 * (template-parts/erp/amber-erp-page.php) and the ACF field registration
 * (inc/acf/fields-free.php), so admin defaults and demo output never drift.
 * Keys map to field names as erp_ft{n}_{key}; cards as erp_ft{n}_{card}_{part}.
 *
 * @return array[] Five tabs; each: label, icon, title, desc, cta_label,
 *                 cta_url and a 'cards' map (l1,l2,r1,r2,b1..b4 => icon,title,text).
 */
function amber_erp_feature_defaults() {
	return array(
		1 => array(
			'label'     => __( 'Smart Dashboard', 'amber' ),
			'icon'      => 'chart',
			'title'     => __( 'Your Entire Business on One Screen', 'amber' ),
			'desc'      => __( 'The Amber ERP Software dashboard brings finance, sales, inventory and operations into a single live view — so every decision starts from the same real-time truth.', 'amber' ),
			'cta_label' => __( 'Explore the Dashboard', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'bolt', __( 'Smart Automation', 'amber' ), __( 'AI-powered workflows handle routine entries for you.', 'amber' ) ),
				'l2' => array( 'cloud', __( 'Real-time Sync', 'amber' ), __( 'Live updates across every device and branch.', 'amber' ) ),
				'r1' => array( 'support', __( 'Team Collaboration', 'amber' ), __( 'Shared workspaces keep every department aligned.', 'amber' ) ),
				'r2' => array( 'chart', __( 'Advanced Reports', 'amber' ), __( 'Drill-down analytics on every metric.', 'amber' ) ),
				'b1' => array( 'globe', __( 'Mobile App', 'amber' ), __( 'Run your business from anywhere.', 'amber' ) ),
				'b2' => array( 'shield', __( 'Security', 'amber' ), __( 'Bank-grade encryption and roles.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Open API', 'amber' ), __( 'Connect anything with REST APIs.', 'amber' ) ),
				'b4' => array( 'cube', __( 'Integrations', 'amber' ), __( 'Plug into the tools you already use.', 'amber' ) ),
			),
		),
		2 => array(
			'label'     => __( 'Finance & Accounting', 'amber' ),
			'icon'      => 'briefcase',
			'title'     => __( 'Close Your Books in Days, Not Weeks', 'amber' ),
			'desc'      => __( 'Automated ledgers, reconciliations and tax-ready statements keep finance accurate to the minute — with a clear audit trail behind every number.', 'amber' ),
			'cta_label' => __( 'See Finance Tools', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'check', __( 'Auto Reconciliation', 'amber' ), __( 'Match transactions to statements instantly.', 'amber' ) ),
				'l2' => array( 'clock', __( 'Recurring Billing', 'amber' ), __( 'Invoices and reminders on autopilot.', 'amber' ) ),
				'r1' => array( 'chart', __( 'Cash-flow Forecast', 'amber' ), __( 'Predict liquidity months ahead.', 'amber' ) ),
				'r2' => array( 'shield', __( 'Audit Trail', 'amber' ), __( 'Every change logged and traceable.', 'amber' ) ),
				'b1' => array( 'briefcase', __( 'Multi-entity', 'amber' ), __( 'Consolidate companies in one view.', 'amber' ) ),
				'b2' => array( 'globe', __( 'Multi-currency', 'amber' ), __( 'Trade in any currency, live rates.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Tax Engine', 'amber' ), __( 'GST and VAT rules built in.', 'amber' ) ),
				'b4' => array( 'cube', __( 'Bank Feeds', 'amber' ), __( 'Direct feeds from your banks.', 'amber' ) ),
			),
		),
		3 => array(
			'label'     => __( 'Inventory Control', 'amber' ),
			'icon'      => 'cube',
			'title'     => __( 'Every Item Tracked, Everywhere', 'amber' ),
			'desc'      => __( 'Real-time stock across warehouses, batches and serials — with reorder intelligence that keeps shelves full and working capital free.', 'amber' ),
			'cta_label' => __( 'See Inventory Tools', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'cube', __( 'Batch & Serial', 'amber' ), __( 'Trace every unit end to end.', 'amber' ) ),
				'l2' => array( 'bolt', __( 'Smart Reorder', 'amber' ), __( 'Auto purchase at the right moment.', 'amber' ) ),
				'r1' => array( 'truck', __( 'Multi-warehouse', 'amber' ), __( 'Transfer and track between sites.', 'amber' ) ),
				'r2' => array( 'chart', __( 'Stock Analytics', 'amber' ), __( 'Aging, turnover and dead stock.', 'amber' ) ),
				'b1' => array( 'cart', __( 'Barcode Ready', 'amber' ), __( 'Scan in, scan out, zero errors.', 'amber' ) ),
				'b2' => array( 'clock', __( 'Cycle Counts', 'amber' ), __( 'Count without stopping work.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Kitting & BOM', 'amber' ), __( 'Bundles and assemblies handled.', 'amber' ) ),
				'b4' => array( 'shield', __( 'Loss Control', 'amber' ), __( 'Variance alerts in real time.', 'amber' ) ),
			),
		),
		4 => array(
			'label'     => __( 'Sales & CRM', 'amber' ),
			'icon'      => 'cart',
			'title'     => __( 'From Lead to Cash, Without the Gaps', 'amber' ),
			'desc'      => __( 'Quotes, orders, fulfilment and follow-ups in one pipeline — so sales sees inventory, finance sees sales, and customers feel the difference.', 'amber' ),
			'cta_label' => __( 'See Sales Tools', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'support', __( '360° Customers', 'amber' ), __( 'Every interaction in one profile.', 'amber' ) ),
				'l2' => array( 'bolt', __( 'Quote to Order', 'amber' ), __( 'Convert quotes in one click.', 'amber' ) ),
				'r1' => array( 'chart', __( 'Pipeline View', 'amber' ), __( 'Deals and stages at a glance.', 'amber' ) ),
				'r2' => array( 'clock', __( 'Follow-up Nudges', 'amber' ), __( 'Never miss a next step.', 'amber' ) ),
				'b1' => array( 'pos', __( 'POS Sync', 'amber' ), __( 'Store sales flow straight in.', 'amber' ) ),
				'b2' => array( 'globe', __( 'Online Orders', 'amber' ), __( 'E-commerce lands in the ERP.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Price Lists', 'amber' ), __( 'Customer-specific pricing.', 'amber' ) ),
				'b4' => array( 'shield', __( 'Credit Limits', 'amber' ), __( 'Sell safely within limits.', 'amber' ) ),
			),
		),
		5 => array(
			'label'     => __( 'Analytics & Reports', 'amber' ),
			'icon'      => 'spark',
			'title'     => __( 'Answers Before You Ask', 'amber' ),
			'desc'      => __( 'Live KPIs, scheduled reports and drill-downs across every module — decision-grade intelligence without exporting a single spreadsheet.', 'amber' ),
			'cta_label' => __( 'See Analytics', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'chart', __( 'Live KPIs', 'amber' ), __( 'Boards that update themselves.', 'amber' ) ),
				'l2' => array( 'clock', __( 'Scheduled Reports', 'amber' ), __( 'Delivered to inboxes on time.', 'amber' ) ),
				'r1' => array( 'layers', __( 'Drill-downs', 'amber' ), __( 'From totals to transactions.', 'amber' ) ),
				'r2' => array( 'cloud', __( 'Export Anywhere', 'amber' ), __( 'PDF, Excel or straight to BI.', 'amber' ) ),
				'b1' => array( 'bolt', __( 'Anomaly Alerts', 'amber' ), __( 'Spikes flagged automatically.', 'amber' ) ),
				'b2' => array( 'globe', __( 'Branch Compare', 'amber' ), __( 'Locations side by side.', 'amber' ) ),
				'b3' => array( 'shield', __( 'Role-based Views', 'amber' ), __( 'Everyone sees what they should.', 'amber' ) ),
				'b4' => array( 'spark', __( 'AI Summaries', 'amber' ), __( 'Plain-language insights.', 'amber' ) ),
			),
		),
		6 => array(
			'label'     => __( 'Purchase & Vendor Management', 'amber' ),
			'icon'      => 'truck',
			'title'     => __( 'Buy Smarter, Pay on Time', 'amber' ),
			'desc'      => __( 'Purchase orders, vendor comparisons and approvals in one flow — so procurement stays fast without losing control of spend.', 'amber' ),
			'cta_label' => __( 'See Purchase Tools', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'truck', __( 'Vendor Comparison', 'amber' ), __( 'Compare quotes before you commit.', 'amber' ) ),
				'l2' => array( 'bolt', __( 'Auto PO Creation', 'amber' ), __( 'Purchase orders from reorder points.', 'amber' ) ),
				'r1' => array( 'chart', __( 'Spend Analytics', 'amber' ), __( 'See where every rupee goes.', 'amber' ) ),
				'r2' => array( 'shield', __( 'Approval Workflows', 'amber' ), __( 'Route big spends for sign-off.', 'amber' ) ),
				'b1' => array( 'cube', __( 'Goods Receipt', 'amber' ), __( 'Match invoices to what arrived.', 'amber' ) ),
				'b2' => array( 'clock', __( 'Payment Schedules', 'amber' ), __( 'Never miss a vendor due date.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Contract Pricing', 'amber' ), __( 'Locked-in rates per vendor.', 'amber' ) ),
				'b4' => array( 'globe', __( 'Multi-vendor Sourcing', 'amber' ), __( 'Split orders across suppliers.', 'amber' ) ),
			),
		),
		7 => array(
			'label'     => __( 'Team & Payroll', 'amber' ),
			'icon'      => 'users',
			'title'     => __( 'People Ops, Fully Connected', 'amber' ),
			'desc'      => __( 'Attendance, leave and payroll tied straight to the same system running your business — accurate pay, zero spreadsheets.', 'amber' ),
			'cta_label' => __( 'See Team Tools', 'amber' ),
			'cta_url'   => '#demo',
			'cards'     => array(
				'l1' => array( 'users', __( 'Attendance Tracking', 'amber' ), __( 'Biometric, app or web check-in.', 'amber' ) ),
				'l2' => array( 'clock', __( 'Leave Management', 'amber' ), __( 'Requests and approvals in one place.', 'amber' ) ),
				'r1' => array( 'chart', __( 'Payroll Runs', 'amber' ), __( 'Salaries computed automatically.', 'amber' ) ),
				'r2' => array( 'shield', __( 'Compliance Ready', 'amber' ), __( 'Statutory deductions built in.', 'amber' ) ),
				'b1' => array( 'support', __( 'Self-service Portal', 'amber' ), __( 'Payslips and requests, no back-and-forth.', 'amber' ) ),
				'b2' => array( 'globe', __( 'Multi-branch HR', 'amber' ), __( 'One roster across every location.', 'amber' ) ),
				'b3' => array( 'layers', __( 'Role-based Access', 'amber' ), __( 'Managers see only their teams.', 'amber' ) ),
				'b4' => array( 'bolt', __( 'Instant Payslips', 'amber' ), __( 'Generated the moment payroll runs.', 'amber' ) ),
			),
		),
	);
}

/**
 * Default content for the Amber ERP Software "Services" numbered grid section.
 *
 * Shared by the ERP template and the ACF registration (field names
 * erp_svc{n}_title / erp_svc{n}_text), mirroring the numbered two-column
 * services layout from the reference design.
 *
 * @return array[] Ten services; each: title, text.
 */
function amber_erp_service_defaults() {
	return array(
		1  => array(
			'title' => __( 'Implementation & Onboarding', 'amber' ),
			'text'  => __( 'Structured rollouts that take you live in weeks, not months. Our team maps your processes, configures every module and walks your people through go-live with zero disruption to daily business.', 'amber' ),
		),
		2  => array(
			'title' => __( 'Data Migration', 'amber' ),
			'text'  => __( 'Move ledgers, stock, suppliers and customer history from spreadsheets or legacy systems safely. Every record is validated, deduplicated and reconciled before switch-over.', 'amber' ),
		),
		3  => array(
			'title' => __( 'Custom Module Development', 'amber' ),
			'text'  => __( 'When your workflow is unique, we build for it. Purpose-built modules and screens shaped around your operations — fully integrated with the Amber ERP Software core.', 'amber' ),
		),
		4  => array(
			'title' => __( 'Workflow Automation', 'amber' ),
			'text'  => __( 'Approvals, alerts, recurring documents and scheduled jobs run themselves. Free your team from repetitive admin and keep every process moving without follow-ups.', 'amber' ),
		),
		5  => array(
			'title' => __( 'Third-party Integrations', 'amber' ),
			'text'  => __( 'Connect banks, e-commerce platforms, logistics partners and payment gateways through ready connectors and a clean REST API — data flows in real time, both ways.', 'amber' ),
		),
		6  => array(
			'title' => __( 'Training & Adoption', 'amber' ),
			'text'  => __( 'Role-based training for finance, sales, warehouse and management, plus in-app guides. We make sure the whole team actually uses what you paid for.', 'amber' ),
		),
		7  => array(
			'title' => __( 'Cloud & On-premise Hosting', 'amber' ),
			'text'  => __( 'Run Amber ERP Software in our managed cloud with automatic backups and updates, or deploy on your own infrastructure. Either way, performance and uptime are our problem, not yours.', 'amber' ),
		),
		8  => array(
			'title' => __( 'Security & Compliance', 'amber' ),
			'text'  => __( 'Role-based access, encryption at rest and in transit, full audit trails and tax-authority-ready reporting keep your data protected and your business compliant.', 'amber' ),
		),
		9  => array(
			'title' => __( 'Support & Maintenance', 'amber' ),
			'text'  => __( 'SLA-backed support from real product specialists, proactive monitoring and continuous updates keep your ERP fast, current and always available.', 'amber' ),
		),
		10 => array(
			'title' => __( 'ERP Consultation', 'amber' ),
			'text'  => __( 'Not sure where to start? Our consultants audit your current processes and design a phased ERP roadmap that fits your budget, your team and your growth plans.', 'amber' ),
		),
	);
}

/**
 * Default content for the Amber ERP Software FAQ section.
 *
 * Shared by the ERP template and the ACF registration (field names
 * erp_faq{n}_q / erp_faq{n}_a).
 *
 * @return array[] Eight FAQs; each: q, a.
 */
function amber_erp_faq_defaults() {
	return array(
		1 => array(
			'q' => __( 'What is Amber ERP Software and who is it for?', 'amber' ),
			'a' => __( 'Amber ERP Software is a connected business platform that unifies finance, inventory, sales and operations in one system. It fits growing businesses of every size — from a single store to a multi-branch enterprise — and scales as you do.', 'amber' ),
		),
		2 => array(
			'q' => __( 'How long does implementation take?', 'amber' ),
			'a' => __( 'Most businesses go live in two to six weeks depending on modules and data volume. Our structured onboarding covers process mapping, configuration, data migration and training — with a dedicated implementation manager throughout.', 'amber' ),
		),
		3 => array(
			'q' => __( 'Can Amber ERP Software be customised to our workflow?', 'amber' ),
			'a' => __( 'Yes. Screens, fields, approval chains and reports are configurable out of the box, and our team builds fully custom modules for workflows that are unique to your business — all natively integrated with the ERP core.', 'amber' ),
		),
		4 => array(
			'q' => __( 'Does it integrate with the tools we already use?', 'amber' ),
			'a' => __( 'Amber ERP Software ships with ready connectors for banks, e-commerce platforms, POS systems, payment gateways and logistics partners, plus a clean REST API for anything else. Data flows both ways, in real time.', 'amber' ),
		),
		5 => array(
			'q' => __( 'Can we migrate data from spreadsheets or a legacy system?', 'amber' ),
			'a' => __( 'Absolutely. Our migration service moves ledgers, stock, suppliers and customer history safely — every record is validated, deduplicated and reconciled before switch-over, so you start clean without losing history.', 'amber' ),
		),
		6 => array(
			'q' => __( 'Is our business data secure?', 'amber' ),
			'a' => __( 'Security is foundational: role-based access control, encryption in transit and at rest, full audit trails and automatic backups. Host in our managed cloud or on your own infrastructure — your data, your choice.', 'amber' ),
		),
		7 => array(
			'q' => __( 'What training and support do you provide?', 'amber' ),
			'a' => __( 'Every rollout includes role-based training for finance, sales, warehouse and management, plus in-app guides. After go-live you get SLA-backed support from real product specialists — not a ticket black hole.', 'amber' ),
		),
		8 => array(
			'q' => __( 'How is Amber ERP Software priced?', 'amber' ),
			'a' => __( 'Pricing is per module and per user, so you only pay for what you use. Talk to our team for a tailored quote — most customers find Amber ERP Software pays for itself within the first year through time saved and tighter stock control.', 'amber' ),
		),
	);
}

/**
 * Default content for the POS page's "Benefits" section (icon-card grid).
 *
 * Shared by the ERP template and the ACF registration (field names
 * erp_pben{n}_icon / erp_pben{n}_title / erp_pben{n}_text). Icons map to
 * amber_icon_svg(). POS-only section — see the 'pos' branch in
 * template-parts/erp/amber-erp-page.php.
 *
 * @return array[] Six benefits; each: icon, title, text.
 */
function amber_erp_pos_benefit_defaults() {
	return array(
		1 => array(
			'icon'  => 'mobile-billing',
			'title' => __( 'Mobile Billing', 'amber' ),
			'text'  => __( 'Speed up checkout with mobile billing from any location and ensure smooth operations during peak hours and festive seasons.', 'amber' ),
		),
		2 => array(
			'icon'  => 'omnichannel',
			'title' => __( 'Go Omnichannel', 'amber' ),
			'text'  => __( 'Integrate your POS with online platforms so customers can shop online and pick up or return products in-store, with real-time inventory across every channel.', 'amber' ),
		),
		3 => array(
			'icon'  => 'purchase-mgmt',
			'title' => __( 'Purchase Management', 'amber' ),
			'text'  => __( 'Make agile purchasing decisions and ensure accurate procurement by creating instant purchase orders and handling returns from anywhere.', 'amber' ),
		),
		4 => array(
			'icon'  => 'smart-reports',
			'title' => __( 'Smart Reports', 'amber' ),
			'text'  => __( 'Analyze business health with real-time graphical reports. Customize dashboards with filters, save favorites, and export reports with ease.', 'amber' ),
		),
		5 => array(
			'icon'  => 'reduce-waste',
			'title' => __( 'Reduce Product Waste', 'amber' ),
			'text'  => __( 'Track wasted and damaged items to process returns or issue debit notes to suppliers, and monitor batch expiration to streamline purchase returns.', 'amber' ),
		),
		6 => array(
			'icon'  => 'flexible-payment',
			'title' => __( 'Flexible Payment Methods', 'amber' ),
			'text'  => __( 'Ensure hassle-free payments through a wide range of integrations with payment devices, payment gateways, and connected banking.', 'amber' ),
		),
	);
}

/**
 * Default content for the POS page's "Key Features" interactive list
 * (reference: GoFrugal's "Key features of a POS system" section).
 *
 * Shared by the ERP template and the ACF registration (field names
 * erp_pkey{n}_title / erp_pkey{n}_text / erp_pkey{n}_image). Icons map to
 * amber_icon_svg(). Rendered via the same list+visual mechanism as the
 * Amber Laundry Software feature sections (.gzf-* markup, modules/gozzbe-features.js).
 * POS-only section.
 *
 * @return array[] Seven features; each: icon, title, text.
 */
function amber_erp_pos_key_feature_defaults() {
	return array(
		1 => array(
			'icon'  => 'bolt',
			'title' => __( 'Fast Billing', 'amber' ),
			'text'  => __( 'Experience smooth checkouts on tablet, mobile, and desktop-based POS software that ensures seamless transactions, even offline.', 'amber' ),
		),
		2 => array(
			'icon'  => 'layers',
			'title' => __( 'Inventory Management', 'amber' ),
			'text'  => __( 'Maximize sales and minimize shortages with POS software that offers complete inventory control, cuts down on waste, makes smart recommendations, and sends timely alerts.', 'amber' ),
		),
		3 => array(
			'icon'  => 'chart',
			'title' => __( 'Business Intelligence', 'amber' ),
			'text'  => __( 'Access real-time graphical business reports, customize dashboards with filters, and schedule email reports for stakeholders using a POS system.', 'amber' ),
		),
		4 => array(
			'icon'  => 'users',
			'title' => __( 'CRM and Loyalty', 'amber' ),
			'text'  => __( 'Delight your customers with personalized marketing campaigns through SMS, email, and WhatsApp to highlight special offers and boost engagement.', 'amber' ),
		),
		5 => array(
			'icon'  => 'cube',
			'title' => __( 'Multi-store Management', 'amber' ),
			'text'  => __( 'Efficiently manage multiple stores with smart optimization and collaboration. Real-time visibility across all locations ensures seamless operations and hassle-free expansion.', 'amber' ),
		),
		6 => array(
			'icon'  => 'briefcase',
			'title' => __( 'Integrated Accounting', 'amber' ),
			'text'  => __( 'Ensure tax compliance with automated GST calculations through an integrated POS system, supporting informed decision-making from purchase to sale.', 'amber' ),
		),
		7 => array(
			'icon'  => 'shield',
			'title' => __( 'Data Security and Backup', 'amber' ),
			'text'  => __( 'Safeguard your business data with POS software built on a robust security management framework, keeping every transaction protected.', 'amber' ),
		),
	);
}

/**
 * Default content for the Amber ERP Software "Industries We Serve" section.
 *
 * Shared by the ERP template and the ACF registration (field names
 * erp_ind{n}_icon / erp_ind{n}_label). Icons map to amber_icon_svg().
 *
 * @return array[] Eight industries; each: icon, label.
 */
function amber_erp_industry_defaults() {
	return array(
		1 => array( 'icon' => 'cart', 'label' => __( 'Retail & E-commerce', 'amber' ) ),
		2 => array( 'icon' => 'factory', 'label' => __( 'Manufacturing', 'amber' ) ),
		3 => array( 'icon' => 'utensils', 'label' => __( 'Restaurants', 'amber' ) ),
		4 => array( 'icon' => 'health', 'label' => __( 'Healthcare', 'amber' ) ),
		5 => array( 'icon' => 'truck', 'label' => __( 'Logistics', 'amber' ) ),
		6 => array( 'icon' => 'cap', 'label' => __( 'Education', 'amber' ) ),
		7 => array( 'icon' => 'pin', 'label' => __( 'Real Estate', 'amber' ) ),
		8 => array( 'icon' => 'briefcase', 'label' => __( 'Professional Services', 'amber' ) ),
	);
}

/**
 * Placeholder logos for the "Brands That Trust" strips.
 *
 * These are the last-resort stand-ins behind amber_client_logos(): they render
 * only while the Client Logos CPT has no usable entry, so a fresh install
 * never shows an empty strip. Each ships a bundled wordmark SVG under
 * assets/images/brands/.
 *
 * Only 8 distinct placeholder marks exist, but the grid (per the integrations
 * -page reference) shows 3 rows of 6 (18 slots) — so the set is tiled/cycled
 * past 8, offsetting the start index each "lap" so the same mark never lands
 * in the same grid column twice.
 *
 * @return array[] Eighteen brand slots; each: name, logo (bundled SVG URL).
 */
function amber_erp_brand_defaults() {
	$dir = AMBER_URI . 'assets/images/brands/';
	$set = array(
		array( 'name' => 'Northwind Retail', 'logo' => $dir . 'northwind-retail.svg' ),
		array( 'name' => 'Alaina Group', 'logo' => $dir . 'alaina-group.svg' ),
		array( 'name' => 'Oasis Foods', 'logo' => $dir . 'oasis-foods.svg' ),
		array( 'name' => 'Radish & Co.', 'logo' => $dir . 'radish-co.svg' ),
		array( 'name' => 'Australian Traders', 'logo' => $dir . 'australian-traders.svg' ),
		array( 'name' => 'Habit Stores', 'logo' => $dir . 'habit-stores.svg' ),
		array( 'name' => 'La Sheen', 'logo' => $dir . 'la-sheen.svg' ),
		array( 'name' => 'Amira Holdings', 'logo' => $dir . 'amira-holdings.svg' ),
	);

	$cols  = 6;
	$total = 18;
	$defaults = array();
	for ( $i = 0; $i < $total; $i++ ) {
		// Offset each 6-wide row by one extra step so column N never repeats
		// the same mark it showed in the row above.
		$row    = (int) floor( $i / $cols );
		$source = ( $i + $row ) % count( $set );
		$defaults[ $i + 1 ] = $set[ $source ];
	}
	return $defaults;
}

/**
 * Default content for the "How To Choose" checklist section.
 *
 * Shared by template-parts/sections/section-choose.php and the ACF Free
 * registration (field name choose_point{n}) so admin placeholders always
 * match the demo output.
 *
 * @return array<int,string> Seven checklist points, 1-indexed.
 */
function amber_choose_points_defaults() {
	return array(
		1 => __( 'Manage multiple counters, accept payments, and support mobile billing solutions to manage the peak hours efficiently.', 'amber' ),
		2 => __( 'Attract customers with various promotional offers and discounts.', 'amber' ),
		3 => __( 'Support omnichannel solutions to acquire new customers and drive more sales.', 'amber' ),
		4 => __( 'Accelerate the success of your business with digital mobile solutions.', 'amber' ),
		5 => __( 'Expand your success by controlling all your stores with a centralized web-based supply chain management system.', 'amber' ),
		6 => __( 'Boost your business without any barriers by having the freedom to integrate with third-party solutions.', 'amber' ),
		7 => __( 'Eliminate double work with the support of integrated accounting software.', 'amber' ),
	);
}

/**
 * Default content for the "WhatsApp Integration" checklist section.
 *
 * Shared by template-parts/sections/section-whatsapp.php and the ACF Free
 * registration (field name whatsapp_point{n}) so admin placeholders always
 * match the demo output.
 *
 * @return array<int,string> Four checklist points, 1-indexed.
 */
function amber_whatsapp_points_defaults() {
	return array(
		1 => __( 'Send automated order confirmations, invoices and delivery updates straight to your customer\'s WhatsApp.', 'amber' ),
		2 => __( 'Let customers browse products, ask questions and place orders in a familiar chat interface.', 'amber' ),
		3 => __( 'Trigger low-stock, payment and appointment reminders without lifting a finger.', 'amber' ),
		4 => __( 'Sync every conversation back into Amber ERP Software so your team always has the full context.', 'amber' ),
	);
}

/**
 * Default content for the homepage "Products Showcase" section.
 *
 * Shared by template-parts/sections/section-products.php and the ACF Free
 * registration (field names products{n}_name/icon/accent/desc/desc2/link_*).
 *
 * @return array[] Four products; each: name, desc, desc2, accent, icon, link.
 */
function amber_home_products_defaults() {
	return array(
		1 => array(
			'name'   => 'Amber ERP Software',
			'desc'   => __( 'Amber ERP Software is an advanced enterprise resource planning solution designed to unify finance, inventory, procurement, and business operations within a single intelligent platform. By providing real-time visibility into every process, Amber ERP Software helps businesses improve efficiency, streamline workflows, and make smarter decisions faster.', 'amber' ),
			'desc2'  => __( 'Built to scale with growing businesses, the platform eliminates data silos, simplifies approvals, enhances operational control, and enables organizations to manage their entire workflow seamlessly through one centralized system.', 'amber' ),
			'accent' => '#0ea5e9',
			'icon'   => 'layers',
			'link'   => array( 'url' => home_url( '/amber-erp/' ), 'title' => __( 'View Details', 'amber' ) ),
		),
		2 => array(
			'name'   => 'Amber POS Software',
			'desc'   => __( 'Amber POS Software is a fast, reliable point-of-sale system designed to simplify sales and everyday operations for retailers of every size. Billing, inventory and customers all live in one clean interface your staff can master in minutes.', 'amber' ),
			'desc2'  => __( 'From a single till to a multi-store chain, Amber POS Software keeps stock accurate, checkout quick and reporting real-time — so you always know exactly how the business is performing.', 'amber' ),
			'accent' => '#f59e0b',
			'icon'   => 'chart',
			'link'   => array( 'url' => home_url( '/pos-software/' ), 'title' => __( 'View Details', 'amber' ) ),
		),
		3 => array(
			'name'   => 'Amber Restaurant Software',
			'desc'   => __( 'Amber Restaurant Software is an all-in-one restaurant platform to manage orders, tables, kitchen and customers from one place. QR ordering, a kitchen display system and table management work together to keep service flowing.', 'amber' ),
			'desc2'  => __( 'Delivery, analytics and customer tools round out the suite, giving restaurants everything they need to run smoother shifts and delight more guests.', 'amber' ),
			'accent' => '#10b981',
			'icon'   => 'bolt',
			'link'   => array( 'url' => home_url( '/zoomie-restaurant/' ), 'title' => __( 'View Details', 'amber' ) ),
		),
		4 => array(
			'name'   => 'Amber Laundry Software',
			'desc'   => __( 'Amber Laundry Software is smart laundry management software that simplifies pickup, tracking and the whole customer experience. Schedule collections, follow every order and keep customers informed automatically.', 'amber' ),
			'desc2'  => __( 'With staff management and detailed reporting built in, Amber Laundry Software helps laundries run efficiently while giving customers a modern, transparent service.', 'amber' ),
			'accent' => '#6366f1',
			'icon'   => 'rocket',
			'link'   => array( 'url' => home_url( '/gozzbe-laundry/' ), 'title' => __( 'View Details', 'amber' ) ),
		),
	);
}

/**
 * Default content for the homepage "Industries We Serve" grid.
 *
 * Shared by template-parts/sections/section-industries.php and the ACF Free
 * registration (field names industries{n}_name/icon/desc).
 *
 * @return array[] Eight industries; each: name, icon, desc.
 */
function amber_home_industries_defaults() {
	return array(
		1 => array( 'name' => __( 'Retail & Wholesale', 'amber' ), 'icon' => 'cart', 'desc' => __( 'Simplify inventory, procurement, distribution, and multi-channel sales management.', 'amber' ) ),
		2 => array( 'name' => __( 'Restaurants & Cafés', 'amber' ), 'icon' => 'utensils', 'desc' => __( 'Manage orders, tables, kitchen and delivery from one integrated platform.', 'amber' ) ),
		3 => array( 'name' => __( 'Laundry & Dry Cleaning', 'amber' ), 'icon' => 'washer', 'desc' => __( 'Automate pickups, order tracking and customer updates from end to end.', 'amber' ) ),
		4 => array( 'name' => __( 'Manufacturing', 'amber' ), 'icon' => 'factory', 'desc' => __( 'Streamline production planning, inventory, procurement and quality control.', 'amber' ) ),
		5 => array( 'name' => __( 'Distribution', 'amber' ), 'icon' => 'truck', 'desc' => __( 'Optimise fleet, warehousing and delivery with full supply-chain visibility.', 'amber' ) ),
		6 => array( 'name' => __( 'Education', 'amber' ), 'icon' => 'cap', 'desc' => __( 'Streamline admissions, student records, attendance and academic administration.', 'amber' ) ),
		7 => array( 'name' => __( 'Healthcare', 'amber' ), 'icon' => 'health', 'desc' => __( 'Manage appointments, records and billing with complete operational visibility.', 'amber' ) ),
		8 => array( 'name' => __( 'Services & More', 'amber' ), 'icon' => 'briefcase', 'desc' => __( 'Streamline field service operations, maintenance activities and customer support.', 'amber' ) ),
	);
}

/**
 * Default content for the homepage "Why Choose" (bento) cards.
 *
 * Shared by template-parts/sections/section-bento.php and the ACF Free
 * registration (field names bento{n}_icon/title/feature/text).
 *
 * @return array[] Bento cards; each: icon, title, feature (bool), text.
 */
function amber_home_bento_defaults() {
	return array(
		1 => array( 'icon' => 'bolt', 'title' => __( 'Fast Implementation', 'amber' ), 'feature' => true, 'text' => __( 'Guided onboarding and sensible defaults get every module live in weeks, not months.', 'amber' ) ),
		2 => array( 'icon' => 'shield', 'title' => __( 'Enterprise Security', 'amber' ), 'feature' => false, 'text' => __( 'Role-based access and encryption keep your data protected end to end.', 'amber' ) ),
		3 => array( 'icon' => 'chart', 'title' => __( 'Real-time Insights', 'amber' ), 'feature' => false, 'text' => __( 'Live dashboards turn everyday activity into decisions you can act on.', 'amber' ) ),
		4 => array( 'icon' => 'globe', 'title' => __( 'Built to Scale', 'amber' ), 'feature' => false, 'text' => __( 'One connected platform that grows from a single store to a multi-branch business.', 'amber' ) ),
		5 => array( 'icon' => 'support', 'title' => __( 'Always-on Support', 'amber' ), 'feature' => false, 'text' => __( 'Real specialists, SLA-backed response times and proactive monitoring.', 'amber' ) ),
		6 => array( 'icon' => 'layers', 'title' => __( 'Open Integrations', 'amber' ), 'feature' => false, 'text' => __( 'Connect the tools you already use through a clean REST API.', 'amber' ) ),
	);
}

/**
 * Default content for the homepage "Live Product Experience" tabs.
 *
 * Shared by template-parts/sections/section-live_demo.php and the ACF Free
 * registration (field names live{n}_name/accent/kpis, kpis as "Label | Value"
 * lines matching the free-field textarea format).
 *
 * @return array[] Four product tabs; each: name, accent, kpis (label/value pairs).
 */
function amber_home_live_defaults() {
	return array(
		1 => array(
			'name'   => 'Amber ERP Software',
			'accent' => '#0ea5e9',
			'kpis'   => array(
				array( 'label' => __( 'Revenue', 'amber' ), 'value' => '$248.5K' ),
				array( 'label' => __( 'Orders', 'amber' ), 'value' => '1,240' ),
				array( 'label' => __( 'Stock value', 'amber' ), 'value' => '$1.2M' ),
			),
		),
		2 => array(
			'name'   => 'Amber POS Software',
			'accent' => '#f59e0b',
			'kpis'   => array(
				array( 'label' => __( 'Today’s sales', 'amber' ), 'value' => '$8,430' ),
				array( 'label' => __( 'Receipts', 'amber' ), 'value' => '312' ),
				array( 'label' => __( 'Avg. basket', 'amber' ), 'value' => '$27.0' ),
			),
		),
		3 => array(
			'name'   => 'Amber Restaurant Software',
			'accent' => '#10b981',
			'kpis'   => array(
				array( 'label' => __( 'Live orders', 'amber' ), 'value' => '38' ),
				array( 'label' => __( 'Tables', 'amber' ), 'value' => '24/30' ),
				array( 'label' => __( 'Avg. prep', 'amber' ), 'value' => '12 min' ),
			),
		),
		4 => array(
			'name'   => 'Amber Laundry Software',
			'accent' => '#6366f1',
			'kpis'   => array(
				array( 'label' => __( 'Pickups', 'amber' ), 'value' => '146' ),
				array( 'label' => __( 'In progress', 'amber' ), 'value' => '57' ),
				array( 'label' => __( 'On-time', 'amber' ), 'value' => '98.2%' ),
			),
		),
	);
}

/**
 * Default content for the homepage "Reviews" (testimonials) slider.
 *
 * Shared by template-parts/sections/section-testimonials.php and the ACF Free
 * registration (field names testi{n}_quote/name/role/avatar).
 *
 * @return array[] Four testimonials; each: quote, name, role, avatar.
 */
function amber_home_testimonials_defaults() {
	return array(
		1 => array(
			'quote' => __( 'Amber Technologies delivers results, not just software. Their ERP solution boosted our sales and client satisfaction across every branch.', 'amber' ),
			'name'  => __( 'Rajesh Kumar', 'amber' ),
			'role'  => __( 'CEO, Kumar Industries', 'amber' ),
		),
		2 => array(
			'quote' => __( 'Amber Restaurant Software transformed our restaurant operations. QR ordering and the kitchen display cut waiting times, and order accuracy went way up.', 'amber' ),
			'name'  => __( 'Anika Sharma', 'amber' ),
			'role'  => __( 'Owner, The Food Dish', 'amber' ),
		),
		3 => array(
			'quote' => __( 'Very expert in their domain — they provide excellent solutions customised to what clients and partners actually need. A real asset to any company.', 'amber' ),
			'name'  => __( 'Mohammed Salahuddin', 'amber' ),
			'role'  => __( 'Operations Director', 'amber' ),
		),
		4 => array(
			'quote' => __( 'We rolled out Amber POS Software across twelve stores. Billing is faster, stock is finally accurate, and the multi-store reports run themselves.', 'amber' ),
			'name'  => __( 'Imran Shaikh', 'amber' ),
			'role'  => __( 'Manager, CleanWash Retail', 'amber' ),
		),
	);
}

/**
 * Default content for the homepage FAQ accordion.
 *
 * Shared by template-parts/sections/section-faq.php and the ACF Free
 * registration (field names home_faq{n}_q/a).
 *
 * @return array[] Seven FAQs; each: q, a.
 */
function amber_home_faq_defaults() {
	return array(
		1 => array(
			'q' => __( 'What makes Amber the right choice for ERP implementation?', 'amber' ),
			'a' => __( 'Amber helps businesses simplify operations through customised ERP solutions designed around real business workflows. From implementation and integration to support and optimisation, we focus on building systems that improve efficiency and scalability.', 'amber' ),
		),
		2 => array(
			'q' => __( 'Why do businesses choose Amber ERP Software solutions?', 'amber' ),
			'a' => __( 'Businesses choose Amber ERP Software because it offers flexibility, automation, and centralised business management. Amber helps companies manage sales, inventory, accounting, CRM, HR, and operations from one connected platform.', 'amber' ),
		),
		3 => array(
			'q' => __( 'Can Amber customise the ERP based on business requirements?', 'amber' ),
			'a' => __( 'Yes. Every business operates differently, which is why our ERP solutions are customised to match specific workflows, operational needs, and industry requirements for better efficiency and usability.', 'amber' ),
		),
		4 => array(
			'q' => __( 'Why work with a local technology partner?', 'amber' ),
			'a' => __( 'Working with a local partner helps businesses receive localised support, better implementation guidance, and solutions aligned with regional operational requirements and business practices.', 'amber' ),
		),
		5 => array(
			'q' => __( 'How does Amber POS Software help retail businesses grow?', 'amber' ),
			'a' => __( 'Amber POS Software simplifies billing, inventory and customer management across single tills and multi-store chains — giving retailers faster checkouts, accurate stock visibility, and the reports they need to grow.', 'amber' ),
		),
		6 => array(
			'q' => __( 'Can Amber support restaurants and laundry businesses too?', 'amber' ),
			'a' => __( 'Yes. Amber Restaurant Software manages QR ordering, kitchen displays and tables for restaurants, while Amber Laundry Software handles pickup, delivery and order tracking for laundries — both connected to the same Amber ecosystem.', 'amber' ),
		),
		7 => array(
			'q' => __( 'Why does data security matter in today’s digital market?', 'amber' ),
			'a' => __( 'Reliable, secure data management protects your customers and your reputation. Amber products encrypt data in transit and at rest, with role-based access and dependable backups built in.', 'amber' ),
		),
	);
}

/**
 * Default reseller cards for the Contact page's "Our Locations" band.
 *
 * Shared by template-parts/sections/section-locations.php and the ACF
 * registration (field names loc{n}_*), so the admin's placeholders always match
 * what the page actually shows. Transcribed from the reseller records; each
 * slot is overridable per field, and clearing a slot's Country removes it.
 *
 * @return array<int,array<string,string>> Four slots; the fourth is spare.
 */
function amber_reseller_defaults() {
	return array(
		1 => array(
			'city'    => __( 'Bahrain', 'amber' ),
			'flag'    => '🇧🇭',
			'company' => __( 'Ainush Software Solutions W.L.L', 'amber' ),
			'address' => __( 'Office 31, Building 1, Block 321, Zubara Avenue, Gudaibiya, Kingdom of Bahrain', 'amber' ),
			'phone1'  => '+973 3451821',
			'phone2'  => '+973 17310420',
			'phone3'  => '',
			'email'   => 'info@ainush.com',
			'website' => '',
		),
		2 => array(
			'city'    => __( 'Oman', 'amber' ),
			'flag'    => '🇴🇲',
			'company' => __( 'Softech Solution', 'amber' ),
			'address' => __( 'Al Koudh, Muscat, Sultanate of Oman', 'amber' ),
			'phone1'  => '+968 7414 0070',
			'phone2'  => '+968 9694 0703',
			'phone3'  => '',
			'email'   => 'info@softechoman.com',
			'website' => 'https://www.softechoman.com',
		),
		3 => array(
			'city'    => __( 'Qatar', 'amber' ),
			'flag'    => '🇶🇦',
			'company' => __( 'Amalgamate Technologies', 'amber' ),
			'address' => __( 'Street 696, Zone 90, Al Wakra, Qatar', 'amber' ),
			'phone1'  => '+974 7701 4916',
			'phone2'  => '+974 3003 2216',
			'phone3'  => '+974 3003 2217',
			'email'   => 'support@amalgamatetechnologies.com',
			'website' => 'https://amalgamatetechnologies.com/',
		),
		// Spare slot — stays hidden until a Country is filled in.
		4 => array(
			'city'    => '',
			'flag'    => '',
			'company' => '',
			'address' => '',
			'phone1'  => '',
			'phone2'  => '',
			'phone3'  => '',
			'email'   => '',
			'website' => '',
		),
	);
}

/**
 * The client logos, as one shared list for the whole site.
 *
 * Every logo strip renders from this — the homepage "From Concept To Success"
 * marquee, the trusted-by slider, the POS hero strip and the "Brands That
 * Trust" grid on each product page — so one edit in wp-admin → Client Logos
 * updates all of them. Before this, only the homepage marquee read the CPT:
 * the product pages each stored their own erp_brand{n}_* copies of the bundled
 * placeholder marks, so no two strips showed the same clients.
 *
 * The `client_logo` CPT is the source of truth (title = alt text, featured
 * image = the mark, Order = sequence). Entries without an image are skipped,
 * since a logo strip has nothing to show for them. With no usable entries at
 * all the bundled placeholder marks stand in, so the strips are never empty
 * on a fresh install.
 *
 * @param int $limit Optional. Return at most this many logos (0 = all).
 * @return array<int,array{name:string,logo:mixed}> Same shape as
 *                                                  amber_erp_brand_defaults().
 */
function amber_client_logos( $limit = 0 ) {
	static $cache = null;

	if ( null === $cache ) {
		$cache = array();

		$ids = get_posts(
			array(
				'post_type'      => 'client_logo',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		foreach ( $ids as $id ) {
			if ( ! has_post_thumbnail( $id ) ) {
				continue;
			}
			$cache[] = array(
				'name' => get_the_title( $id ),
				'logo' => get_post_thumbnail_id( $id ),
			);
		}

		if ( empty( $cache ) ) {
			$cache = array_values( amber_erp_brand_defaults() );
		}
	}

	return $limit > 0 ? array_slice( $cache, 0, $limit ) : $cache;
}

/**
 * The client logos laid out to fill a fixed grid.
 *
 * The "Brands That Trust" grid is a 6-across, 3-row block by design, so it
 * needs 18 tiles regardless of how many clients are on file. This cycles the
 * shared set to fill them, shifting the start index one step per row so the
 * same logo never lands in the same column twice — the arrangement the bundled
 * placeholders used to produce. Once the Client Logos CPT holds 18 or more
 * entries nothing repeats at all.
 *
 * @param int $count Tiles to fill.
 * @param int $cols  Columns the grid renders at its widest.
 * @return array<int,array{name:string,logo:mixed}>
 */
function amber_client_logos_grid( $count = 18, $cols = 6 ) {
	$set   = amber_client_logos();
	$total = count( $set );

	if ( ! $total ) {
		return array();
	}

	$tiles = array();
	for ( $i = 0; $i < $count; $i++ ) {
		$row     = (int) floor( $i / $cols );
		$tiles[] = $set[ ( $i + $row ) % $total ];
	}

	return $tiles;
}

/**
 * Whether a product-page repeater slot has been deliberately emptied.
 *
 * The product pages share one template, but each lists a different number of
 * feature cards and FAQs. Slots a page doesn't use are saved as an empty
 * string. amber_sub() treats empty as "unset" and falls back to the shared
 * demo copy, which would resurrect content the page meant to drop — so the
 * template asks this instead, which reads the stored value directly.
 *
 * Returns false when nothing is stored at all (a fresh page with no ACF data),
 * so untouched pages still show their defaults.
 *
 * @param string $field Field name, e.g. 'erp_ft9_label'.
 * @return bool True when the field exists and is empty.
 */
function amber_erp_slot_is_cleared( $field ) {
	return amber_field_is_cleared( $field );
}

/**
 * Whether a field on this page was deliberately saved empty.
 *
 * Numbered-slot sections (product features, FAQs, reseller cards) let a page
 * drop a slot by clearing its heading. amber_sub() reads empty as "unset" and
 * would hand back the demo copy, resurrecting content the page meant to lose —
 * so templates ask this instead, which reads the stored value directly.
 *
 * Returns false when nothing is stored at all (a page never saved through the
 * admin), so untouched pages still show their defaults.
 *
 * @param string $field Field name, e.g. 'loc1_city'.
 * @return bool True when the field exists and is empty.
 */
function amber_field_is_cleared( $field ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$pid = get_the_ID();
	if ( ! $pid ) {
		return false;
	}

	// get_field() returns null for "never set" and '' for "saved empty".
	$value = get_field( $field, $pid );

	return null !== $value && '' === trim( (string) $value );
}

/**
 * Per-product config for the shared product-page body template
 * (template-parts/erp/amber-erp-page.php). Each product page template sets
 * $GLOBALS['amber_erp_product'] before loading the part; the body reads the
 * matching config here. 'name'/'short' also drive automatic text substitution
 * of the ERP-flavoured defaults ("Amber ERP Software" → name, "ERP" → short).
 *
 * @param string $key Optional product key ('erp'|'zoomie'|'gozzbe'|'pos'). Defaults
 *                    to the key the current page template set.
 * @return array{key:string,name:string,short:string,category:string,hero_accent:string,hero_rest:string,hero_lead_bold:string,hero_lead:string}
 */
function amber_erp_page_product( $key = '' ) {
	if ( '' === $key ) {
		$key = isset( $GLOBALS['amber_erp_product'] ) ? $GLOBALS['amber_erp_product'] : 'erp';
	}

	$products = array(
		'erp'    => array(
			'name'           => __( 'Amber ERP Software', 'amber' ),
			'short'          => __( 'ERP', 'amber' ),
			'category'       => __( 'Enterprise Resource Planning Software', 'amber' ),
			'hero_accent'    => __( 'Transform Your Business', 'amber' ),
			'hero_rest'      => __( 'with Amber ERP Software', 'amber' ),
			'hero_lead_bold' => __( 'Unify every department, automate the busywork, and make faster decisions effortlessly.', 'amber' ),
			'hero_lead'      => __( 'Amber ERP Software is a connected business platform that unifies finance, inventory, sales and operations in one intelligent system. By capturing every transaction in real time, Amber ERP Software turns everyday business activity into actionable insights that power confident, data-driven decisions.', 'amber' ),
		),
		'zoomie' => array(
			'name'           => __( 'Amber Restaurant Software', 'amber' ),
			'short'          => __( 'Restaurant', 'amber' ),
			'category'       => __( 'Restaurant Management Software', 'amber' ),
			'hero_accent'    => __( 'Transform Your Business', 'amber' ),
			'hero_rest'      => __( 'with Amber Restaurant Software', 'amber' ),
			'hero_lead_bold' => __( 'Personalise every order, automate the kitchen and delight more diners effortlessly.', 'amber' ),
			'hero_lead'      => __( 'Amber Restaurant Software is an all-in-one restaurant platform that unifies QR ordering, kitchen displays, tables and delivery in one intelligent system. By capturing every order in real time, Amber Restaurant Software turns everyday service into actionable insights that power faster, smarter restaurants.', 'amber' ),
		),
		'gozzbe' => array(
			'name'           => __( 'Amber Laundry Software', 'amber' ),
			'short'          => __( 'Laundry', 'amber' ),
			'category'       => __( 'Laundry Management Software', 'amber' ),
			'hero_accent'    => __( 'Transform Your Business', 'amber' ),
			'hero_rest'      => __( 'with Amber Laundry Software', 'amber' ),
			'hero_lead_bold' => __( 'Personalise every pickup, automate reminders and retain more customers effortlessly.', 'amber' ),
			'hero_lead'      => __( 'Amber Laundry Software is a smart laundry platform that unifies pickups, processing, delivery and customers in one intelligent system. By tracking every garment in real time, Amber Laundry Software turns daily operations into actionable insights that power cleaner, faster service.', 'amber' ),
		),
		'pos'    => array(
			'name'           => __( 'Amber POS Software', 'amber' ),
			'short'          => __( 'POS', 'amber' ),
			'category'       => __( 'Point of Sale Software', 'amber' ),
			'hero_accent'    => __( 'Transform Your Business', 'amber' ),
			'hero_rest'      => __( 'with Amber POS Software', 'amber' ),
			'hero_lead_bold' => __( 'Ring up every sale, automate stock updates and serve customers effortlessly.', 'amber' ),
			'hero_lead'      => __( 'Amber POS Software is a fast, reliable point-of-sale that unifies billing, inventory and customers in one intelligent system. By capturing every sale in real time, Amber POS Software turns checkout activity into actionable insights that power smarter retail.', 'amber' ),
		),
	);

	$product        = isset( $products[ $key ] ) ? $products[ $key ] : $products['erp'];
	$product['key'] = isset( $products[ $key ] ) ? $key : 'erp';

	return $product;
}

/**
 * Which product a page belongs to, judged by its page template.
 *
 * $GLOBALS['amber_erp_product'] is only set once a product page template runs,
 * so anything that needs the product *before* then (the stored-value rewrite
 * below runs from an ACF filter) resolves it from the post itself instead.
 *
 * @param int $post_id Page ID.
 * @return string Product key, or '' when the page isn't a product page.
 */
function amber_erp_product_key_for_post( $post_id ) {
	$templates = array(
		'page-templates/page-amber-erp.php' => 'erp',
		'page-templates/page-zoomie.php'    => 'zoomie',
		'page-templates/page-gozzbe.php'    => 'gozzbe',
		'page-templates/page-pos.php'       => 'pos',
	);

	$template = get_page_template_slug( $post_id );

	return isset( $templates[ $template ] ) ? $templates[ $template ] : '';
}

/**
 * Rewrite ERP-flavoured product wording for the product a page actually sells.
 *
 * The four product pages share one template *and* one set of ACF fields, and
 * every one of those fields carries an ERP-worded `default_value`. ACF writes
 * those defaults into a page's own meta the first time it is saved, so the
 * Restaurant/Laundry/POS pages each ended up storing "Try Amber ERP Software
 * Free for 14 Days" and friends — stored values win over the template's
 * product-aware defaults, which is why every product page rendered the ERP
 * copy in its CTA and the other shared sections.
 *
 * This performs the same substitution the template already applies to its own
 * defaults ("Amber ERP Software" → the product's name, a bare "ERP" → its short
 * name), plus the two "Enterprise Resource Planning" category phrasings, so
 * stored copy that was never product-specific reads correctly on every page.
 * URLs and paths are left alone — only display copy is rewritten.
 *
 * @param mixed $value   String (or array of strings) to rewrite.
 * @param array $product Product config from amber_erp_page_product().
 * @return mixed Same shape as $value.
 */
function amber_erp_localize( $value, array $product ) {
	if ( 'erp' === $product['key'] ) {
		return $value;
	}

	// Link/image fields arrive as arrays; rewrite their labels, not their URLs.
	if ( is_array( $value ) ) {
		foreach ( $value as $key => $item ) {
			if ( in_array( $key, array( 'url', 'link', 'filename', 'mime_type' ), true ) ) {
				continue;
			}
			$value[ $key ] = amber_erp_localize( $item, $product );
		}
		return $value;
	}

	if ( ! is_string( $value ) || '' === $value ) {
		return $value;
	}

	// Anchors, paths and absolute URLs are addresses, not copy.
	if ( preg_match( '~^(?:https?:)?//|^[/#]~', $value ) ) {
		return $value;
	}

	// Longest phrase first, so "Amber ERP Software" is consumed before the
	// bare-"ERP" pass below can touch it.
	$category = $product['category'];
	$value    = str_replace(
		array( 'Amber ERP Software', 'Enterprise Resource Planning Software', 'Enterprise Resource Planning' ),
		array( $product['name'], $category, preg_replace( '/\s*Software$/i', '', $category ) ),
		$value
	);

	// Remaining standalone "ERP" mentions → the product's short name. Word
	// boundaries keep it out of the middle of words (e.g. "ambererp.app").
	return preg_replace( '/\bERP\b/', $product['short'], $value );
}
