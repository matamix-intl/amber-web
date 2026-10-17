<?php
/**
 * ACF bootstrap: detection, local JSON sync, options page, field loading.
 *
 * The theme treats ACF (Pro) as an enhancement, not a hard dependency. Every
 * front-end template has built-in defaults, so the site renders fully even
 * with ACF inactive. When ACF Pro IS active, this file:
 *   - points ACF Local JSON at /acf-json so groups are version-controlled and
 *     auto-exported (this gives you the importable JSON deliverable for free);
 *   - registers the global Options page;
 *   - loads the PHP field-group definitions.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the full ACF Pro feature set (flexible content, repeater, options) present?
 *
 * @return bool
 */
function amber_has_acf_pro() {
	// Options pages are Pro-only, so this is a reliable Pro signal.
	return function_exists( 'acf_add_options_page' );
}

/**
 * Tell ACF to save & load field-group JSON inside the theme.
 *
 * Saving to /acf-json means any change you make in the admin is mirrored to a
 * portable, importable JSON file automatically — no manual export needed.
 */
add_filter(
	'acf/settings/save_json',
	function () {
		$dir = AMBER_DIR . 'acf-json';
		if ( ! file_exists( $dir ) ) {
			wp_mkdir_p( $dir );
		}
		return $dir;
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		// Replace the default path with ours so groups load from the theme.
		$paths   = array_filter( $paths, fn( $p ) => false === strpos( $p, 'acf-json' ) );
		$paths[] = AMBER_DIR . 'acf-json';
		return $paths;
	}
);

/**
 * Register the global Options page (and a few sub-pages) — ACF Pro only.
 *
 * @return void
 */
function amber_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Theme Settings', 'amber' ),
			'menu_title' => __( 'Theme Settings', 'amber' ),
			'menu_slug'  => 'amber-settings',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 2,
			'redirect'   => true,
		)
	);

	foreach ( array(
		'general' => __( 'General & Branding', 'amber' ),
		'header'  => __( 'Header', 'amber' ),
		'footer'  => __( 'Footer', 'amber' ),
		'contact' => __( 'Contact & Social', 'amber' ),
	) as $slug => $title ) {
		acf_add_options_sub_page(
			array(
				'page_title'  => $title,
				'menu_title'  => $title,
				'parent_slug' => 'amber-settings',
				'menu_slug'   => 'amber-settings-' . $slug,
			)
		);
	}
}
add_action( 'acf/init', 'amber_register_options_page' );

/**
 * Load the PHP field-group definitions once ACF is ready.
 *
 * These run through acf_add_local_field_group(), which ignores unsupported
 * field types gracefully on ACF Free, so nothing fatals.
 *
 * @return void
 */
function amber_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	require AMBER_INC . 'acf/field-helpers.php';

	if ( amber_acf_is_pro() ) {
		// ACF PRO: the Options page + Flexible Content section builder.
		require AMBER_INC . 'acf/fields-options.php';
		require AMBER_INC . 'acf/fields-page-builder.php';
	} else {
		// ACF Free: flat, top-level fields (tabs + basic fields only).
		require AMBER_INC . 'acf/fields-free.php';
	}
}
add_action( 'acf/init', 'amber_register_fields' );

/**
 * Render stored product-page copy for the product the page actually sells.
 *
 * The Restaurant / Laundry / POS pages share the ERP page's template *and* its
 * field group, whose `default_value`s are all ERP-worded. ACF persists those
 * defaults into each page's own meta on first save, and a stored value beats
 * the template's product-aware fallback — so the CTA and the other shared
 * sections rendered ERP copy on every product page. Rewriting on read fixes
 * the whole family of sections at once (see amber_erp_localize()), including
 * pages saved before this and any saved after it.
 *
 * Front-end only: the editor keeps seeing exactly what is stored, so nothing
 * an admin deliberately typed can be rewritten back into the database.
 *
 * @param mixed      $value   Loaded field value.
 * @param int|string $post_id Post the value belongs to ('options', 'user_1'…).
 * @param array      $field   ACF field array.
 * @return mixed
 */
function amber_erp_localize_field_value( $value, $post_id, $field ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $value;
	}

	if ( empty( $field['name'] ) || 0 !== strpos( $field['name'], 'erp_' ) ) {
		return $value;
	}

	if ( ! is_numeric( $post_id ) ) {
		return $value;
	}

	$key = amber_erp_product_key_for_post( (int) $post_id );
	if ( '' === $key || 'erp' === $key ) {
		return $value;
	}

	return amber_erp_localize( $value, amber_erp_page_product( $key ) );
}
add_filter( 'acf/load_value', 'amber_erp_localize_field_value', 20, 3 );

/**
 * Show a friendly admin notice when ACF (Pro) is missing or only Free is active.
 *
 * @return void
 */
function amber_acf_admin_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	// No ACF at all → prompt to install it.
	if ( ! class_exists( 'ACF' ) ) {
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			wp_kses_post( __( 'Amber needs <strong>Advanced Custom Fields</strong> to edit content. Install ACF (Free or Pro) to manage your sections, header and footer.', 'amber' ) )
		);
		return;
	}

	// ACF Free → tell the user where to edit + what needs Pro. Show once.
	if ( ! amber_acf_is_pro() && ! get_user_meta( get_current_user_id(), 'amber_free_notice_dismissed', true ) ) {
		$front = (int) get_option( 'page_on_front' );
		$edit  = $front ? get_edit_post_link( $front ) : admin_url( 'options-reading.php' );
		printf(
			'<div class="notice notice-info is-dismissible amber-free-notice"><p>%s</p></div>',
			wp_kses_post(
				sprintf(
					/* translators: %s edit-home URL */
					__( 'Amber is running on <strong>ACF Free</strong>. Edit your headings, hero copy, the reveal video and global header/footer on the <a href="%s">Home page</a> (tabs: <em>Hero, Reveal, Site Settings…</em>). Repeating lists (products, testimonials, industries) use built-in defaults — those need ACF Pro to edit per item.', 'amber' ),
					esc_url( (string) $edit )
				)
			)
		);
	}
}
add_action( 'admin_notices', 'amber_acf_admin_notice' );
