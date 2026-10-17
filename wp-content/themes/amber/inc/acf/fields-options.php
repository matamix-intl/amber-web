<?php
/**
 * ACF field group: global Theme Settings (Options page).
 *
 * Branding, header, footer, contact and social — everything global that
 * appears on every page. Tabs keep the admin UI tidy. Each tab maps to one of
 * the options sub-pages registered in acf-init.php.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'                   => 'group_amber_options',
		'title'                 => __( 'Theme Settings', 'amber' ),
		'fields'                => array(

			/* ---------------------------------------------------------- General */
			amber_f( 'tab', 'tab_general', __( 'Branding', 'amber' ) ),
			amber_f_image( 'logo', __( 'Logo', 'amber' ), array( 'instructions' => __( 'Primary logo (SVG/PNG). Falls back to the site title.', 'amber' ) ) ),
			amber_f_image( 'logo_light', __( 'Logo — Light (for dark backgrounds)', 'amber' ) ),
			amber_f_image( 'favicon', __( 'Favicon', 'amber' ), array( 'instructions' => __( '512×512 PNG recommended.', 'amber' ) ) ),
			amber_f_image( 'default_social_image', __( 'Default Social Share Image', 'amber' ), array( 'instructions' => __( '1200×630 for Open Graph / Twitter cards.', 'amber' ) ) ),
			amber_f_color( 'brand_color', __( 'Brand Accent Color', 'amber' ), array( 'default_value' => '#0ea5e9', 'instructions' => __( 'Overrides the default brand colour site-wide.', 'amber' ) ) ),

			/* ----------------------------------------------------------- Header */
			amber_f( 'tab', 'tab_header', __( 'Header', 'amber' ) ),
			amber_f_truefalse( 'announcement_enabled', __( 'Show Announcement Bar', 'amber' ), array( 'default_value' => 0 ) ),
			amber_f_text( 'announcement_text', __( 'Announcement Text', 'amber' ), array( 'conditional_logic' => array( array( array( 'field' => amber_fk( 'announcement_enabled' ), 'operator' => '==', 'value' => '1' ) ) ) ) ),
			amber_f_link( 'announcement_link', __( 'Announcement Link', 'amber' ), array( 'conditional_logic' => array( array( array( 'field' => amber_fk( 'announcement_enabled' ), 'operator' => '==', 'value' => '1' ) ) ) ) ),
			amber_f_link( 'header_cta', __( 'Header Button (CTA)', 'amber' ), array( 'instructions' => __( 'The primary button in the header.', 'amber' ) ) ),
			amber_f_link( 'header_cta_secondary', __( 'Header Secondary Link', 'amber' ), array( 'instructions' => __( 'Optional, e.g. "Sign in".', 'amber' ) ) ),

			/* ----------------------------------------------------------- Footer */
			amber_f( 'tab', 'tab_footer', __( 'Footer', 'amber' ) ),
			amber_f_image( 'footer_logo', __( 'Footer Logo', 'amber' ) ),
			amber_f_textarea( 'footer_description', __( 'Footer Description', 'amber' ), array( 'default_value' => __( 'Empowering businesses with smart software solutions for a smarter tomorrow.', 'amber' ) ) ),
			amber_f_text( 'footer_services_heading', __( 'Products Column Heading', 'amber' ), array( 'default_value' => __( 'Products', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'footer_quick_heading', __( 'Quick Links Heading', 'amber' ), array( 'default_value' => __( 'Company', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_truefalse( 'newsletter_enabled', __( 'Show Newsletter Signup', 'amber' ), array( 'default_value' => 1 ) ),
			amber_f_text( 'newsletter_heading', __( 'Newsletter Heading', 'amber' ), array( 'default_value' => __( 'Stay in the loop', 'amber' ) ) ),
			amber_f_text( 'newsletter_text', __( 'Newsletter Subtext', 'amber' ), array( 'default_value' => __( 'Product updates, no spam. Unsubscribe anytime.', 'amber' ) ) ),
			amber_f_text( 'copyright', __( 'Copyright Text', 'amber' ), array( 'instructions' => __( 'Use %year% for the current year.', 'amber' ), 'default_value' => __( '© %year% Amber Technologies. All rights reserved.', 'amber' ) ) ),

			/* -------------------------------------------------- Contact & Social */
			amber_f( 'tab', 'tab_contact', __( 'Contact & Social', 'amber' ) ),
			amber_f_text( 'contact_phone', __( 'Phone', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_email', __( 'Email', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'contact_address', __( 'Address', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_hours', __( 'Business Hours', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_whatsapp', __( 'WhatsApp Number', 'amber' ), array( 'instructions' => __( 'Digits only, with country code, e.g. 919544931055.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_repeater(
				'social_links',
				__( 'Social Profiles', 'amber' ),
				array(
					amber_f_select(
						'network',
						__( 'Network', 'amber' ),
						array(
							'twitter'   => 'X / Twitter',
							'linkedin'  => 'LinkedIn',
							'facebook'  => 'Facebook',
							'instagram' => 'Instagram',
							'youtube'   => 'YouTube',
							'github'    => 'GitHub',
						),
						array( 'wrapper' => array( 'width' => '40' ) )
					),
					amber_f( 'url', 'url', __( 'Profile URL', 'amber' ), array( 'wrapper' => array( 'width' => '60' ) ) ),
				),
				array( 'button_label' => __( 'Add Social Profile', 'amber' ) )
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'amber-settings',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'active'                => true,
		'description'           => __( 'Global, site-wide settings for Amber.', 'amber' ),
		'show_in_rest'          => 0,
	)
);
