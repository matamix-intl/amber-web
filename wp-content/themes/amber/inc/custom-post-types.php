<?php
/**
 * Custom post types & taxonomies.
 *
 * Most homepage content is driven by the ACF page-builder (flexible content +
 * repeaters). For genuinely scalable, individually-addressable content we use
 * real CPTs: Services (with a Service Category taxonomy) and Testimonials.
 * Sections can pull from either repeaters OR these CPTs.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Service CPT + Service Category taxonomy.
 *
 * @return void
 */
function amber_register_services_cpt() {
	$labels = array(
		'name'               => __( 'Services', 'amber' ),
		'singular_name'      => __( 'Service', 'amber' ),
		'add_new'            => __( 'Add New', 'amber' ),
		'add_new_item'       => __( 'Add New Service', 'amber' ),
		'edit_item'          => __( 'Edit Service', 'amber' ),
		'new_item'           => __( 'New Service', 'amber' ),
		'view_item'          => __( 'View Service', 'amber' ),
		'search_items'       => __( 'Search Services', 'amber' ),
		'not_found'          => __( 'No services found', 'amber' ),
		'not_found_in_trash' => __( 'No services found in Trash', 'amber' ),
		'menu_name'          => __( 'Services', 'amber' ),
	);

	register_post_type(
		'service',
		array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'menu_icon'           => 'dashicons-screenoptions',
			'menu_position'       => 22,
			'rewrite'             => array( 'slug' => 'services' ),
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'        => true, // Gutenberg + REST.
			'exclude_from_search' => false,
		)
	);

	register_taxonomy(
		'service_cat',
		'service',
		array(
			'labels'            => array(
				'name'          => __( 'Service Categories', 'amber' ),
				'singular_name' => __( 'Service Category', 'amber' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'service-category' ),
		)
	);
}
add_action( 'init', 'amber_register_services_cpt' );

/**
 * Register the Testimonial CPT.
 *
 * @return void
 */
function amber_register_testimonials_cpt() {
	$labels = array(
		'name'          => __( 'Testimonials', 'amber' ),
		'singular_name' => __( 'Testimonial', 'amber' ),
		'add_new_item'  => __( 'Add New Testimonial', 'amber' ),
		'edit_item'     => __( 'Edit Testimonial', 'amber' ),
		'menu_name'     => __( 'Testimonials', 'amber' ),
	);

	register_post_type(
		'testimonial',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_icon'    => 'dashicons-format-quote',
			'menu_position' => 23,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true,
			'rewrite'      => false,
		)
	);
}
add_action( 'init', 'amber_register_testimonials_cpt' );

/**
 * Register the Client Logo CPT — powers the "From Concept To Success" client
 * marquee (and any other trusted-by strip). Each entry is just a title (used as
 * the image alt text) plus a featured image (the logo itself). Use the Order
 * field to drag logos into the sequence you want. Works on core WordPress — no
 * ACF Pro required.
 *
 * @return void
 */
function amber_register_client_logos_cpt() {
	$labels = array(
		'name'          => __( 'Client Logos', 'amber' ),
		'singular_name' => __( 'Client Logo', 'amber' ),
		'add_new'       => __( 'Add New', 'amber' ),
		'add_new_item'  => __( 'Add New Client Logo', 'amber' ),
		'edit_item'     => __( 'Edit Client Logo', 'amber' ),
		'new_item'      => __( 'New Client Logo', 'amber' ),
		'view_item'     => __( 'View Client Logo', 'amber' ),
		'search_items'  => __( 'Search Client Logos', 'amber' ),
		'not_found'     => __( 'No client logos yet', 'amber' ),
		'menu_name'     => __( 'Client Logos', 'amber' ),
	);

	register_post_type(
		'client_logo',
		array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_icon'           => 'dashicons-images-alt2',
			'menu_position'       => 24,
			// Title = alt text, thumbnail = the logo, page-attributes = ordering.
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest'        => true,
			'rewrite'             => false,
		)
	);
}
add_action( 'init', 'amber_register_client_logos_cpt' );

/**
 * Register the Case Study CPT + Case Study Tag taxonomy — powers the "Featured
 * Case Studies" rail. Each entry is a title (case-study name), a featured image
 * (the card background), an excerpt (the short summary revealed on hover) and
 * one or more Tags (the pills). Use the Order field to sequence the rail. Works
 * on core WordPress — no ACF Pro required.
 *
 * @return void
 */
function amber_register_case_studies_cpt() {
	$labels = array(
		'name'               => __( 'Case Studies', 'amber' ),
		'singular_name'      => __( 'Case Study', 'amber' ),
		'add_new'            => __( 'Add New', 'amber' ),
		'add_new_item'       => __( 'Add New Case Study', 'amber' ),
		'edit_item'          => __( 'Edit Case Study', 'amber' ),
		'new_item'           => __( 'New Case Study', 'amber' ),
		'view_item'          => __( 'View Case Study', 'amber' ),
		'search_items'       => __( 'Search Case Studies', 'amber' ),
		'not_found'          => __( 'No case studies found', 'amber' ),
		'not_found_in_trash' => __( 'No case studies found in Trash', 'amber' ),
		'menu_name'          => __( 'Case Studies', 'amber' ),
	);

	register_post_type(
		'case_study',
		array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'menu_icon'           => 'dashicons-portfolio',
			'menu_position'       => 25,
			'rewrite'             => array( 'slug' => 'case-studies' ),
			// Title = name, excerpt = hover summary, thumbnail = card image,
			// page-attributes = ordering.
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'        => true,
			'exclude_from_search' => false,
		)
	);

	register_taxonomy(
		'case_study_tag',
		'case_study',
		array(
			'labels'            => array(
				'name'          => __( 'Case Study Tags', 'amber' ),
				'singular_name' => __( 'Case Study Tag', 'amber' ),
			),
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'case-study-tag' ),
		)
	);
}
add_action( 'init', 'amber_register_case_studies_cpt' );

/**
 * Flush rewrite rules once after the theme is switched on, so CPT permalinks
 * work without a manual visit to Settings > Permalinks.
 *
 * @return void
 */
function amber_rewrite_flush() {
	amber_register_services_cpt();
	amber_register_testimonials_cpt();
	amber_register_client_logos_cpt();
	amber_register_case_studies_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'amber_rewrite_flush' );

/**
 * Make the Case Study editor self-explanatory so a non-technical editor knows
 * exactly which field maps to which part of the "Featured Case Studies" card:
 *   • Title            → the card heading
 *   • Featured image   → the full-bleed card background photo
 *   • Excerpt          → the short description revealed on hover
 *   • Case Study Tags  → the pills (App Development, UI/UX, …)
 *   • Order (Page Attr)→ the sequence in the rail
 *
 * WordPress hides the Excerpt box by default, so we rename it, give it help text
 * and force it visible for this CPT.
 *
 * @return void
 */
function amber_case_study_admin_help() {
	// Rename the Excerpt meta-box to something meaningful and add guidance.
	remove_meta_box( 'postexcerpt', 'case_study', 'normal' );
	add_meta_box(
		'postexcerpt',
		__( 'Card Description (shown on hover)', 'amber' ),
		'amber_case_study_excerpt_metabox',
		'case_study',
		'normal',
		'high'
	);

	// Guidance under the Featured Image box.
	add_filter(
		'admin_post_thumbnail_html',
		function ( $content, $post_id ) {
			if ( 'case_study' === get_post_type( $post_id ) ) {
				$content .= '<p class="description" style="margin-top:8px">' .
					esc_html__( 'This photo becomes the full-bleed card background. Use a landscape image (about 1200×800px or larger).', 'amber' ) .
					'</p>';
			}
			return $content;
		},
		10,
		2
	);
}
add_action( 'add_meta_boxes_case_study', 'amber_case_study_admin_help' );

/**
 * Render the (relabelled) excerpt box with help text — this is the card's hover
 * description.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function amber_case_study_excerpt_metabox( $post ) {
	?>
	<label class="screen-reader-text" for="excerpt"><?php esc_html_e( 'Card description', 'amber' ); ?></label>
	<textarea rows="3" cols="40" name="excerpt" id="excerpt" style="width:100%"><?php echo esc_textarea( $post->post_excerpt ); // phpcs:ignore ?></textarea>
	<p class="description">
		<?php esc_html_e( 'A short summary that appears when a visitor hovers the card. Keep it to one or two sentences.', 'amber' ); ?>
	</p>
	<?php
}

/**
 * A small "how this maps to the site" note at the top of the Case Study editor.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function amber_case_study_editor_intro( $post ) {
	if ( 'case_study' !== $post->post_type ) {
		return;
	}
	echo '<div class="notice notice-info inline" style="margin:12px 0"><p>'
		. esc_html__( 'Fill in: Title (card heading), Featured Image (card photo), Card Description below (hover text), and Case Study Tags (the pills). Drag the order with the “Order” field under Page Attributes.', 'amber' )
		. '</p></div>';
}
add_action( 'edit_form_after_title', 'amber_case_study_editor_intro' );
