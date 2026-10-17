<?php
/**
 * ACF field group: the Section Builder (Flexible Content).
 *
 * A single "sections" flexible-content field is attached to the front page and
 * all Pages. Each layout maps 1:1 to a template part in
 * /template-parts/sections. Because the same builder is available everywhere,
 * every layout is reusable across Home, About, Services, Contact and any other
 * page — exactly the brief's "reusable flexible content" requirement.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Assemble all flexible-content layouts.
 *
 * @return array
 */
function amber_builder_layouts() {
	return array(

		/* =====================================================  HERO  ===== */
		amber_layout(
			'hero',
			__( '★ Hero Banner', 'amber' ),
			array(
				amber_f_text( 'hero_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Helping Businesses Grow Through Smart ERP, POS & Business Management Solutions', 'amber' ) ) ),
				amber_f_textarea( 'hero_subheading', __( 'Subheading', 'amber' ), array( 'default_value' => __( 'From ERP implementation to digital growth strategies, we help businesses automate operations, improve visibility, and scale with confidence.', 'amber' ) ) ),
				amber_f_link( 'hero_primary_btn', __( 'Primary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_link( 'hero_secondary_btn', __( 'Secondary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			)
		),

		/* ====================================================  REVEAL  ===== */
		amber_layout(
			'reveal',
			__( '★ Scroll-Zoom Reveal', 'amber' ),
			array(
				amber_f_text(
					'reveal_title',
					__( 'Giant Wordmark', 'amber' ),
					array(
						'default_value' => __( 'AMBER TECHNOLOGIES', 'amber' ),
						'instructions'  => __( 'Large gradient text revealed behind the scaling media.', 'amber' ),
					)
				),
				amber_f_select(
					'reveal_media_type',
					__( 'Media', 'amber' ),
					array(
						'video' => __( 'Background video (Vimeo / YouTube / MP4)', 'amber' ),
						'image' => __( 'Image', 'amber' ),
					),
					array( 'default_value' => 'video', 'instructions' => __( 'Leave the URL/Image empty to show a blank media slot.', 'amber' ) )
				),
				amber_f(
					'url',
					'reveal_video_url',
					__( 'Video URL', 'amber' ),
					array(
						'instructions'      => __( 'Vimeo, YouTube or a direct .mp4/.webm link. Autoplays muted and looped.', 'amber' ),
						'conditional_logic' => array( array( array( 'field' => amber_fk( 'reveal_media_type' ), 'operator' => '==', 'value' => 'video' ) ) ),
					)
				),
				amber_f_image(
					'reveal_image',
					__( 'Image', 'amber' ),
					array(
						'instructions'      => __( 'Wide image (16:9 looks best).', 'amber' ),
						'conditional_logic' => array( array( array( 'field' => amber_fk( 'reveal_media_type' ), 'operator' => '==', 'value' => 'image' ) ) ),
					)
				),
			)
		),

		/* ==================================================  PRODUCTS  ===== */
		amber_layout(
			'products',
			__( '★ Products Showcase', 'amber' ),
			array_merge(
				amber_section_header_fields( 'products' ),
				array(
					amber_f_repeater(
						'products_items',
						__( 'Products', 'amber' ),
						array(
							amber_f_text( 'name', __( 'Product Name', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
							amber_f_icon( 'icon', __( 'Icon', 'amber' ), array( 'wrapper' => array( 'width' => '25' ) ) ),
							amber_f_color( 'accent', __( 'Accent Colour', 'amber' ), array( 'wrapper' => array( 'width' => '25' ), 'default_value' => '#0ea5e9' ) ),
							amber_f_textarea( 'desc', __( 'Short Description', 'amber' ), array( 'rows' => 2 ) ),
							amber_f_textarea( 'features', __( 'Features (one per line)', 'amber' ), array( 'rows' => 5 ) ),
							amber_f_link( 'link', __( 'Link', 'amber' ) ),
						),
						array( 'button_label' => __( 'Add Product', 'amber' ) )
					),
				),
				amber_section_style_fields( 'products' )
			)
		),

		/* ================================================  INDUSTRIES  ===== */
		amber_layout(
			'industries',
			__( 'Industries Grid', 'amber' ),
			array_merge(
				amber_section_header_fields( 'industries' ),
				array(
					amber_f_repeater(
						'industries_items',
						__( 'Industries', 'amber' ),
						array(
							amber_f_text( 'name', __( 'Name', 'amber' ), array( 'wrapper' => array( 'width' => '60' ) ) ),
							amber_f_select(
								'icon',
								__( 'Icon', 'amber' ),
								array(
									'cart' => 'Retail', 'utensils' => 'Restaurant', 'washer' => 'Laundry',
									'factory' => 'Manufacturing', 'truck' => 'Distribution', 'cap' => 'Education',
									'health' => 'Healthcare', 'briefcase' => 'Services', 'globe' => 'Global',
								),
								array( 'wrapper' => array( 'width' => '40' ) )
							),
						),
						array( 'button_label' => __( 'Add Industry', 'amber' ) )
					),
				),
				amber_section_style_fields( 'industries' )
			)
		),

		/* =====================================================  BENTO  ===== */
		amber_layout(
			'bento',
			__( 'Why Choose (Bento)', 'amber' ),
			array_merge(
				amber_section_header_fields( 'bento' ),
				array(
					amber_f_repeater(
						'bento_items',
						__( 'Cards', 'amber' ),
						array(
							amber_f_icon( 'icon', __( 'Icon', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
							amber_f_text( 'title', __( 'Title', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
							amber_f_truefalse( 'feature', __( 'Feature tile (large)', 'amber' ), array( 'wrapper' => array( 'width' => '20' ), 'default_value' => 0 ) ),
							amber_f_textarea( 'text', __( 'Text', 'amber' ), array( 'rows' => 2 ) ),
						),
						array( 'button_label' => __( 'Add Card', 'amber' ) )
					),
				),
				amber_section_style_fields( 'bento' )
			)
		),

		/* =================================================  LIVE DEMO  ===== */
		amber_layout(
			'live_demo',
			__( 'Live Product Experience', 'amber' ),
			array_merge(
				amber_section_header_fields( 'live' ),
				array(
					amber_f_repeater(
						'live_items',
						__( 'Products', 'amber' ),
						array(
							amber_f_text( 'name', __( 'Product Name', 'amber' ), array( 'wrapper' => array( 'width' => '60' ) ) ),
							amber_f_color( 'accent', __( 'Accent Colour', 'amber' ), array( 'wrapper' => array( 'width' => '40' ), 'default_value' => '#0ea5e9' ) ),
							amber_f_textarea( 'kpis', __( 'KPIs', 'amber' ), array( 'rows' => 3, 'instructions' => __( 'One per line as “Label | Value”.', 'amber' ) ) ),
						),
						array( 'button_label' => __( 'Add Product', 'amber' ) )
					),
				),
				amber_section_style_fields( 'live' )
			)
		),

		/* ===============================================  TRUSTED LOGOS  ===== */
		amber_layout(
			'logos',
			__( 'Trusted-By Logo Slider', 'amber' ),
			array(
				amber_f_text( 'logos_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Trusted by fast-growing teams worldwide', 'amber' ) ) ),
				amber_f_repeater(
					'logos_items',
					__( 'Logos', 'amber' ),
					array(
						amber_f_image( 'logo', __( 'Logo', 'amber' ), array( 'wrapper' => array( 'width' => '60' ) ) ),
						amber_f_text( 'name', __( 'Company Name (alt)', 'amber' ), array( 'wrapper' => array( 'width' => '40' ) ) ),
					),
					array( 'button_label' => __( 'Add Logo', 'amber' ) )
				),
			)
		),

		/* ===================================================  FEATURES  ===== */
		amber_layout(
			'features',
			__( 'Features Grid', 'amber' ),
			array_merge(
				amber_section_header_fields( 'features' ),
				array(
					amber_f_repeater(
						'features_items',
						__( 'Features', 'amber' ),
						array(
							amber_f_icon( 'icon', __( 'Icon', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
							amber_f_text( 'title', __( 'Title', 'amber' ), array( 'wrapper' => array( 'width' => '70' ) ) ),
							amber_f_textarea( 'text', __( 'Description', 'amber' ) ),
						),
						array( 'button_label' => __( 'Add Feature', 'amber' ) )
					),
				),
				amber_section_style_fields( 'features' )
			)
		),

		/* ===================================================  SERVICES  ===== */
		amber_layout(
			'services',
			__( 'Services', 'amber' ),
			array_merge(
				amber_section_header_fields( 'services' ),
				array(
					amber_f_select(
						'services_source',
						__( 'Content Source', 'amber' ),
						array(
							'manual' => __( 'Manual list (below)', 'amber' ),
							'cpt'    => __( 'Pull from Services post type', 'amber' ),
						),
						array( 'default_value' => 'manual' )
					),
					amber_f_repeater(
						'services_items',
						__( 'Services', 'amber' ),
						array(
							amber_f_icon( 'icon', __( 'Icon', 'amber' ), array( 'wrapper' => array( 'width' => '25' ) ) ),
							amber_f_text( 'title', __( 'Title', 'amber' ), array( 'wrapper' => array( 'width' => '75' ) ) ),
							amber_f_textarea( 'text', __( 'Description', 'amber' ) ),
							amber_f_link( 'link', __( 'Link', 'amber' ) ),
						),
						array(
							'button_label'      => __( 'Add Service', 'amber' ),
							'conditional_logic' => array( array( array( 'field' => amber_fk( 'services_source' ), 'operator' => '==', 'value' => 'manual' ) ) ),
						)
					),
				),
				amber_section_style_fields( 'services' )
			)
		),

		/* ================================================  WHY CHOOSE US  ===== */
		amber_layout(
			'why_choose',
			__( 'Why Choose Us (split)', 'amber' ),
			array_merge(
				amber_section_header_fields( 'why' ),
				array(
					amber_f_image( 'why_image', __( 'Image', 'amber' ) ),
					amber_f_select( 'why_image_side', __( 'Image Side', 'amber' ), array( 'right' => __( 'Right', 'amber' ), 'left' => __( 'Left', 'amber' ) ), array( 'default_value' => 'right' ) ),
					amber_f_repeater(
						'why_points',
						__( 'Points', 'amber' ),
						array(
							amber_f_text( 'title', __( 'Title', 'amber' ) ),
							amber_f_textarea( 'text', __( 'Text', 'amber' ) ),
						),
						array( 'button_label' => __( 'Add Point', 'amber' ) )
					),
					amber_f_link( 'why_btn', __( 'Button', 'amber' ) ),
				),
				amber_section_style_fields( 'why' )
			)
		),

		/* ==================================================  STATISTICS  ===== */
		amber_layout(
			'stats',
			__( 'Statistics Counter', 'amber' ),
			array_merge(
				amber_section_header_fields( 'stats' ),
				array(
					amber_f_repeater(
						'stats_items',
						__( 'Stats', 'amber' ),
						array(
							amber_f_number( 'value', __( 'Number', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
							amber_f_text( 'prefix', __( 'Prefix', 'amber' ), array( 'wrapper' => array( 'width' => '15' ), 'placeholder' => '$' ) ),
							amber_f_text( 'suffix', __( 'Suffix', 'amber' ), array( 'wrapper' => array( 'width' => '15' ), 'placeholder' => '%, K, M, +' ) ),
							amber_f_text( 'label', __( 'Label', 'amber' ), array( 'wrapper' => array( 'width' => '40' ) ) ),
						),
						array( 'button_label' => __( 'Add Stat', 'amber' ) )
					),
				),
				amber_section_style_fields( 'stats' )
			)
		),

		/* ===============================================  PROCESS TIMELINE  ===== */
		amber_layout(
			'process',
			__( 'Process Timeline', 'amber' ),
			array_merge(
				amber_section_header_fields( 'process' ),
				array(
					amber_f_repeater(
						'process_steps',
						__( 'Steps', 'amber' ),
						array(
							amber_f_text( 'title', __( 'Title', 'amber' ), array( 'wrapper' => array( 'width' => '40' ) ) ),
							amber_f_textarea( 'text', __( 'Description', 'amber' ), array( 'wrapper' => array( 'width' => '60' ) ) ),
						),
						array( 'button_label' => __( 'Add Step', 'amber' ) )
					),
				),
				amber_section_style_fields( 'process' )
			)
		),

		/* ============================================  PRODUCT SHOWCASE  ===== */
		amber_layout(
			'showcase',
			__( 'Product Showcase', 'amber' ),
			array_merge(
				amber_section_header_fields( 'showcase' ),
				array(
					amber_f_repeater(
						'showcase_items',
						__( 'Showcase Rows', 'amber' ),
						array(
							amber_f_text( 'eyebrow', __( 'Eyebrow', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
							amber_f_text( 'title', __( 'Title', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
							amber_f_textarea( 'text', __( 'Text', 'amber' ) ),
							amber_f_image( 'image', __( 'Image', 'amber' ) ),
							amber_f_link( 'link', __( 'Link', 'amber' ) ),
						),
						array( 'button_label' => __( 'Add Showcase Row', 'amber' ) )
					),
				),
				amber_section_style_fields( 'showcase' )
			)
		),

		/* =================================================  TESTIMONIALS  ===== */
		amber_layout(
			'testimonials',
			__( 'Testimonials', 'amber' ),
			array_merge(
				amber_section_header_fields( 'testi' ),
				array(
					amber_f_repeater(
						'testi_items',
						__( 'Testimonials', 'amber' ),
						array(
							amber_f_textarea( 'quote', __( 'Quote', 'amber' ), array( 'rows' => 3 ) ),
							amber_f_image( 'avatar', __( 'Avatar', 'amber' ), array( 'wrapper' => array( 'width' => '34' ) ) ),
							amber_f_text( 'name', __( 'Name', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
							amber_f_text( 'role', __( 'Role / Company', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
							amber_f_number( 'rating', __( 'Rating (1-5)', 'amber' ), array( 'default_value' => 5, 'min' => 1, 'max' => 5 ) ),
						),
						array( 'button_label' => __( 'Add Testimonial', 'amber' ) )
					),
				),
				amber_section_style_fields( 'testi' )
			)
		),

		/* =====================================================  PRICING  ===== */
		amber_layout(
			'pricing',
			__( 'Pricing Plans', 'amber' ),
			array_merge(
				amber_section_header_fields( 'pricing' ),
				array(
					amber_f_repeater(
						'pricing_plans',
						__( 'Plans', 'amber' ),
						array(
							amber_f_text( 'name', __( 'Plan Name', 'amber' ), array( 'wrapper' => array( 'width' => '40' ) ) ),
							amber_f_text( 'price', __( 'Price', 'amber' ), array( 'wrapper' => array( 'width' => '30' ), 'placeholder' => '$29' ) ),
							amber_f_text( 'period', __( 'Period', 'amber' ), array( 'wrapper' => array( 'width' => '30' ), 'placeholder' => '/mo' ) ),
							amber_f_textarea( 'tagline', __( 'Tagline', 'amber' ), array( 'rows' => 2 ) ),
							amber_f_textarea( 'features', __( 'Features (one per line)', 'amber' ), array( 'rows' => 5, 'instructions' => __( 'Each line becomes a checklist item.', 'amber' ) ) ),
							amber_f_link( 'button', __( 'Button', 'amber' ) ),
							amber_f_truefalse( 'featured', __( 'Highlight as “Most popular”', 'amber' ), array( 'default_value' => 0 ) ),
						),
						array( 'button_label' => __( 'Add Plan', 'amber' ) )
					),
				),
				amber_section_style_fields( 'pricing' )
			)
		),

		/* =========================================================  FAQ  ===== */
		amber_layout(
			'faq',
			__( 'FAQ Accordion', 'amber' ),
			array_merge(
				amber_section_header_fields( 'faq' ),
				array(
					amber_f_repeater(
						'faq_items',
						__( 'Questions', 'amber' ),
						array(
							amber_f_text( 'question', __( 'Question', 'amber' ) ),
							amber_f_wysiwyg( 'answer', __( 'Answer', 'amber' ), array( 'tabs' => 'visual', 'toolbar' => 'basic' ) ),
						),
						array( 'button_label' => __( 'Add Question', 'amber' ) )
					),
				),
				amber_section_style_fields( 'faq' )
			)
		),

		/* ========================================================  TEAM  ===== */
		amber_layout(
			'team',
			__( 'Team Members', 'amber' ),
			array_merge(
				amber_section_header_fields( 'team' ),
				array(
					amber_f_repeater(
						'team_items',
						__( 'Members', 'amber' ),
						array(
							amber_f_image( 'photo', __( 'Photo', 'amber' ), array( 'wrapper' => array( 'width' => '34' ) ) ),
							amber_f_text( 'name', __( 'Name', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
							amber_f_text( 'role', __( 'Role', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
							amber_f( 'url', 'linkedin', __( 'LinkedIn URL', 'amber' ) ),
						),
						array( 'button_label' => __( 'Add Member', 'amber' ) )
					),
				),
				amber_section_style_fields( 'team' )
			)
		),

		/* ===============================================  BLOG PREVIEW  ===== */
		amber_layout(
			'blog',
			__( 'Blog Preview', 'amber' ),
			array_merge(
				amber_section_header_fields( 'blog' ),
				array(
					amber_f_number( 'blog_count', __( 'Number of posts', 'amber' ), array( 'default_value' => 3, 'min' => 1, 'max' => 6, 'wrapper' => array( 'width' => '50' ) ) ),
					amber_f_link( 'blog_btn', __( 'View-all Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				),
				amber_section_style_fields( 'blog' )
			)
		),

		/* =====================================================  CTA  ===== */
		amber_layout(
			'cta',
			__( 'CTA Banner', 'amber' ),
			array(
				amber_f_text( 'cta_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Ready to get started?', 'amber' ) ) ),
				amber_f_textarea( 'cta_text', __( 'Text', 'amber' ), array( 'default_value' => __( 'Join thousands of teams building better products with Amber.', 'amber' ) ) ),
				amber_f_link( 'cta_primary', __( 'Primary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_link( 'cta_secondary', __( 'Secondary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_select( 'cta_style', __( 'Style', 'amber' ), array( 'gradient' => __( 'Brand Gradient', 'amber' ), 'ink' => __( 'Dark', 'amber' ), 'surface' => __( 'Light', 'amber' ) ), array( 'default_value' => 'gradient' ) ),
			)
		),

		/* =================================================  CONTACT  ===== */
		amber_layout(
			'contact',
			__( 'Contact Section', 'amber' ),
			array_merge(
				amber_section_header_fields( 'contact' ),
				array(
					amber_f_textarea( 'contact_shortcode', __( 'Form Shortcode', 'amber' ), array( 'instructions' => __( 'Paste a CF7/WPForms shortcode, or leave blank for the built-in form.', 'amber' ), 'rows' => 2 ) ),
					amber_f_truefalse( 'contact_show_details', __( 'Show contact details (from Theme Settings)', 'amber' ), array( 'default_value' => 1 ) ),
					amber_f_text( 'contact_map_embed', __( 'Google Map Embed URL', 'amber' ), array( 'instructions' => __( 'The src URL from a Google Maps “Embed a map” iframe.', 'amber' ) ) ),
				),
				amber_section_style_fields( 'contact' )
			)
		),
	);
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_builder',
		'title'           => __( 'Page Sections', 'amber' ),
		'fields'          => array(
			array(
				'key'          => 'field_amber_sections',
				'label'        => __( 'Sections', 'amber' ),
				'name'         => 'sections',
				'type'         => 'flexible_content',
				'instructions' => __( 'Build the page by stacking reusable sections. Drag to reorder.', 'amber' ),
				'button_label' => __( 'Add Section', 'amber' ),
				'layouts'      => amber_builder_layouts(),
			),
		),
		'location'        => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Reusable section builder available on the front page and all pages.', 'amber' ),
	)
);
