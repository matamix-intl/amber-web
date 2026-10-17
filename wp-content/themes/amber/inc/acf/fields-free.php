<?php
/**
 * ACF *Free*-compatible field groups.
 *
 * ACF Free has no Flexible Content, Repeater or Options page. So instead of the
 * Pro "Section Builder", we expose the editable content as flat, top-level
 * fields organised with Tab fields (all supported in Free):
 *
 *   - "Amber — Page Content"  → on every Page. Section headings, hero copy &
 *     buttons, the reveal media, the CTA. Read by amber_sub() from the page.
 *   - "Amber — Site Settings" → on the static front page only. Header, branding,
 *     footer, contact & social. Read by amber_option() from the front page.
 *
 * Repeating lists (products, industries, bento cards, testimonials, FAQ) are
 * exposed as fixed numbered slots (Product 1..4, Industry 1..8, etc.) instead
 * of a true repeater, mirroring the numbered-field convention already used by
 * the Amber ERP Software page groups below. Stats counters use the same approach.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon choices for the About page value/KPI selects.
 *
 * Keys map 1:1 to amber_icon_svg() so whatever an admin picks renders. Kept to
 * the icons that read well at card/KPI size.
 *
 * @return array<string,string> value => label
 */
function amber_about_icon_choices() {
	return array(
		'rocket'    => __( 'Rocket', 'amber' ),
		'globe'     => __( 'Globe', 'amber' ),
		'support'   => __( 'Support', 'amber' ),
		'shield'    => __( 'Shield', 'amber' ),
		'bolt'      => __( 'Bolt', 'amber' ),
		'chart'     => __( 'Chart', 'amber' ),
		'briefcase' => __( 'Briefcase', 'amber' ),
		'clock'     => __( 'Clock', 'amber' ),
		'layers'    => __( 'Layers', 'amber' ),
		'cloud'     => __( 'Cloud', 'amber' ),
		'star'      => __( 'Star', 'amber' ),
		'check'     => __( 'Check', 'amber' ),
	);
}

/* =========================================================================
 * Numbered-slot field builders for the homepage repeating sections (ACF Free
 * has no Repeater, so each list item gets its own fixed field set, keyed off
 * the shared amber_home_*_defaults() so admin placeholders match the demo
 * output — same convention as the Amber ERP Software groups further down this file).
 * ====================================================================== */

$amber_products_fields = array();
foreach ( amber_home_products_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: product number. */
	$amber_products_fields[] = amber_f( 'tab', "tab_free_product{$amber_i}", sprintf( __( 'Product %d', 'amber' ), $amber_i ) );
	$amber_products_fields[] = amber_f_text( "products{$amber_i}_name", __( 'Name', 'amber' ), array( 'default_value' => $amber_d['name'], 'wrapper' => array( 'width' => '40' ) ) );
	$amber_products_fields[] = amber_f_icon( "products{$amber_i}_icon", __( 'Icon', 'amber' ), array( 'default_value' => $amber_d['icon'], 'wrapper' => array( 'width' => '30' ) ) );
	$amber_products_fields[] = amber_f_color( "products{$amber_i}_accent", __( 'Accent colour', 'amber' ), array( 'default_value' => $amber_d['accent'], 'wrapper' => array( 'width' => '30' ) ) );
	$amber_products_fields[] = amber_f_image( "products{$amber_i}_image", __( 'Image', 'amber' ), array( 'instructions' => __( 'Leave empty for the branded icon placeholder.', 'amber' ) ) );
	$amber_products_fields[] = amber_f_textarea( "products{$amber_i}_desc", __( 'Description', 'amber' ), array( 'rows' => 3, 'default_value' => $amber_d['desc'] ) );
	$amber_products_fields[] = amber_f_textarea( "products{$amber_i}_desc2", __( 'Description (second paragraph)', 'amber' ), array( 'rows' => 3, 'default_value' => $amber_d['desc2'] ) );
	$amber_products_fields[] = amber_f_text( "products{$amber_i}_link_label", __( 'Button label', 'amber' ), array( 'default_value' => $amber_d['link']['title'], 'wrapper' => array( 'width' => '50' ) ) );
	$amber_products_fields[] = amber_f_text( "products{$amber_i}_link_url", __( 'Button URL', 'amber' ), array( 'default_value' => $amber_d['link']['url'], 'wrapper' => array( 'width' => '50' ) ) );
}

$amber_industries_fields = array();
foreach ( amber_home_industries_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: industry number. */
	$amber_industries_fields[] = amber_f_image( "industries{$amber_i}_img", sprintf( __( 'Industry %d — icon (PNG)', 'amber' ), $amber_i ), array( 'instructions' => __( 'Square line-art PNG on a transparent background (96×96 or larger). Only the shape is used — the theme paints it white, and accent-coloured on hover.', 'amber' ), 'mime_types' => 'png,svg', 'wrapper' => array( 'width' => '20' ) ) );
	$amber_industries_fields[] = amber_f_text( "industries{$amber_i}_name", sprintf( __( 'Industry %d — name', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['name'], 'wrapper' => array( 'width' => '30' ) ) );
	$amber_industries_fields[] = amber_f_text( "industries{$amber_i}_desc", sprintf( __( 'Industry %d — description', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['desc'], 'instructions' => __( 'Kept for reference — the mosaic layout shows the icon and name only.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
}

$amber_bento_fields = array();
foreach ( amber_home_bento_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: bento card number. */
	$amber_bento_fields[] = amber_f_select( "bento{$amber_i}_icon", sprintf( __( 'Card %d — icon', 'amber' ), $amber_i ), amber_erp_icon_choices(), array( 'default_value' => $amber_d['icon'], 'wrapper' => array( 'width' => '20' ) ) );
	$amber_bento_fields[] = amber_f_text( "bento{$amber_i}_title", sprintf( __( 'Card %d — title', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['title'], 'wrapper' => array( 'width' => '40' ) ) );
	$amber_bento_fields[] = amber_f_truefalse( "bento{$amber_i}_feature", sprintf( __( 'Card %d — large feature tile', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['feature'] ? 1 : 0, 'wrapper' => array( 'width' => '15' ) ) );
	$amber_bento_fields[] = amber_f_textarea( "bento{$amber_i}_text", sprintf( __( 'Card %d — text', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d['text'], 'wrapper' => array( 'width' => '25' ) ) );
}

$amber_testi_fields = array();
foreach ( amber_home_testimonials_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: testimonial number. */
	$amber_testi_fields[] = amber_f( 'tab', "tab_free_testi{$amber_i}", sprintf( __( 'Testimonial %d', 'amber' ), $amber_i ) );
	$amber_testi_fields[] = amber_f_textarea( "testi{$amber_i}_quote", __( 'Quote', 'amber' ), array( 'rows' => 3, 'default_value' => $amber_d['quote'] ) );
	$amber_testi_fields[] = amber_f_image( "testi{$amber_i}_avatar", __( 'Avatar photo', 'amber' ), array( 'instructions' => __( 'Leave empty to show the initial letter of the name.', 'amber' ), 'wrapper' => array( 'width' => '34' ) ) );
	$amber_testi_fields[] = amber_f_text( "testi{$amber_i}_name", __( 'Name', 'amber' ), array( 'default_value' => $amber_d['name'], 'wrapper' => array( 'width' => '33' ) ) );
	$amber_testi_fields[] = amber_f_text( "testi{$amber_i}_role", __( 'Role / Company', 'amber' ), array( 'default_value' => $amber_d['role'], 'wrapper' => array( 'width' => '33' ) ) );
}

$amber_home_faq_fields = array();
foreach ( amber_home_faq_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: FAQ number. */
	$amber_home_faq_fields[] = amber_f_text( "home_faq{$amber_i}_q", sprintf( __( 'Q%d — question', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['q'], 'wrapper' => array( 'width' => '35' ) ) );
	$amber_home_faq_fields[] = amber_f_textarea( "home_faq{$amber_i}_a", sprintf( __( 'Q%d — answer', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d['a'], 'wrapper' => array( 'width' => '65' ) ) );
}

// "How To Choose" checklist section — shared by the homepage's "Amber — Page
// Content" group and the POS-only "Amber — ERP Page" group (section-choose.php
// is rendered on both: right after the hero in the homepage demo composition,
// and on the POS product page).
$amber_choose_fields = array(
	amber_f( 'tab', 'tab_choose', __( 'How To Choose (Checklist)', 'amber' ) ),
	amber_f( 'message', 'choose_msg', '', array( 'message' => __( 'The checklist section shown right after the hero: heading, intro, a checklist and a button on the left, an image on the right. Leave a field blank to use the built-in demo copy.', 'amber' ) ) ),
	amber_f_textarea( 'choose_heading', __( 'Heading', 'amber' ), array( 'rows' => 3, 'default_value' => "How to choose the right POS\nsolution for restaurants & retail\nstores?", 'instructions' => __( 'A line break starts a new line in the heading.', 'amber' ) ) ),
	amber_f_textarea( 'choose_intro', __( 'Intro paragraph', 'amber' ), array( 'rows' => 4, 'default_value' => 'Before choosing a POS system, understand that POS software contributes to your business in every area far beyond managing bills and transactions. Look out for these features and stay ahead of the competition to be the best in your business:' ) ),
	amber_f_image( 'choose_image', __( 'Image', 'amber' ), array( 'instructions' => __( 'Shown on the right of the checklist. Leave empty to use the bundled placeholder.', 'amber' ) ) ),
	amber_f_link( 'choose_btn', __( 'Button', 'amber' ) ),
);
foreach ( amber_choose_points_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: checklist point number. */
	$amber_choose_fields[] = amber_f_textarea( "choose_point{$amber_i}", sprintf( __( 'Checklist point %d', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d ) );
}

// "WhatsApp Integration" checklist section — chat mockup left, checklist right.
// Sits between "Products" and "Industries" in the homepage demo composition.
$amber_whatsapp_fields = array(
	amber_f( 'tab', 'tab_whatsapp', __( 'WhatsApp Integration', 'amber' ) ),
	amber_f( 'message', 'whatsapp_msg', '', array( 'message' => __( 'The WhatsApp Business pitch section: a chat mockup on the left, heading/intro/checklist/button on the right. Leave a field blank to use the built-in demo copy.', 'amber' ) ) ),
	amber_f_text( 'whatsapp_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Bring Amber ERP Software straight into your customers’ WhatsApp' ) ),
	amber_f_textarea( 'whatsapp_intro', __( 'Intro paragraph', 'amber' ), array( 'rows' => 3, 'default_value' => 'Connect Amber ERP Software with WhatsApp Business and turn every order, reminder and support chat into a conversation your customers already check ten times a day.' ) ),
	amber_f_link( 'whatsapp_btn', __( 'Button', 'amber' ) ),
);
foreach ( amber_whatsapp_points_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: checklist point number. */
	$amber_whatsapp_fields[] = amber_f_textarea( "whatsapp_point{$amber_i}", sprintf( __( 'Checklist point %d', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d ) );
}

/* =========================================================================
 * Group A — Page Content (per page)
 * ====================================================================== */
acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_content',
		'title'           => __( 'Amber — Page Content', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Editable section content for this page (ACF Free).', 'amber' ),
		'location'        => array(
			array(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
				// Pages with their own dedicated field groups get only those.
				array( 'param' => 'page_template', 'operator' => '!=', 'value' => 'page-templates/page-amber-erp.php' ),
				array( 'param' => 'page_template', 'operator' => '!=', 'value' => 'page-templates/page-zoomie.php' ),
				array( 'param' => 'page_template', 'operator' => '!=', 'value' => 'page-templates/page-gozzbe.php' ),
				array( 'param' => 'page_template', 'operator' => '!=', 'value' => 'page-templates/page-pos.php' ),
				array( 'param' => 'page_template', 'operator' => '!=', 'value' => 'page-templates/page-contact.php' ),
			),
		),
		'fields'          => array_merge(
			array(

				/* ---- Hero (aurora banner) ---- */
				amber_f( 'tab', 'tab_free_hero', __( 'Hero', 'amber' ) ),
				amber_f( 'message', 'hero_msg', '', array( 'message' => __( 'The hero is the centered aurora banner: large heading, description and two pill buttons over a white stage with teal/cyan edge gradients. Leave a field blank to use the built-in demo content. The three product-preview cards along the fold are fixed brand content.', 'amber' ) ) ),

				amber_f_text( 'hero_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Helping Businesses Grow Through Smart ERP, POS & Business Management Solutions' ) ),
				amber_f_textarea( 'hero_subheading', __( 'Description', 'amber' ), array( 'default_value' => 'From ERP implementation to digital growth strategies, we help businesses automate operations, improve visibility, and scale with confidence.' ) ),
				amber_f_link( 'hero_primary_btn', __( 'Primary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_link( 'hero_secondary_btn', __( 'Secondary Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			),
			$amber_choose_fields,
			array(

				/* ---- Reveal ---- */
				amber_f( 'tab', 'tab_free_reveal', __( 'Reveal Video', 'amber' ) ),
				amber_f_text( 'reveal_title', __( 'Giant Wordmark', 'amber' ), array( 'default_value' => 'AMBER TECHNOLOGIES' ) ),
				amber_f_select( 'reveal_media_type', __( 'Media', 'amber' ), array( 'video' => __( 'Video (Vimeo / YouTube / MP4)', 'amber' ), 'image' => __( 'Image', 'amber' ) ), array( 'default_value' => 'video', 'wrapper' => array( 'width' => '40' ) ) ),
				amber_f( 'url', 'reveal_video_url', __( 'Video URL', 'amber' ), array( 'instructions' => __( 'Vimeo, YouTube or a direct .mp4 link.', 'amber' ), 'wrapper' => array( 'width' => '60' ), 'conditional_logic' => array( array( array( 'field' => amber_fk( 'reveal_media_type' ), 'operator' => '==', 'value' => 'video' ) ) ) ) ),
				amber_f_image( 'reveal_image', __( 'Image', 'amber' ), array( 'conditional_logic' => array( array( array( 'field' => amber_fk( 'reveal_media_type' ), 'operator' => '==', 'value' => 'image' ) ) ) ) ),

				/* ---- Stats counters ---- */
				amber_f( 'tab', 'tab_free_counters', __( 'Stats Counters', 'amber' ) ),
				amber_f_text( 'counters_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Powering Businesses Across' ) ),
				amber_f_text( 'counters_heading_highlight', __( 'Heading — highlighted words', 'amber' ), array( 'default_value' => 'Over 50 Cities', 'instructions' => __( 'Appended after the heading, rendered in the brand colour.', 'amber' ) ) ),
				amber_f_text( 'counter1_num', __( 'Stat 1 — number', 'amber' ), array( 'default_value' => '5,000+', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter1_label', __( 'Stat 1 — label', 'amber' ), array( 'default_value' => 'Businesses', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter2_num', __( 'Stat 2 — number', 'amber' ), array( 'default_value' => '200,000+', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter2_label', __( 'Stat 2 — label', 'amber' ), array( 'default_value' => 'Daily Transactions', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter3_num', __( 'Stat 3 — number', 'amber' ), array( 'default_value' => '50+', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter3_label', __( 'Stat 3 — label', 'amber' ), array( 'default_value' => 'Cities Served', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter4_num', __( 'Stat 4 — number', 'amber' ), array( 'default_value' => '99.9%', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'counter4_label', __( 'Stat 4 — label', 'amber' ), array( 'default_value' => 'Reliability', 'wrapper' => array( 'width' => '25' ) ) ),

				/* ---- Text reveal ---- */
				amber_f( 'tab', 'tab_free_textreveal', __( 'Text Reveal', 'amber' ) ),
				amber_f_textarea( 'textreveal_heading', __( 'Statement', 'amber' ), array( 'default_value' => 'Smart software for enterprise, retail, restaurants and laundry — one connected ecosystem you can trust.', 'instructions' => __( 'Fills in word-by-word as the visitor scrolls.', 'amber' ) ) ),
				amber_f_link( 'textreveal_button', __( 'Button', 'amber' ) ),

				/* ---- Products ---- */
				amber_f( 'tab', 'tab_free_products', __( 'Products', 'amber' ) ),
				amber_f_text( 'products_heading', __( 'Section — heading', 'amber' ), array( 'default_value' => 'Powerful Solutions. Seamless Experience.', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'products_eyebrow', __( 'Section — eyebrow', 'amber' ), array( 'default_value' => 'Our Products', 'wrapper' => array( 'width' => '30' ) ) ),
			),
			$amber_products_fields,
			$amber_whatsapp_fields,
			array(

				/* ---- Industries ---- */
				amber_f( 'tab', 'tab_free_industries', __( 'Industries', 'amber' ) ),
				amber_f_text( 'industries_heading', __( 'Section — heading', 'amber' ), array( 'default_value' => 'Solutions for Every Industry', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'industries_eyebrow', __( 'Section — eyebrow', 'amber' ), array( 'default_value' => 'Industries We Serve', 'wrapper' => array( 'width' => '30' ) ) ),
			),
			$amber_industries_fields,
			array(

				/* ---- Why Choose (Bento) ---- */
				amber_f( 'tab', 'tab_free_bento', __( 'Why Choose', 'amber' ) ),
				amber_f_text( 'bento_heading', __( 'Section — heading', 'amber' ), array( 'default_value' => 'Technology That Works for You', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'bento_eyebrow', __( 'Section — eyebrow', 'amber' ), array( 'default_value' => 'Why Choose Amber', 'wrapper' => array( 'width' => '30' ) ) ),
			),
			$amber_bento_fields,
			array(

				/* ---- Stats counters heading (counters live in their own tab above) ---- */
				amber_f( 'tab', 'tab_free_stats_heading', __( 'Stats Heading', 'amber' ) ),
				amber_f_text( 'stats_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Numbers teams trust', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'stats_eyebrow', __( 'Eyebrow', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),

				/* ---- Testimonials ---- */
				amber_f( 'tab', 'tab_free_testi', __( 'Testimonials', 'amber' ) ),
				amber_f_text( 'testi_heading', __( 'Section — heading', 'amber' ), array( 'default_value' => 'Loved by teams everywhere', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'testi_eyebrow', __( 'Section — eyebrow', 'amber' ), array( 'default_value' => 'Testimonials', 'wrapper' => array( 'width' => '30' ) ) ),
			),
			$amber_testi_fields,
			array(

				/* ---- FAQ ---- */
				amber_f( 'tab', 'tab_free_home_faq', __( 'FAQ', 'amber' ) ),
				amber_f_text( 'faq_heading', __( 'Section — heading', 'amber' ), array( 'default_value' => 'Got Questions? We’ve Got Answers', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'faq_eyebrow', __( 'Section — eyebrow', 'amber' ), array( 'default_value' => 'FAQ', 'wrapper' => array( 'width' => '30' ) ) ),
			),
			$amber_home_faq_fields,
			array(

				/* ---- CTA ---- */
				amber_f( 'tab', 'tab_free_cta', __( 'CTA Banner', 'amber' ) ),
				amber_f_text( 'cta_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Ready to Transform Your Business?' ) ),
				amber_f_textarea( 'cta_text', __( 'Text', 'amber' ) ),
				amber_f_link( 'cta_primary', __( 'Button', 'amber' ) ),
			)
		),
	)
);

/* =========================================================================
 * Group C0 — Contact Page (only when the "Contact Us" template is selected)
 *
 * The Contact template renders just two sections — the "Send us a message"
 * panel (template-parts/sections/section-contact.php) and the reseller band
 * (section-locations.php) — so it used to sit under the generic "Page Content"
 * group and show the homepage's Hero / Products / Testimonials / FAQ tabs,
 * none of which it renders. These are the fields it actually reads, and the
 * generic group is excluded above.
 * ====================================================================== */
/*
 * Per-reseller card fields (loc{n}_*). ACF Free has no repeater, so each
 * reseller is a tab of flat fields; defaults come from amber_reseller_defaults()
 * so the admin shows the copy the page is actually rendering. Clearing a slot's
 * Country removes that card from the page.
 */
$amber_reseller_fields = array();
foreach ( amber_reseller_defaults() as $amber_n => $amber_r ) {
	/* translators: %d: reseller slot number. */
	$amber_reseller_fields[] = amber_f( 'tab', "tab_contact_loc{$amber_n}", sprintf( __( 'Reseller %d', 'amber' ), $amber_n ) );

	if ( '' === $amber_r['city'] ) {
		$amber_reseller_fields[] = amber_f( 'message', "loc{$amber_n}_msg", '', array( 'message' => __( 'Spare slot — fill in a Country to add another reseller card to the page.', 'amber' ) ) );
	} else {
		$amber_reseller_fields[] = amber_f( 'message', "loc{$amber_n}_msg", '', array( 'message' => __( 'Clear the Country to remove this reseller from the page.', 'amber' ) ) );
	}

	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_city", __( 'Country', 'amber' ), array( 'default_value' => $amber_r['city'], 'wrapper' => array( 'width' => '25' ) ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_flag", __( 'Flag emoji', 'amber' ), array( 'default_value' => $amber_r['flag'], 'instructions' => __( 'e.g. 🇧🇭 — leave empty for none.', 'amber' ), 'wrapper' => array( 'width' => '15' ) ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_company", __( 'Company', 'amber' ), array( 'default_value' => $amber_r['company'], 'wrapper' => array( 'width' => '60' ) ) );
	$amber_reseller_fields[] = amber_f_textarea( "loc{$amber_n}_address", __( 'Address', 'amber' ), array( 'rows' => 2, 'default_value' => $amber_r['address'] ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_phone1", __( 'Phone 1', 'amber' ), array( 'default_value' => $amber_r['phone1'], 'wrapper' => array( 'width' => '33' ) ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_phone2", __( 'Phone 2', 'amber' ), array( 'default_value' => $amber_r['phone2'], 'wrapper' => array( 'width' => '33' ) ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_phone3", __( 'Phone 3', 'amber' ), array( 'default_value' => $amber_r['phone3'], 'wrapper' => array( 'width' => '34' ) ) );
	$amber_reseller_fields[] = amber_f_text( "loc{$amber_n}_email", __( 'Email', 'amber' ), array( 'default_value' => $amber_r['email'], 'wrapper' => array( 'width' => '50' ) ) );
	$amber_reseller_fields[] = amber_f( 'url', "loc{$amber_n}_website", __( 'Website', 'amber' ), array( 'default_value' => $amber_r['website'], 'instructions' => __( 'Full URL. Leave empty to hide the row.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
}

/*
 * Page-level overrides for the contact lines. Deliberately no default_value:
 * blank means "use the site-wide value from Site Settings", which is also what
 * the contact form's notification email uses. The instructions name the value
 * currently in force, read straight from postmeta rather than through
 * amber_option() — ACF is mid-registration here and must not be re-entered.
 */
$amber_front_id     = (int) get_option( 'page_on_front' );
$amber_global_hint  = static function ( $field ) use ( $amber_front_id ) {
	$value = $amber_front_id ? (string) get_post_meta( $amber_front_id, $field, true ) : '';
	$value = trim( preg_replace( '/\s+/', ' ', $value ) );

	if ( '' === $value ) {
		return __( 'Leave empty to hide this line. Set it site-wide on the Home page under Site Settings → Contact &amp; Social.', 'amber' );
	}

	return sprintf(
		/* translators: %s: the value currently set site-wide. */
		__( 'Leave empty to use the site-wide value (%s), set on the Home page under Site Settings → Contact &amp; Social.', 'amber' ),
		'<strong>' . esc_html( $value ) . '</strong>'
	);
};

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_contact',
		'title'           => __( 'Amber — Contact Page', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Editable content for the Contact Us page (ACF Free). Leave a field blank to use the built-in copy.', 'amber' ),
		'location'        => array(
			array(
				array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-contact.php' ),
			),
		),
		'fields'          => array_merge( array(

			/* ---- Section header ---- */
			amber_f( 'tab', 'tab_contact_intro', __( 'Intro', 'amber' ) ),
			amber_f( 'message', 'contact_intro_msg', '', array( 'message' => __( 'The heading block above the form. Phone, email and address themselves are global — edit those on the Home page under <strong>Site Settings → Contact &amp; Social</strong>, so the footer and every other page stay in step.', 'amber' ) ) ),
			amber_f_text( 'contact_eyebrow', __( 'Eyebrow', 'amber' ), array( 'default_value' => 'Contact', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'contact_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Let’s talk', 'wrapper' => array( 'width' => '70' ) ) ),
			amber_f_textarea( 'contact_subheading', __( 'Subheading', 'amber' ), array( 'rows' => 2, 'default_value' => 'Tell us about your project and we’ll get back within one business day.' ) ),
			amber_f_select( 'contact_align', __( 'Heading alignment', 'amber' ), array( 'center' => __( 'Centered', 'amber' ), 'left' => __( 'Left', 'amber' ) ), array( 'default_value' => 'center', 'wrapper' => array( 'width' => '30' ) ) ),

			/* ---- Form panel ---- */
			amber_f( 'tab', 'tab_contact_form', __( 'Form', 'amber' ) ),
			amber_f( 'message', 'contact_form_msg', '', array( 'message' => __( 'The form itself is the Contact Form 7 form wired into the page template; these fields are the copy shown above it.', 'amber' ) ) ),
			amber_f_text( 'contact_form_heading', __( 'Form heading', 'amber' ), array( 'default_value' => 'Send us a message' ) ),
			amber_f_textarea( 'contact_form_text', __( 'Form intro text', 'amber' ), array( 'rows' => 2, 'default_value' => 'Fill up the form and our team will get back to you within 24 hours.' ) ),

			/* ---- Details panel ---- */
			amber_f( 'tab', 'tab_contact_details', __( 'Contact Details', 'amber' ) ),
			// No "show/hide the panel" toggle: amber_sub() treats a false value
			// as unset and would hand back the default, so a true_false field
			// here could be switched off and nothing would happen.
			amber_f_text( 'contact_details_eyebrow', __( 'Eyebrow', 'amber' ), array( 'default_value' => 'How to reach us', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'contact_details_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Contact details', 'wrapper' => array( 'width' => '70' ) ) ),
			amber_f_textarea( 'contact_details_text', __( 'Intro text', 'amber' ), array( 'rows' => 2, 'default_value' => "We're just a message or call away, ready to provide you with the support and assistance you need." ) ),

			amber_f_text( 'page_contact_email', __( 'Email', 'amber' ), array( 'instructions' => $amber_global_hint( 'contact_email' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'page_contact_phone', __( 'Phone number', 'amber' ), array( 'instructions' => $amber_global_hint( 'contact_phone' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'page_contact_address', __( 'Address', 'amber' ), array( 'rows' => 3, 'instructions' => $amber_global_hint( 'contact_address' ) . ' ' . __( 'Line breaks are kept.', 'amber' ) ) ),
			amber_f_text( 'page_contact_whatsapp', __( 'WhatsApp number', 'amber' ), array( 'instructions' => $amber_global_hint( 'contact_whatsapp' ) . ' ' . __( 'Digits with country code, e.g. 919605000694.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'page_contact_hours', __( 'Working hours', 'amber' ), array( 'instructions' => $amber_global_hint( 'contact_hours' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'contact_map_embed', __( 'Map embed', 'amber' ), array( 'rows' => 3, 'instructions' => __( 'Paste a Google Maps &lt;iframe&gt; embed. Leave empty to hide the map.', 'amber' ) ) ),

			/* ---- Resellers band ---- */
			amber_f( 'tab', 'tab_contact_locations', __( 'Resellers', 'amber' ) ),
			amber_f( 'message', 'locations_msg', '', array( 'message' => __( 'The band of reseller cards over the background video. Each card has its own tab after this one.', 'amber' ) ) ),
			amber_f_text( 'locations_eyebrow', __( 'Eyebrow', 'amber' ), array( 'default_value' => 'Global Presence', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'locations_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Our', 'wrapper' => array( 'width' => '35' ) ) ),
			amber_f_text( 'locations_heading_accent', __( 'Heading — accent word', 'amber' ), array( 'default_value' => 'Locations', 'wrapper' => array( 'width' => '35' ) ) ),
			amber_f_textarea( 'locations_subheading', __( 'Subheading', 'amber' ), array( 'rows' => 2, 'default_value' => 'Serving clients worldwide from our offices across the globe.' ) ),
			amber_f( 'file', 'locations_video', __( 'Background video', 'amber' ), array( 'instructions' => __( 'MP4 shown behind the reseller cards. Keep it small — it loads when the section scrolls into view. Leave empty for the plain dark band.', 'amber' ), 'mime_types' => 'mp4,webm' ) ),
		), $amber_reseller_fields ),
	)
);

/* =========================================================================
 * Group C — About Page (only when the "About Us" template is selected)
 *
 * Every field here is read by template-parts/about/about-page.php through
 * amber_sub(), so the page shows premium defaults until these are filled and
 * live content afterwards. Scoped by page template so it only appears on the
 * About page, keeping the editor tidy.
 * ====================================================================== */
acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_about',
		'title'           => __( 'Amber — About Page', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Editable content for the About Us page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => array(
			array(
				array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-about.php' ),
			),
		),
		'fields'          => array(

			/* ---- Hero ---- */
			amber_f( 'tab', 'tab_about_hero', __( 'Hero', 'amber' ) ),
			amber_f_text( 'about_heading', __( 'Headline', 'amber' ), array( 'default_value' => 'Driving Business Growth Through Technology', 'instructions' => __( 'The main headline. The optional highlighted word below is appended after this.', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
			amber_f_text( 'about_heading_highlight', __( 'Highlighted word', 'amber' ), array( 'default_value' => '', 'instructions' => __( 'Optional — rendered in the blue brand gradient after the headline. Leave blank for a plain white headline.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_textarea( 'about_lead', __( 'Banner lead (under the headline)', 'amber' ), array( 'rows' => 3, 'default_value' => 'A UAE-based technology company delivering ERP, digital marketing, and custom digital solutions for modern businesses', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_image( 'about_hero_image', __( 'Banner background photo', 'amber' ), array( 'instructions' => __( 'Wide photo (about 16:7, min 1920px) shown full-bleed behind the headline under a dark navy overlay. Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
			amber_f_link( 'about_hero_btn', __( 'Banner button', 'amber' ), array( 'instructions' => __( 'Pill CTA on the banner. Defaults to “Get started” → #contact.', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),

			/* ---- About our company (photo collage left, badge + heading + copy + checklist right) ----
			 * Reference: D Max Dental "Creating Healthy Smiles" split. The collage is three
			 * independent photo slots (wide top-left, small top-right, tall bottom-right
			 * portrait) plus a stat badge — not one single image — so each slot gets its
			 * own image field. Field names/defaults mirror template-parts/about/about-page.php
			 * exactly (read through amber_sub()). */
			amber_f( 'tab', 'tab_about_co', __( 'About Company', 'amber' ) ),
			amber_f_text( 'about_co_badge', __( 'Eyebrow (dash-flanked label above the heading)', 'amber' ), array( 'default_value' => 'About Us', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'about_co_heading', __( 'Heading', 'amber' ), array( 'default_value' => "Empowering Business Growth\nThrough Smart Digital Solutions", 'instructions' => __( 'A line break forces the heading onto exactly two lines. Leave as one line to let it split automatically near the midpoint.', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
			amber_f_textarea( 'about_co_body', __( 'Paragraph', 'amber' ), array( 'rows' => 4, 'instructions' => __( 'One short paragraph — keep it brief, the reference layout has room for a single paragraph, not several.', 'amber' ) ) ),

			amber_f( 'message', 'about_co_collage_msg', '', array( 'message' => __( 'Photo collage (left column): three independent photo slots. Leave a slot empty to show the bundled placeholder.', 'amber' ) ) ),
			amber_f_image( 'about_co_image', __( 'Photo 1 — wide, top-left', 'amber' ), array( 'wrapper' => array( 'width' => '34' ) ) ),
			amber_f_image( 'about_co_image_2', __( 'Photo 2 — small, top-right', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),
			amber_f_image( 'about_co_image_3', __( 'Photo 3 — tall portrait, bottom-right', 'amber' ), array( 'wrapper' => array( 'width' => '33' ) ) ),

			amber_f( 'message', 'about_co_stat_msg', '', array( 'message' => __( 'Stat badge — the solid blue card overlapping the collage\'s bottom-left corner.', 'amber' ) ) ),
			amber_f_text( 'about_co_stat_num', __( 'Number', 'amber' ), array( 'default_value' => '3', 'wrapper' => array( 'width' => '20' ) ) ),
			amber_f_text( 'about_co_stat_label', __( 'Label', 'amber' ), array( 'default_value' => 'Years Experience', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'about_co_stat_text', __( 'Supporting line', 'amber' ), array( 'default_value' => 'Building trusted business software since day one.', 'wrapper' => array( 'width' => '50' ) ) ),

			amber_f( 'message', 'about_co_points_msg', '', array( 'message' => __( 'Checklist — up to 4 short points shown below the paragraph. Leave a point blank to remove it.', 'amber' ) ) ),
			amber_f_text( 'about_co_point1', __( 'Point 1', 'amber' ), array( 'default_value' => 'Experienced team who listen before they build', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'about_co_point2', __( 'Point 2', 'amber' ), array( 'default_value' => 'Modern platform that keeps every module in sync', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'about_co_point3', __( 'Point 3', 'amber' ), array( 'default_value' => 'Implementation plans built around your workflow', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'about_co_point4', __( 'Point 4', 'amber' ), array( 'default_value' => 'Long-term support that goes well beyond go-live', 'wrapper' => array( 'width' => '50' ) ) ),

			/* ---- Mission & Vision (alternating badge-image / copy rows) ---- */
			amber_f( 'tab', 'tab_about_mv', __( 'Mission & Vision', 'amber' ) ),
			amber_f_text( 'about_mv_m_title', __( 'Mission — title', 'amber' ), array( 'default_value' => 'Our Mission', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_textarea( 'about_mv_m_text', __( 'Mission — text', 'amber' ), array( 'rows' => 3, 'wrapper' => array( 'width' => '40' ) ) ),
			amber_f_image( 'about_mv_m_image', __( 'Mission — image', 'amber' ), array( 'instructions' => __( 'Round badge graphic (left of the copy). Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_text( 'about_mv_v_title', __( 'Vision — title', 'amber' ), array( 'default_value' => 'Our Vision', 'wrapper' => array( 'width' => '30' ) ) ),
			amber_f_textarea( 'about_mv_v_text', __( 'Vision — text', 'amber' ), array( 'rows' => 3, 'wrapper' => array( 'width' => '40' ) ) ),
			amber_f_image( 'about_mv_v_image', __( 'Vision — image', 'amber' ), array( 'instructions' => __( 'Round badge graphic (right of the copy). Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),

			/* ---- Closing CTA (centered light-blue panel, same component as the homepage) ---- */
			amber_f( 'tab', 'tab_about_cta', __( 'Closing CTA', 'amber' ) ),
			amber_f_text( 'about_cta_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Contact Us for Pricing Plans', 'wrapper' => array( 'width' => '70' ) ) ),
			amber_f_textarea( 'about_cta_text', __( 'Text', 'amber' ), array( 'rows' => 3 ) ),
			amber_f_link( 'about_cta_btn', __( 'Button', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),

		),
	)
);

/* =========================================================================
 * Shared location for all product pages (ERP + its themed siblings). Every
 * product page reuses the same field names, so the same groups appear on all
 * four templates while values stay per-page (post meta).
 * ====================================================================== */
$amber_product_pages_location = array();
foreach ( array( 'page-amber-erp', 'page-zoomie', 'page-gozzbe', 'page-pos' ) as $amber_tpl ) {
	$amber_product_pages_location[] = array(
		array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/' . $amber_tpl . '.php' ),
	);
}

/* =========================================================================
 * Group D — Amber ERP Software Page (only when the "Amber ERP Software" template is selected)
 *
 * Every field here is read by template-parts/erp/amber-erp-page.php through
 * amber_sub(), so the hero shows its built-in defaults until these are filled
 * and live content afterwards. Scoped by page template so it only appears on
 * the ERP page; the generic "Page Content" group is excluded there.
 * ====================================================================== */
// POS hero trust-logo strip. Only the label is per-page now — the logos are
// the first eight of the shared Client Logos set (wp-admin → Client Logos), so
// this strip can't drift from the "Brands That Trust" grid lower down.
$amber_erp_hero_trust_fields = array(
	amber_f( 'tab', 'tab_erp_hero_trust', __( 'Trust Logos (POS)', 'amber' ) ),
	amber_f( 'message', 'erp_hero_trust_msg', '', array( 'message' => __( '"Brands trusted by" logo strip under the POS hero image. It shows the first eight of the shared <strong>Client Logos</strong> set — edit those once in wp-admin → <strong>Client Logos</strong>.', 'amber' ) ) ),
	amber_f_text( 'erp_hero_trust_label', __( 'Label', 'amber' ), array( 'default_value' => __( 'Brands trusted by', 'amber' ) ) ),
);

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp',
		'title'           => __( 'Amber — ERP Page', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Editable content for the Amber ERP Software page hero (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => array_merge(
			array(

				/* ---- Hero copy ---- */
				amber_f( 'tab', 'tab_erp_hero', __( 'Hero', 'amber' ) ),
				amber_f_text( 'erp_heading_accent', __( 'Headline — first line', 'amber' ), array( 'default_value' => 'Enterprise Resource Planning', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_text( 'erp_heading_rest', __( 'Headline — trailing word(s)', 'amber' ), array( 'default_value' => 'Software', 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_lead_bold', __( 'Bold statement line', 'amber' ), array( 'instructions' => __( 'Short bold sentence shown above the lead paragraph.', 'amber' ), 'default_value' => 'Unify every department, automate the busywork, and make faster decisions effortlessly.' ) ),
				amber_f_textarea( 'erp_lead', __( 'Lead paragraph', 'amber' ), array( 'rows' => 4, 'default_value' => 'Amber ERP Software is a connected business platform that unifies finance, inventory, sales and operations in one intelligent system. By capturing every transaction in real time, Amber ERP Software turns everyday business activity into actionable insights that power confident, data-driven decisions.' ) ),
				amber_f_text( 'erp_btn1_label', __( 'Button — label', 'amber' ), array( 'default_value' => 'Get Started', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'erp_btn1_url', __( 'Button — URL', 'amber' ), array( 'default_value' => '#get-started', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'erp_v2_btn2_label', __( 'Button 2 — label (Amber ERP Software page only)', 'amber' ), array( 'default_value' => 'Explore Features', 'wrapper' => array( 'width' => '25' ) ) ),
				amber_f_text( 'erp_v2_btn2_url', __( 'Button 2 — URL (Amber ERP Software page only)', 'amber' ), array( 'default_value' => '#erp-services', 'wrapper' => array( 'width' => '25' ) ) ),

				/* ---- Hero floating badges (Amber ERP Software page only) ---- */
				amber_f( 'tab', 'tab_erp_v2_badges', __( 'Hero Badges', 'amber' ) ),
				amber_f( 'message', 'erp_v2_badges_msg', '', array( 'message' => __( 'Icon + label for the four floating stat badges either side of the Amber ERP Software page headline. Leave an icon empty to use the bundled placeholder.', 'amber' ) ) ),
				amber_f_image( 'erp_v2_badge1_icon', __( 'Badge 1 icon (left, top)', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_text( 'erp_v2_badge1_label', __( 'Badge 1 label', 'amber' ), array( 'default_value' => 'Real-time Insight', 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_image( 'erp_v2_badge2_icon', __( 'Badge 2 icon (left, bottom)', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_text( 'erp_v2_badge2_label', __( 'Badge 2 label', 'amber' ), array( 'default_value' => 'Centralize Data Access', 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_image( 'erp_v2_badge3_icon', __( 'Badge 3 icon (right, top)', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_text( 'erp_v2_badge3_label', __( 'Badge 3 label', 'amber' ), array( 'default_value' => 'Seamless Integration', 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_image( 'erp_v2_badge4_icon', __( 'Badge 4 icon (right, bottom)', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_text( 'erp_v2_badge4_label', __( 'Badge 4 label', 'amber' ), array( 'default_value' => 'Secure & Reliable', 'wrapper' => array( 'width' => '50' ) ) ),

				/* ---- Hero decorative vectors (Amber ERP Software page only) ---- */
				amber_f( 'tab', 'tab_erp_v2_vectors', __( 'Hero Vectors', 'amber' ) ),
				amber_f( 'message', 'erp_v2_vectors_msg', '', array( 'message' => __( 'Decorative graphic behind each badge pair, either side of the Amber ERP Software page headline. Leave empty to use the bundled placeholder.', 'amber' ) ) ),
				amber_f_image( 'erp_v2_vector_left', __( 'Left vector image', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
				amber_f_image( 'erp_v2_vector_right', __( 'Right vector image', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),

				/* ---- Dashboard visual ---- */
				amber_f( 'tab', 'tab_erp_visual', __( 'Hero Background', 'amber' ) ),
				amber_f( 'message', 'erp_visual_msg', '', array( 'message' => __( 'Full-bleed background image behind the hero text. Leave empty to use the bundled default.', 'amber' ) ) ),
				amber_f_image( 'erp_hero_bg_image', __( 'Hero background image', 'amber' ), array( 'instructions' => __( 'Wide photo/graphic (about 16:9 or wider).', 'amber' ) ) ),

				/* ---- Hero dashboard/product image (Amber ERP Software + POS pages) ---- */
				amber_f( 'tab', 'tab_erp_hero_image', __( 'Hero Image', 'amber' ) ),
				amber_f( 'message', 'erp_hero_image_msg', '', array( 'message' => __( 'Amber ERP Software page: the dashboard screenshot shown below the headline. POS page: the product screenshot in the large frame below the headline. Leave empty to use the bundled placeholder.', 'amber' ) ) ),
				amber_f_image( 'erp_hero_image', __( 'Hero image', 'amber' ) ),

				/* ---- Hero dashboard floating cards (Amber ERP Software page only) ---- */
				amber_f( 'tab', 'tab_erp_v2_floats', __( 'Hero Floating Cards', 'amber' ) ),
				amber_f( 'message', 'erp_v2_floats_msg', '', array( 'message' => __( 'Four small stat-card images that overlap the dashboard screenshot on the Amber ERP Software page. Leave empty to use the bundled placeholder.', 'amber' ) ) ),
				amber_f_image( 'erp_v2_float1_image', __( 'Floating card 1 (top-left — e.g. payment received)', 'amber' ) ),
				amber_f_image( 'erp_v2_float2_image', __( 'Floating card 2 (top-right — e.g. total sales)', 'amber' ) ),
				amber_f_image( 'erp_v2_float3_image', __( 'Floating card 3 (bottom-left — e.g. new order)', 'amber' ) ),
				amber_f_image( 'erp_v2_float4_image', __( 'Floating card 4 (bottom-right — e.g. top product)', 'amber' ) ),

				/* ---- "Why Amber POS Software" image (split band, POS page only) ---- */
				amber_f( 'tab', 'tab_erp_pwhy_image', __( 'Why Amber POS Software Image', 'amber' ) ),
				amber_f( 'message', 'erp_pwhy_image_msg', '', array( 'message' => __( 'Product screenshot shown on the right of the "Why Choose Amber POS Software" section. Leave empty to use the bundled dashboard screenshot.', 'amber' ) ) ),
				amber_f_image( 'erp_pwhy_image', __( 'Why Amber POS Software image', 'amber' ) ),

				/* ---- Key Features carousel (Amber ERP Software page only) ---- */
				amber_f( 'tab', 'tab_erp_kf', __( 'Key Features', 'amber' ) ),
				amber_f( 'message', 'erp_kf_msg', '', array( 'message' => __( 'The auto-advancing "Key features" carousel between the challenges section and the "Why Amber" cards. Each slide has its own illustration, title and description. Leave a slide\'s title empty to remove it from the carousel.', 'amber' ) ) ),
				amber_f_text( 'erp_kf_heading', __( 'Section heading', 'amber' ), array( 'default_value' => "Key features of Amber ERP Software for retail stores" ) ),
				amber_f_textarea( 'erp_kf_lead', __( 'Section lead', 'amber' ), array( 'rows' => 2, 'default_value' => 'Retail Innovation: One Solution Fits All With Extensive Features' ) ),

				amber_f( 'message', 'erp_kf1_msg', '', array( 'message' => __( 'Slide 1', 'amber' ) ) ),
				amber_f_image( 'erp_kf1_image', __( 'Slide 1 — illustration', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_kf1_title', __( 'Slide 1 — title', 'amber' ), array( 'default_value' => 'Seamless Omnichannel Integration', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_textarea( 'erp_kf1_text', __( 'Slide 1 — description', 'amber' ), array( 'rows' => 3, 'default_value' => "Effortlessly expand your retail presence with Amber ERP Software's omnichannel capabilities. Whether it's an online shop, marketplace integrations, or your own custom storefront, Amber ERP Software simplifies omnichannel store management from one dashboard." ) ),

				amber_f( 'message', 'erp_kf2_msg', '', array( 'message' => __( 'Slide 2', 'amber' ) ) ),
				amber_f_image( 'erp_kf2_image', __( 'Slide 2 — illustration', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_kf2_title', __( 'Slide 2 — title', 'amber' ), array( 'default_value' => 'Notifications and Reminders', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_textarea( 'erp_kf2_text', __( 'Slide 2 — description', 'amber' ), array( 'rows' => 3, 'default_value' => 'Stay connected with your business through WhatsApp, SMS, and email notifications. Receive reminders for crucial reports, payment deadlines, and customer occasions such as birthdays and anniversaries — plus bulk promotional messages, stock alerts, and payable/receivable summaries.' ) ),

				amber_f( 'message', 'erp_kf3_msg', '', array( 'message' => __( 'Slide 3', 'amber' ) ) ),
				amber_f_image( 'erp_kf3_image', __( 'Slide 3 — illustration', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_kf3_title', __( 'Slide 3 — title', 'amber' ), array( 'default_value' => 'Harness the Power of Integrations', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_textarea( 'erp_kf3_text', __( 'Slide 3 — description', 'amber' ), array( 'rows' => 3, 'default_value' => 'Connect Amber ERP Software with the accounting, payment, logistics and e-commerce tools you already use, so data flows automatically between every system instead of being re-entered by hand.' ) ),

				amber_f( 'message', 'erp_kf4_msg', '', array( 'message' => __( 'Slide 4', 'amber' ) ) ),
				amber_f_image( 'erp_kf4_image', __( 'Slide 4 — illustration', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_kf4_title', __( 'Slide 4 — title', 'amber' ), array( 'default_value' => 'Real-Time Inventory Control', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_textarea( 'erp_kf4_text', __( 'Slide 4 — description', 'amber' ), array( 'rows' => 3, 'default_value' => 'Track stock across every outlet and warehouse in real time, with low-stock alerts and automated reorder suggestions that keep shelves full without tying up capital in excess inventory.' ) ),

				amber_f( 'message', 'erp_kf5_msg', '', array( 'message' => __( 'Slide 5', 'amber' ) ) ),
				amber_f_image( 'erp_kf5_image', __( 'Slide 5 — illustration', 'amber' ), array( 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_text( 'erp_kf5_title', __( 'Slide 5 — title', 'amber' ), array( 'default_value' => 'Advanced Reporting and Analytics', 'wrapper' => array( 'width' => '70' ) ) ),
				amber_f_textarea( 'erp_kf5_text', __( 'Slide 5 — description', 'amber' ), array( 'rows' => 3, 'default_value' => 'Turn every transaction into a clear, visual report — sales trends, profit margins, staff performance and more — so decisions are backed by real data instead of guesswork.' ) ),
			),
			$amber_erp_hero_trust_fields
		),
	)
);
acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_choose',
		'title'           => __( 'Amber — POS: How To Choose', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "How to choose the right POS solution" checklist section (POS page only; ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_choose_fields,
	)
);

/* =========================================================================
 * Group — Amber POS Software: Key Features (interactive list + detail panel)
 *
 * Field names follow erp_pkey{n}_title/text/image/url, read by
 * template-parts/erp/amber-erp-page.php through amber_sub(). Defaults come
 * from amber_erp_pos_key_feature_defaults() so admin placeholders match the
 * demo output. POS-only section (rendered right after "Why Amber POS Software").
 * ====================================================================== */
$amber_pos_key_fields = array(
	amber_f_textarea( 'erp_pkey_heading', __( 'Heading', 'amber' ), array( 'rows' => 2, 'default_value' => "Key Features\nof a POS System", 'instructions' => __( 'A line break starts a new line in the heading.', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
	amber_f_text( 'erp_pkey_learn_label', __( '"Learn more" link label', 'amber' ), array( 'default_value' => __( 'Learn more', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
);
foreach ( amber_erp_pos_key_feature_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: key-feature row number. */
	$amber_pos_key_fields[] = amber_f( 'tab', "tab_pos_key{$amber_i}", sprintf( __( 'Row %d', 'amber' ), $amber_i ) );
	$amber_pos_key_fields[] = amber_f_text( "erp_pkey{$amber_i}_title", __( 'Title', 'amber' ), array( 'default_value' => $amber_d['title'], 'instructions' => __( 'Leave blank to hide this row.', 'amber' ), 'wrapper' => array( 'width' => '40' ) ) );
	$amber_pos_key_fields[] = amber_f_textarea( "erp_pkey{$amber_i}_text", __( 'Description', 'amber' ), array( 'rows' => 2, 'default_value' => $amber_d['text'], 'wrapper' => array( 'width' => '60' ) ) );
	$amber_pos_key_fields[] = amber_f_image( "erp_pkey{$amber_i}_image", __( 'Panel image', 'amber' ), array( 'instructions' => __( 'Leave empty for the bundled placeholder.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
	$amber_pos_key_fields[] = amber_f_text( "erp_pkey{$amber_i}_url", __( '"Learn more" URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_pos_key',
		'title'           => __( 'Amber — POS: Key Features', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Key Features of a POS System" interactive section (POS page only; ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_pos_key_fields,
	)
);

/* =========================================================================
 * Group — Amber Restaurant Software: Three-Step Benefits (#zm-steps — eyebrow +
 * accent-highlighted heading, horizontal pill tabs, one large stage panel
 * per step with a screenshot and a checklist)
 *
 * Field names follow erp_zmstep_eyebrow/heading/heading_accent/sub and
 * erp_zmstep{n}_title/text/image/bullet{1..3}, read by
 * template-parts/erp/amber-erp-page.php through amber_sub(). Amber
 * Restaurant Software page only.
 * ====================================================================== */
$amber_zm_step_defaults = array(
	array(
		'title'   => 'Digital Management',
		'text'    => 'Those days are over when people used to manage their restaurant manually. Now it\'s the era of digital technology, everyone has their own smart technology on their hands. Thus expects the same advanced and technical service from everyone.',
		'text2'   => 'And to catch this digital pace, your restaurant must adopt digital management technology. Amber Restaurant Software offers exactly what you want:',
		'bullets' => array(
			'User-friendly interface',
			'Straightforward management system',
			'Smooth and fast billing system',
		),
	),
	array(
		'title'   => 'Take Control On Your Own Hand',
		'text'    => 'You can eliminate all the stress and tension of "not having a strong control and management system" in your restaurant. Now you can not only monitor but also control and record all the data.',
		'text2'   => 'With Amber Restaurant Software, you can get all-in one solution for all your management related operations in your restaurant, no matter what size and type your restaurant is. Easy managerial system for your restaurant.',
		'bullets' => array(
			'Eliminate the possibilities of theft',
			'Better control on the operational cost',
			'Heads-up about monthly/yearly sales; with graphical presentation',
		),
	),
	array(
		'title'   => 'Integration',
		'text'    => 'Amber Restaurant Software, quite strongly integrated with Website, App and Third party delivery companies. Moreover with this powerful integration your restaurant can enrich the online visibility, establish the brand, and of course higher customer reach.',
		'text2'   => 'This integration system opens up the opportunity to manage, record and control all the synchronization of the payment data, order data, customer data, accounts data and optimize a chronological workflow at no-time.',
		'bullets' => array(
			'Powerful integration with website, app and third party',
			'Raise-up the customer engagement',
			'Make your website globally ranked',
		),
	),
);

$amber_zm_step_fields = array(
	amber_f( 'tab', 'tab_zmstep_intro', __( 'Section', 'amber' ) ),
	amber_f_text( 'erp_zmstep_eyebrow', __( 'Eyebrow', 'amber' ), array( 'default_value' => "Don't Worry!!!", 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_zmstep_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Your Restaurant Is in Safe Hands With', 'wrapper' => array( 'width' => '40' ) ) ),
	amber_f_text( 'erp_zmstep_heading_accent', __( 'Heading — highlighted phrase', 'amber' ), array( 'default_value' => 'Amber Restaurant Software', 'instructions' => __( 'Appended after the heading, in brand colour.', 'amber' ), 'wrapper' => array( 'width' => '35' ) ) ),
	amber_f_text( 'erp_zmstep_sub', __( 'Subheading', 'amber' ), array( 'default_value' => 'Boost your restaurant with three main steps' ) ),
);
foreach ( $amber_zm_step_defaults as $amber_i => $amber_d ) {
	$amber_n = $amber_i + 1;
	/* translators: %d: step number. */
	$amber_zm_step_fields[] = amber_f( 'tab', "tab_zmstep{$amber_n}", sprintf( __( 'Step %d', 'amber' ), $amber_n ) );
	$amber_zm_step_fields[] = amber_f( 'message', "erp_zmstep{$amber_n}_msg", '', array( 'message' => __( 'Leave "Title" blank to remove this step from the tab bar entirely.', 'amber' ) ) );
	$amber_zm_step_fields[] = amber_f_text( "erp_zmstep{$amber_n}_title", __( 'Title', 'amber' ), array( 'default_value' => $amber_d['title'], 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_step_fields[] = amber_f_image( "erp_zmstep{$amber_n}_image", __( 'Screenshot', 'amber' ), array( 'instructions' => __( 'Leave empty for the bundled placeholder.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_step_fields[] = amber_f_textarea( "erp_zmstep{$amber_n}_text", __( 'Description — paragraph 1', 'amber' ), array( 'rows' => 3, 'default_value' => $amber_d['text'] ) );
	$amber_zm_step_fields[] = amber_f_textarea( "erp_zmstep{$amber_n}_text2", __( 'Description — paragraph 2', 'amber' ), array( 'rows' => 3, 'default_value' => $amber_d['text2'], 'instructions' => __( 'Leave blank to show only the first paragraph.', 'amber' ) ) );
	foreach ( $amber_d['bullets'] as $amber_b_i => $amber_b_default ) {
		$amber_b_n = $amber_b_i + 1;
		/* translators: %d: checklist bullet number. */
		$amber_zm_step_fields[] = amber_f_text( "erp_zmstep{$amber_n}_bullet{$amber_b_n}", sprintf( __( 'Checklist point %d', 'amber' ), $amber_b_n ), array( 'default_value' => $amber_b_default, 'wrapper' => array( 'width' => '33' ) ) );
	}
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_zm_steps',
		'title'           => __( 'Amber Restaurant Software — Three-Step Benefits', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Your Restaurant Is in Safe Hands" three-step section (Amber Restaurant Software page only; ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_zm_step_fields,
	)
);

/* =========================================================================
 * Group — Amber Restaurant Software: Restaurant Types (#zm-types — centred
 * heading with one brand-coloured word, then a photo-card grid of the
 * verticals the product serves)
 *
 * Field names follow erp_zmtype_heading/heading_accent/heading_after and
 * erp_zmtype{n}_label/image/link, read by
 * template-parts/erp/amber-erp-page.php through amber_sub(). Each tile falls
 * back to a brand-gradient icon frame until a photo is uploaded, and clearing
 * a label removes that tile from the grid. Amber Restaurant Software page
 * only.
 * ====================================================================== */
$amber_zm_type_defaults = array(
	array( 'label' => 'Cafe & Coffee Shop', 'icon' => 'star' ),
	array( 'label' => 'Franchise Management', 'icon' => 'layers' ),
	array( 'label' => 'Burger & Sandwich Shop', 'icon' => 'cart' ),
	array( 'label' => 'Pizza Restaurant', 'icon' => 'utensils' ),
	array( 'label' => 'Cloud Kitchen', 'icon' => 'cloud' ),
	array( 'label' => 'Seafood Restaurant', 'icon' => 'globe' ),
	array( 'label' => 'Fine Dine Restaurant', 'icon' => 'spark' ),
	array( 'label' => 'Food Truck', 'icon' => 'truck' ),
	array( 'label' => 'Quick Service Restaurant', 'icon' => 'bolt' ),
	array( 'label' => 'Catering Service', 'icon' => 'briefcase' ),
);

// Icon choices for the no-photo fallback frame. Same shape as the About page's
// select — every key maps 1:1 to amber_icon_svg().
$amber_zm_type_icons = array_merge(
	amber_about_icon_choices(),
	array(
		'utensils' => __( 'Cutlery', 'amber' ),
		'cart'     => __( 'Cart', 'amber' ),
		'truck'    => __( 'Truck', 'amber' ),
		'spark'    => __( 'Spark', 'amber' ),
		'cube'     => __( 'Cube', 'amber' ),
		'pos'      => __( 'POS terminal', 'amber' ),
		'users'    => __( 'Users', 'amber' ),
	)
);

$amber_zm_type_fields = array(
	amber_f( 'tab', 'tab_zmtype_intro', __( 'Section', 'amber' ) ),
	amber_f_text( 'erp_zmtype_heading', __( 'Heading', 'amber' ), array( 'default_value' => 'Built for Every Kind of', 'wrapper' => array( 'width' => '40' ) ) ),
	amber_f_text( 'erp_zmtype_heading_accent', __( 'Heading — highlighted word', 'amber' ), array( 'default_value' => 'Restaurant', 'instructions' => __( 'Rendered inline after the heading, in brand colour.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
	amber_f_text( 'erp_zmtype_heading_after', __( 'Heading — text after the highlight', 'amber' ), array( 'instructions' => __( 'Optional, e.g. "You Have".', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
	amber_f( 'message', 'erp_zmtype_msg', '', array( 'message' => __( 'Leave a tile\'s "Label" blank to remove that tile from the grid. Tiles without a photo show a brand-gradient icon frame instead.', 'amber' ) ) ),
);
foreach ( $amber_zm_type_defaults as $amber_i => $amber_d ) {
	$amber_n = $amber_i + 1;
	/* translators: %d: restaurant-type tile number. */
	$amber_zm_type_fields[] = amber_f( 'tab', "tab_zmtype{$amber_n}", sprintf( __( 'Type %d', 'amber' ), $amber_n ) );
	$amber_zm_type_fields[] = amber_f_text( "erp_zmtype{$amber_n}_label", __( 'Label', 'amber' ), array( 'default_value' => $amber_d['label'], 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_type_fields[] = amber_f_image( "erp_zmtype{$amber_n}_image", __( 'Photo', 'amber' ), array( 'instructions' => __( 'Landscape (roughly 284 × 230) works best — the tile crops to fill.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_type_fields[] = amber_f_link( "erp_zmtype{$amber_n}_link", __( 'Link', 'amber' ), array( 'instructions' => __( 'Optional — makes the tile clickable.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_type_fields[] = amber_f_select( "erp_zmtype{$amber_n}_icon", __( 'Fallback icon', 'amber' ), $amber_zm_type_icons, array( 'default_value' => $amber_d['icon'], 'instructions' => __( 'Only shown while this tile has no photo.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_zm_types',
		'title'           => __( 'Amber Restaurant Software — Restaurant Types', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The restaurant-types photo grid (Amber Restaurant Software page only; ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_zm_type_fields,
	)
);

/* =========================================================================
 * Group — Amber Restaurant Software: Key Features (the "Key Features" module panel —
 * fixed module list | photo | heading/description/"Key Highlights" bullets)
 *
 * Field names follow erp_zmmod{n}_label/heading/image/text/point{1..3} and
 * erp_zmmod_eyebrow/erp_zmmod_kh_label, read by
 * template-parts/erp/amber-erp-page.php through amber_sub(). Defaults mirror
 * the six bundled modules (POS & Order Management, Staff & Shift Management,
 * Accounting & Financial Management, Delivery & Third-Party Integration,
 * Table & Reservation Management, Online Ordering & QR Menu) so admin
 * placeholders match the demo output. Each point field is a single
 * "Bold label: description" line — the template splits on the first colon to
 * bold the label and render the rest plain. Clearing a module's heading
 * removes that row from the list entirely; clearing a point removes just
 * that bullet. Amber Restaurant Software page only.
 * ====================================================================== */
$amber_zm_mod_defaults = array(
	array(
		'label'   => 'POS & Order Management',
		'text'    => 'The POS and Order Management module serves as the central operational framework of the restaurant, ensuring accuracy, speed, and consistency across all order types. It streamlines billing, order processing, and payment workflows, enabling staff to deliver efficient service and maintain complete control over every transaction.',
		'points'  => array(
			'Centralized POS Interface: Manage dine-in, takeaway, and online orders within a unified system.',
			'Real-Time Order Tracking: Monitor table occupancy and order status through a live visual dashboard.',
			'Menu Configuration: Update pricing, availability, and combinations dynamically based on operational requirements.',
		),
	),
	array(
		'label'   => 'Staff & Shift Management',
		'text'    => 'The Staff and Shift Management module keeps every roster, role and attendance record in one place, so managers spend less time on spreadsheets and more time on the floor. Shift changes, overtime and permissions all stay visible and auditable.',
		'points'  => array(
			'Shift Scheduling: Build and publish weekly rosters with drag-and-drop assignment.',
			'Attendance Tracking: Clock-in and clock-out logs sync automatically to payroll.',
			'Role-Based Permissions: Control exactly what each staff member can see and do.',
		),
	),
	array(
		'label'   => 'Accounting & Financial Management',
		'text'    => 'Every transaction, refund and expense rolls up automatically into the Accounting and Financial Management module, giving owners a clear, always-current view of the business without manual reconciliation at month end.',
		'points'  => array(
			'Automated Reconciliation: Match POS transactions against bank deposits without spreadsheets.',
			'Expense Tracking: Log supplier bills and recurring costs against each outlet.',
			'Profit & Loss Reports: Generate outlet-level or brand-wide statements on demand.',
		),
	),
	array(
		'label'   => 'Delivery & Third-Party Integration',
		'text'    => 'The Delivery and Third-Party Integration module pulls every delivery partner into one order queue, so kitchen staff never have to juggle multiple tablets or manually re-key an order that already arrived digitally.',
		'points'  => array(
			'Unified Order Queue: Aggregate orders from every delivery partner into one screen.',
			'Menu Sync: Push price and availability changes to all platforms simultaneously.',
			'Live Rider Tracking: Follow delivery status from dispatch to doorstep.',
		),
	),
	array(
		'label'   => 'Table & Reservation Management',
		'text'    => 'The Table and Reservation Management module gives hosts a live floor plan and a booking calendar in one screen, so tables turn faster and walk-ins never get seated on top of a reservation.',
		'points'  => array(
			'Live Floor Plan: See every table\'s status — open, seated, or reserved — at a glance.',
			'Online Booking: Let guests reserve a table directly from your website or app.',
			'Waitlist Management: Text walk-ins automatically when their table is ready.',
		),
	),
	array(
		'label'   => 'Online Ordering & QR Menu',
		'text'    => 'The Online Ordering and QR Menu module turns every table into a self-service order point and every share link into a storefront, so guests order at their own pace and staff spend less time taking down orders by hand.',
		'points'  => array(
			'Scan-to-Order: Guests browse and order from a QR code at the table, no app required.',
			'Branded Storefront: Take direct online orders without paying marketplace commission.',
			'Live Menu Availability: Sold-out items disappear instantly across every channel.',
		),
	),
);

$amber_zm_mod_fields = array(
	amber_f( 'tab', 'tab_zmmod_intro', __( 'Section', 'amber' ) ),
	amber_f_text( 'erp_zmmod_eyebrow', __( 'Section eyebrow', 'amber' ), array( 'default_value' => __( 'Key Features', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
	amber_f_text( 'erp_zmmod_kh_label', __( '"Key Highlights" label', 'amber' ), array( 'default_value' => __( 'Key Highlights:', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
);
foreach ( $amber_zm_mod_defaults as $amber_i => $amber_d ) {
	$amber_n = $amber_i + 1;
	/* translators: %d: module row number. */
	$amber_zm_mod_fields[] = amber_f( 'tab', "tab_zmmod{$amber_n}", sprintf( __( 'Module %d', 'amber' ), $amber_n ) );
	$amber_zm_mod_fields[] = amber_f( 'message', "erp_zmmod{$amber_n}_msg", '', array( 'message' => __( 'Leave "Heading" blank to remove this module from the list entirely.', 'amber' ) ) );
	$amber_zm_mod_fields[] = amber_f_text( "erp_zmmod{$amber_n}_label", __( 'Nav label', 'amber' ), array( 'default_value' => $amber_d['label'], 'instructions' => __( 'Shown in the left-hand module list. Leave blank to reuse the heading.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_mod_fields[] = amber_f_text( "erp_zmmod{$amber_n}_heading", __( 'Heading', 'amber' ), array( 'default_value' => $amber_d['label'], 'wrapper' => array( 'width' => '50' ) ) );
	$amber_zm_mod_fields[] = amber_f_image( "erp_zmmod{$amber_n}_image", __( 'Photo', 'amber' ), array( 'instructions' => __( 'Fixed-ratio photo shown next to this module\'s panel. Leave empty for the bundled placeholder.', 'amber' ) ) );
	$amber_zm_mod_fields[] = amber_f_textarea( "erp_zmmod{$amber_n}_text", __( 'Description', 'amber' ), array( 'rows' => 4, 'default_value' => $amber_d['text'] ) );
	foreach ( $amber_d['points'] as $amber_pt_i => $amber_pt_default ) {
		$amber_pt_n = $amber_pt_i + 1;
		/* translators: %d: highlight bullet number. */
		$amber_zm_mod_fields[] = amber_f_text( "erp_zmmod{$amber_n}_point{$amber_pt_n}", sprintf( __( 'Key Highlight %d', 'amber' ), $amber_pt_n ), array( 'default_value' => $amber_pt_default, 'instructions' => __( 'Format: "Bold label: description text." Leave blank to remove this bullet.', 'amber' ) ) );
	}
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_zm_modules',
		'title'           => __( 'Amber — Amber Restaurant Software: Key Features', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Key Features" module panel (module list + photo + Key Highlights). Amber Restaurant Software page only; ACF Free. Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_zm_mod_fields,
	)
);

/* =========================================================================
 * Group E — Amber ERP Software Features (Key Features tabbed section on the ERP page)
 *
 * Five feature tabs, each with a tab label + icon, a dashboard image, a
 * title/description/CTA and eight highlight cards (2 left, 2 right, 4 bottom).
 * Field names follow erp_ft{n}_* and are read by the ERP template through
 * amber_sub(). Defaults come from amber_erp_feature_defaults() so the admin
 * placeholders and the demo output always match.
 * ====================================================================== */

/**
 * Icon choices for the ERP feature tabs/cards (about set + commerce icons).
 *
 * @return array<string,string> value => label
 */
function amber_erp_icon_choices() {
	return array_merge(
		amber_about_icon_choices(),
		array(
			'cart'  => __( 'Cart', 'amber' ),
			'truck' => __( 'Truck', 'amber' ),
			'spark' => __( 'Spark', 'amber' ),
			'pos'   => __( 'POS', 'amber' ),
			'cube'  => __( 'Cube', 'amber' ),
		)
	);
}

$amber_erp_ft_fields = array(
	amber_f_text( 'erp_features_eyebrow', __( 'Section label (eyebrow)', 'amber' ), array( 'default_value' => __( 'Features', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_features_heading', __( 'Section heading', 'amber' ), array( 'default_value' => __( 'Key Features of Amber ERP Software', 'amber' ), 'wrapper' => array( 'width' => '35' ) ) ),
	amber_f_textarea( 'erp_features_sub', __( 'Section subtitle', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'Powerful modules built to automate workflows, improve productivity, and provide real-time business insights.', 'amber' ), 'wrapper' => array( 'width' => '40' ) ) ),
);

$amber_erp_card_labels = array(
	'l1' => __( 'Left card 1', 'amber' ),
	'l2' => __( 'Left card 2', 'amber' ),
	'r1' => __( 'Right card 1', 'amber' ),
	'r2' => __( 'Right card 2', 'amber' ),
	'b1' => __( 'Bottom card 1', 'amber' ),
	'b2' => __( 'Bottom card 2', 'amber' ),
	'b3' => __( 'Bottom card 3', 'amber' ),
	'b4' => __( 'Bottom card 4', 'amber' ),
);

// Slots 1–7 carry the shared defaults; 8–20 are spare cards a product page can
// fill (several products list more modules than the defaults cover). The
// template skips any slot whose label is left blank.
$amber_erp_defaults = amber_erp_feature_defaults();

for ( $amber_i = 1; $amber_i <= 20; $amber_i++ ) {
	$amber_d = $amber_erp_defaults[ $amber_i ] ?? null;

	/* translators: %d: feature card number. */
	$amber_erp_ft_fields[] = amber_f( 'tab', "tab_erp_ft{$amber_i}", sprintf( __( 'Card %d', 'amber' ), $amber_i ) );
	$amber_erp_ft_fields[] = amber_f_text( "erp_ft{$amber_i}_label", __( 'Card title', 'amber' ), array( 'default_value' => $amber_d['label'] ?? '', 'instructions' => __( 'Leave blank to hide this card.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) );
	$amber_erp_ft_fields[] = amber_f_textarea( "erp_ft{$amber_i}_desc", __( 'Card description', 'amber' ), array( 'rows' => 2, 'default_value' => $amber_d['desc'] ?? '', 'wrapper' => array( 'width' => '70' ) ) );

	if ( ! $amber_d ) {
		continue;
	}

	$amber_erp_ft_fields[] = amber_f_select( "erp_ft{$amber_i}_icon", __( 'Tab icon', 'amber' ), amber_erp_icon_choices(), array( 'default_value' => $amber_d['icon'], 'wrapper' => array( 'width' => '20' ) ) );
	$amber_erp_ft_fields[] = amber_f_image( "erp_ft{$amber_i}_image", __( 'Dashboard image', 'amber' ), array( 'instructions' => __( 'Wide screenshot (about 3:2). Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );

	foreach ( $amber_d['cards'] as $amber_ck => $amber_cd ) {
		$amber_lab = $amber_erp_card_labels[ $amber_ck ];
		$amber_erp_ft_fields[] = amber_f_select( "erp_ft{$amber_i}_{$amber_ck}_icon", $amber_lab . ' — ' . __( 'icon', 'amber' ), amber_erp_icon_choices(), array( 'default_value' => $amber_cd[0], 'wrapper' => array( 'width' => '20' ) ) );
		$amber_erp_ft_fields[] = amber_f_text( "erp_ft{$amber_i}_{$amber_ck}_title", $amber_lab . ' — ' . __( 'title', 'amber' ), array( 'default_value' => $amber_cd[1], 'wrapper' => array( 'width' => '30' ) ) );
		$amber_erp_ft_fields[] = amber_f_text( "erp_ft{$amber_i}_{$amber_ck}_text", $amber_lab . ' — ' . __( 'text', 'amber' ), array( 'default_value' => $amber_cd[2], 'wrapper' => array( 'width' => '50' ) ) );
	}
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_features',
		'title'           => __( 'Amber — ERP Features', 'amber' ),
		'menu_order'      => 1,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The Key Features tabbed section on the Amber ERP Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_erp_ft_fields,
	)
);

/* =========================================================================
 * Group F — Amber ERP Software Services (numbered two-column services grid)
 *
 * Split heading + right-aligned intro, then ten numbered services (title +
 * text). Field names follow erp_svc{n}_*; defaults come from
 * amber_erp_service_defaults() so admin placeholders match the demo output.
 * ====================================================================== */
$amber_erp_svc_fields = array(
	amber_f_text( 'erp_svc_heading', __( 'Heading — leading words', 'amber' ), array( 'default_value' => __( 'End-to-End', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_svc_heading_accent', __( 'Heading — accent phrase', 'amber' ), array( 'default_value' => __( 'ERP Solutions & Services', 'amber' ), 'wrapper' => array( 'width' => '35' ) ) ),
	amber_f_textarea( 'erp_svc_desc', __( 'Intro paragraph (right-aligned)', 'amber' ), array( 'rows' => 3, 'default_value' => __( 'Our experienced team delivers top-tier ERP implementation and support. We specialise in finance, inventory, sales and operations, and provide complete solutions — including custom modules and integrations — to ensure your business runs smoothly across every department.', 'amber' ), 'wrapper' => array( 'width' => '40' ) ) ),
);

foreach ( amber_erp_service_defaults() as $amber_i => $amber_d ) {
	/* translators: %02d: service number. */
	$amber_erp_svc_fields[] = amber_f_text( "erp_svc{$amber_i}_title", sprintf( __( 'Service %02d — title', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['title'], 'wrapper' => array( 'width' => '35' ) ) );
	$amber_erp_svc_fields[] = amber_f_textarea( "erp_svc{$amber_i}_text", sprintf( __( 'Service %02d — text', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d['text'], 'wrapper' => array( 'width' => '65' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_services',
		'title'           => __( 'Amber — ERP Services', 'amber' ),
		'menu_order'      => 2,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The numbered ERP services grid on the Amber ERP Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_erp_svc_fields,
	)
);

/* =========================================================================
 * Group H — Amber ERP Software Industries (staggered icon-card grid)
 *
 * Heading + intro + CTA on the left, eight industry cards (icon + label) on
 * the right. Field names follow erp_ind{n}_*; defaults come from
 * amber_erp_industry_defaults().
 * ====================================================================== */

/**
 * Icon choices for the ERP industry cards (ERP set + sector icons).
 *
 * @return array<string,string> value => label
 */
function amber_erp_industry_icon_choices() {
	return array_merge(
		amber_erp_icon_choices(),
		array(
			'utensils' => __( 'Utensils (restaurant)', 'amber' ),
			'factory'  => __( 'Factory', 'amber' ),
			'pin'      => __( 'Map pin', 'amber' ),
			'cap'      => __( 'Graduation cap', 'amber' ),
			'health'   => __( 'Health', 'amber' ),
			'washer'   => __( 'Washer (laundry)', 'amber' ),
		)
	);
}

$amber_erp_ind_fields = array(
	amber_f_text( 'erp_ind_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Industries We Serve', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_textarea( 'erp_ind_desc', __( 'Intro paragraph', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'We have extensive experience delivering Amber ERP Software across every kind of operation — each rollout tuned to the workflows, compliance needs and pace of your industry.', 'amber' ), 'wrapper' => array( 'width' => '45' ) ) ),
	amber_f_text( 'erp_ind_btn_label', __( 'Button label', 'amber' ), array( 'default_value' => __( 'Explore Industry Solutions', 'amber' ), 'wrapper' => array( 'width' => '15' ) ) ),
	amber_f_text( 'erp_ind_btn_url', __( 'Button URL', 'amber' ), array( 'default_value' => '#demo', 'wrapper' => array( 'width' => '15' ) ) ),
);

foreach ( amber_erp_industry_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: industry card number. */
	$amber_erp_ind_fields[] = amber_f_select( "erp_ind{$amber_i}_icon", sprintf( __( 'Industry %d — icon', 'amber' ), $amber_i ), amber_erp_industry_icon_choices(), array( 'default_value' => $amber_d['icon'], 'wrapper' => array( 'width' => '20' ) ) );
	$amber_erp_ind_fields[] = amber_f_text( "erp_ind{$amber_i}_label", sprintf( __( 'Industry %d — label', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['label'], 'wrapper' => array( 'width' => '30' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_industries',
		'title'           => __( 'Amber — ERP Industries', 'amber' ),
		'menu_order'      => 3,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Industries We Serve" section on the Amber ERP Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_erp_ind_fields,
	)
);

/* =========================================================================
 * Group H2 — Amber ERP Software "Brands That Trust" logo strip
 * ====================================================================== */
$amber_erp_brands_fields = array(
	amber_f_text( 'erp_brands_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Connect Amber ERP Software with your favorite tools and apps', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
	amber_f_textarea( 'erp_brands_sub', __( 'Subtitle', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'Supercharge your experience by connecting with the tools you already use – and manage everything in one place, seamlessly.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
	amber_f_text( 'erp_brands_btn_label', __( 'Button 1 label', 'amber' ), array( 'default_value' => __( 'Start a free trial', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_brands_btn_url', __( 'Button 1 URL', 'amber' ), array( 'default_value' => '#contact', 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_brands_btn2_label', __( 'Button 2 label', 'amber' ), array( 'default_value' => __( 'View all integrations', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_brands_btn2_url', __( 'Button 2 URL', 'amber' ), array( 'default_value' => '#contact', 'wrapper' => array( 'width' => '25' ) ) ),
);

// The logos themselves are no longer edited per page — they come from the one
// shared Client Logos set (wp-admin → Client Logos), so only this section's
// heading and buttons remain per-product.
$amber_erp_brands_fields[] = amber_f(
	'message',
	'erp_brands_logos_msg',
	'',
	array( 'message' => __( 'The logos shown in this section come from the shared <strong>Client Logos</strong> set — edit them once in wp-admin → <strong>Client Logos</strong> and every page updates together.', 'amber' ) )
);

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_brands',
		'title'           => __( 'Amber — ERP Brands', 'amber' ),
		'menu_order'      => 4,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Brands That Trust" logo strip on the Amber ERP Software page, shown above the CTA (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_erp_brands_fields,
	)
);

/* =========================================================================
 * Group I — Amber ERP Software CTA ("Try free / Talk to an expert" split)
 * ====================================================================== */
acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_cta',
		'title'           => __( 'Amber — ERP CTA', 'amber' ),
		'menu_order'      => 5,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The two-card "Try free / Talk to an expert" CTA on the Amber ERP Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => array(
			amber_f_text( 'erp_cta1_heading', __( 'Card 1 — heading', 'amber' ), array( 'default_value' => __( 'Try Amber ERP Software Free for 14 Days', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'erp_cta1_text', __( 'Card 1 — text', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'Try Amber ERP Software free for 14 days. Set up your workspace, test workflows, and see how easy it is to launch or optimize.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'erp_cta1_btn_label', __( 'Card 1 — button label', 'amber' ), array( 'default_value' => __( 'Start Free Trial', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
			amber_f_text( 'erp_cta1_btn_url', __( 'Card 1 — button URL', 'amber' ), array( 'default_value' => '#contact', 'wrapper' => array( 'width' => '25' ) ) ),
			amber_f_image( 'erp_cta1_image', __( 'Card 1 — image', 'amber' ), array( 'instructions' => __( 'Wide screenshot (about 3:2). Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'erp_cta2_heading', __( 'Card 2 — heading', 'amber' ), array( 'default_value' => __( 'See How Amber ERP Software Fits Your Workflow', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'erp_cta2_text', __( 'Card 2 — text', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'Speak with an expert to explore how Amber ERP Software fits your business — from daily operations to reporting.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'erp_cta2_btn_label', __( 'Card 2 — button label', 'amber' ), array( 'default_value' => __( 'Talk to an Expert', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
			amber_f_text( 'erp_cta2_btn_url', __( 'Card 2 — button URL', 'amber' ), array( 'default_value' => '#contact', 'wrapper' => array( 'width' => '25' ) ) ),
			amber_f_image( 'erp_cta2_image', __( 'Card 2 — image', 'amber' ), array( 'instructions' => __( 'Wide screenshot (about 3:2). Leave empty for the bundled default.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
		),
	)
);

/* =========================================================================
 * Group G — Amber ERP Software FAQ (accordion on the ERP page)
 *
 * Eyebrow + heading + intro, then eight Q/A pairs. Field names follow
 * erp_faq{n}_*; defaults come from amber_erp_faq_defaults().
 * ====================================================================== */
$amber_erp_faq_fields = array(
	amber_f_text( 'erp_faq_eyebrow', __( 'Eyebrow', 'amber' ), array( 'default_value' => __( 'FAQs', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
	amber_f_text( 'erp_faq_heading', __( 'Heading', 'amber' ), array( 'default_value' => __( 'Frequently Asked Questions', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
	amber_f_image( 'erp_faq_avatar', __( 'Call card — photo', 'amber' ), array( 'instructions' => __( 'Square headshot for the "book a call" card. Leave empty for a branded icon.', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_text( 'erp_faq_card_title', __( 'Call card — title', 'amber' ), array( 'default_value' => __( 'Book a 15 min call', 'amber' ), 'wrapper' => array( 'width' => '25' ) ) ),
	amber_f_textarea( 'erp_faq_card_text', __( 'Call card — text', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'Still have questions about Amber ERP Software? Book a free 15-minute call with our product specialists before you decide.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
	amber_f_text( 'erp_faq_card_btn_label', __( 'Call card — button label', 'amber' ), array( 'default_value' => __( 'Book a Free Call', 'amber' ), 'wrapper' => array( 'width' => '10' ) ) ),
	amber_f_text( 'erp_faq_card_btn_url', __( 'Call card — button URL', 'amber' ), array( 'default_value' => '#demo', 'wrapper' => array( 'width' => '10' ) ) ),
);

foreach ( amber_erp_faq_defaults() as $amber_i => $amber_d ) {
	/* translators: %d: FAQ number. */
	$amber_erp_faq_fields[] = amber_f_text( "erp_faq{$amber_i}_q", sprintf( __( 'Q%d — question', 'amber' ), $amber_i ), array( 'default_value' => $amber_d['q'], 'wrapper' => array( 'width' => '35' ) ) );
	$amber_erp_faq_fields[] = amber_f_textarea( "erp_faq{$amber_i}_a", sprintf( __( 'Q%d — answer', 'amber' ), $amber_i ), array( 'rows' => 2, 'default_value' => $amber_d['a'], 'wrapper' => array( 'width' => '65' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_erp_faq',
		'title'           => __( 'Amber — ERP FAQ', 'amber' ),
		'menu_order'      => 6,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The FAQ accordion on the Amber ERP Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_product_pages_location,
		'fields'          => $amber_erp_faq_fields,
	)
);

/* =========================================================================
 * Group J — Amber Laundry Software Feature Sections ("Point of Sale" / "Marketing" — the
 * interactive click-to-expand list + crossfading image panel).
 *
 * Only the Amber Laundry Software page renders these (template-parts/erp/amber-erp-page.php,
 * $amber_render_gz_feature_section()), so this group is scoped to the Amber Laundry Software
 * template only — not the shared $amber_product_pages_location used by the
 * other ERP-page groups above, which would show it on ERP/Amber Restaurant Software/POS too
 * where it has no effect.
 *
 * Field names follow erp_gzf_{slug}_* / erp_gzf_{slug}{n}_* and are read by
 * amber_sub() inside that same closure. Each section's items are numbered
 * slots (ACF Free has no Repeater) — same convention as erp_ft{n}_* above.
 * ====================================================================== */
$amber_gozzbe_features_location = array(
	array(
		array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-gozzbe.php' ),
	),
);

/* =========================================================================
 * Group J0 — Amber Laundry Software Hero (centred banner + intro animation + 7 product-
 * highlight tabs, template-parts/erp/amber-erp-page.php's `gz-hero` branch).
 *
 * Only Amber Laundry Software uses this hero layout (every other product page keeps the
 * shared full-bleed background-image banner in "Amber — ERP Page" above), so
 * this group is scoped to the Amber Laundry Software template only, like Group J below.
 *
 * The 7 tabs reuse the same labels as erp_ft1_label..erp_ft7_label (Amber —
 * ERP Features group) by design — see the template's $gz_tabs build — so
 * only their images are exposed here; the intro video and each tab's image
 * fall back to the bundled defaults when left empty.
 * ====================================================================== */
$amber_gz_hero_tabs = array(
	'pos'       => __( 'Tab 1 image', 'amber' ),
	'workflows' => __( 'Tab 2 image', 'amber' ),
	'pickup'    => __( 'Tab 3 image', 'amber' ),
	'website'   => __( 'Tab 4 image', 'amber' ),
	'marketing' => __( 'Tab 5 image', 'amber' ),
	'reporting' => __( 'Tab 6 image', 'amber' ),
	'plant'     => __( 'Tab 7 image', 'amber' ),
);

$amber_gz_hero_fields = array(
	amber_f_textarea( 'erp_gz_heading', __( 'Headline', 'amber' ), array( 'rows' => 2, 'default_value' => __( "Launch, Manage and Scale Your Laundry\nfrom One Platform", 'amber' ), 'instructions' => __( 'A line break here forces the two-line split; otherwise it breaks automatically near the midpoint.', 'amber' ) ) ),
	amber_f_textarea( 'erp_gz_lead', __( 'Lead paragraph', 'amber' ), array( 'rows' => 3, 'default_value' => __( 'Amber Laundry Software is your all-in-one solution for managing and scaling your laundromat, dry cleaning, shoe repair and alterations business. Experience seamless operations with our advanced tools designed just for you.', 'amber' ) ) ),
	amber_f( 'file', 'erp_gz_intro_video', __( 'Intro animation (video)', 'amber' ), array( 'return_format' => 'array', 'mime_types' => 'webm,mp4', 'instructions' => __( 'Short muted clip that plays once on first load, before the headline reveals. Leave empty for the bundled laundry-basket animation.', 'amber' ) ) ),
);
foreach ( $amber_gz_hero_tabs as $amber_tab_id => $amber_tab_label ) {
	$amber_gz_hero_fields[] = amber_f_image( "erp_gz_tab_{$amber_tab_id}_image", $amber_tab_label, array( 'instructions' => __( 'Shown in this tab\'s panel. Leave empty for the placeholder graphic.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) );
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_gozzbe_hero',
		'title'           => __( 'Amber Laundry Software — Hero', 'amber' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The centred hero banner at the top of the Amber Laundry Software page — headline, lead paragraph, intro animation and the 7 product-highlight tab images (ACF Free). Tab labels are shared with "Amber — ERP Features" → Card 1–7. Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_gozzbe_features_location,
		'fields'          => $amber_gz_hero_fields,
	)
);

/**
 * Build the numbered item fields (title/description/image) for one Amber Laundry Software
 * feature section, mirroring the $items_default array passed to
 * $amber_render_gz_feature_section() in the template.
 *
 * @param string $slug     Section slug (matches the erp_gzf_{slug}* field prefix).
 * @param array  $defaults List of ['title' => ..., 'desc' => ...] in display order.
 * @return array ACF field arrays.
 */
function amber_gzf_item_fields( $slug, $defaults ) {
	$fields = array();
	foreach ( $defaults as $amber_i => $amber_d ) {
		$amber_n = $amber_i + 1;
		/* translators: 1: item number, 2: item title, used as a tab label, e.g. "3 — Payments". */
		$fields[] = amber_f( 'tab', "tab_gzf_{$slug}{$amber_n}", sprintf( __( '%1$d — %2$s', 'amber' ), $amber_n, $amber_d['title'] ) );
		$fields[] = amber_f_text( "erp_gzf_{$slug}{$amber_n}_title", __( 'Title', 'amber' ), array( 'default_value' => $amber_d['title'], 'instructions' => __( 'Leave blank to hide this row.', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) );
		$fields[] = amber_f_textarea( "erp_gzf_{$slug}{$amber_n}_desc", __( 'Description', 'amber' ), array( 'rows' => 2, 'default_value' => $amber_d['desc'], 'wrapper' => array( 'width' => '70' ) ) );
		$fields[] = amber_f_image( "erp_gzf_{$slug}{$amber_n}_image", __( 'Screenshot', 'amber' ), array( 'instructions' => __( 'Shown in the right-hand panel when this row is active (and inline on mobile). Leave empty for the placeholder graphic.', 'amber' ) ) );
	}
	return $fields;
}

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_gozzbe_pos',
		'title'           => __( 'Amber Laundry Software — Point of Sale Section', 'amber' ),
		'menu_order'      => 7,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Point of Sale" interactive feature list on the Amber Laundry Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_gozzbe_features_location,
		'fields'          => array_merge(
			array(
				amber_f_text( 'erp_gzf_pos_heading', __( 'Section heading', 'amber' ), array( 'default_value' => __( 'Point of Sale', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_textarea( 'erp_gzf_pos_sub', __( 'Section intro paragraph', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'The heartbeat of your front counter, simplified. Handle every transaction with a system built for speed, accuracy, and ease — so you can focus on your customers, not your screen.', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
			),
			amber_gzf_item_fields(
				'pos',
				array(
					array( 'title' => __( 'Easy To Use', 'amber' ), 'desc' => __( 'A sleek, intuitive interface lets you find items and add notes in a few taps, making every transaction easy and accurate.', 'amber' ) ),
					array( 'title' => __( 'Detailing', 'amber' ), 'desc' => __( 'Capture every detail, from a missing button to a special detergent, to specific notes and custom care instructions.', 'amber' ) ),
					array( 'title' => __( 'Payments', 'amber' ), 'desc' => __( 'Our fully integrated solution lets you accept online, in-store, and in-app payments, with low fees that put more profit in your pocket.', 'amber' ) ),
					array( 'title' => __( 'Digital Waivers', 'amber' ), 'desc' => __( 'Go paperless with integrated digital waivers. Customers can sign on-screen, and waivers are automatically saved to their profile.', 'amber' ) ),
					array( 'title' => __( 'Automated Communication', 'amber' ), 'desc' => __( 'Automatically sends updates via SMS, Email, Push & WhatsApp when their order is ready, and send automatic reminders too.', 'amber' ) ),
					array( 'title' => __( 'First Order Identifier', 'amber' ), 'desc' => __( "A yellow '1st Order' icon lets your team know it's a customer's first order, and helps them give the best first impression.", 'amber' ) ),
				)
			)
		),
	)
);

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_gozzbe_marketing',
		'title'           => __( 'Amber Laundry Software — Marketing Section', 'amber' ),
		'menu_order'      => 8,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Marketing" interactive feature list on the Amber Laundry Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_gozzbe_features_location,
		'fields'          => array_merge(
			array(
				amber_f_text( 'erp_gzf_marketing_heading', __( 'Section heading', 'amber' ), array( 'default_value' => __( 'Marketing', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_textarea( 'erp_gzf_marketing_sub', __( 'Section intro paragraph', 'amber' ), array( 'rows' => 2, 'default_value' => __( 'All the tools you need to bring in new customers, keep them coming back, and grow smarter — from one platform, so you can build your brand with confidence and automate your growth from day one.', 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
			),
			amber_gzf_item_fields(
				'marketing',
				array(
					array( 'title' => __( 'Website Builder', 'amber' ), 'desc' => __( 'Build and customize a site that looks beautiful on any device, and is fully integrated with your POS.', 'amber' ) ),
					array( 'title' => __( 'Google Business Profile', 'amber' ), 'desc' => __( 'A "Book Now" button on your profile lets customers book directly, so you can turn searches into sales.', 'amber' ) ),
					array( 'title' => __( 'Email, SMS & Push Marketing', 'amber' ), 'desc' => __( 'Send professional emails and targeted messages to key sections of your customer list, ensuring your updates are always seen.', 'amber' ) ),
					array( 'title' => __( 'Automated Marketing Flows and Builder', 'amber' ), 'desc' => __( 'Set up automated flows that engage customers, increase orders, and drive revenue while you focus on your business.', 'amber' ) ),
					array( 'title' => __( '5-Star Customer Reviews', 'amber' ), 'desc' => __( 'Automatically request reviews from your customers on Google, Yelp, Facebook and Nextdoor.', 'amber' ) ),
					array( 'title' => __( 'Ai Agent', 'amber' ), 'desc' => __( "Embed our AI Agent on your website, and handle customer questions 24/7 using your store's information.", 'amber' ) ),
				)
			)
		),
	)
);

acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_gozzbe_workflows',
		'title'           => __( 'Amber Laundry Software — Workflows Section', 'amber' ),
		'menu_order'      => 9,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'The "Workflows" interactive feature list on the Amber Laundry Software page (ACF Free). Leave a field blank to use the built-in demo copy.', 'amber' ),
		'location'        => $amber_gozzbe_features_location,
		'fields'          => array_merge(
			array(
				amber_f_text( 'erp_gzf_workflows_heading', __( 'Section heading', 'amber' ), array( 'default_value' => __( 'Workflows', 'amber' ), 'wrapper' => array( 'width' => '30' ) ) ),
				amber_f_textarea( 'erp_gzf_workflows_sub', __( 'Section intro paragraph', 'amber' ), array( 'rows' => 2, 'default_value' => __( "Whether you're running one store or managing a multi-location operation, stay in control. Assign orders, track progress, manage staff, and handle payments — all from one dashboard.", 'amber' ), 'wrapper' => array( 'width' => '70' ) ) ),
			),
			amber_gzf_item_fields(
				'workflows',
				array(
					array( 'title' => __( 'Wash and Fold Tracking', 'amber' ), 'desc' => __( 'Assign orders to washers and dryers and track activity in real time — so every load stays on schedule.', 'amber' ) ),
					array( 'title' => __( 'Auto and Assisted Assembly', 'amber' ), 'desc' => __( "Connect with MetalProgetti and QuickSort to automate your flow, whether you're managing a single store or full-scale plant.", 'amber' ) ),
					array( 'title' => __( 'Lockers', 'amber' ), 'desc' => __( 'Choose one of our locker partners to automate the workflow between your lockers and Amber Laundry Software.', 'amber' ) ),
					array( 'title' => __( 'Barcode Tagging', 'amber' ), 'desc' => __( 'Scan barcode labels and instantly assign garments to the right rack — no tags, no manual sorting.', 'amber' ) ),
					array( 'title' => __( 'Washer/Dryer Payment Integrations', 'amber' ), 'desc' => __( 'The systems you rely on are connected — so you can move faster and stay focused on the job.', 'amber' ) ),
					array( 'title' => __( 'Multi-Store Plant', 'amber' ), 'desc' => __( 'Barcode-powered tracking, real-time status updates, and customizable workflows built for how you actually work.', 'amber' ) ),
				)
			)
		),
	)
);

/* =========================================================================
 * Group B — Site Settings (front page only)
 * ====================================================================== */
acf_add_local_field_group(
	array(
		'key'             => 'group_amber_free_settings',
		'title'           => __( 'Amber — Site Settings', 'amber' ),
		'menu_order'      => 1,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => __( 'Global header / footer / contact settings (ACF Free). Edited here on the front page, applied site-wide.', 'amber' ),
		'location'        => array(
			array(
				array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ),
			),
		),
		'fields'          => array(

			/* ---- Header / Branding ---- */
			amber_f( 'tab', 'tab_free_header', __( 'Header', 'amber' ) ),
			amber_f_link( 'header_cta', __( 'Header Button (CTA)', 'amber' ), array( 'instructions' => __( 'The “Let’s Talk” button.', 'amber' ) ) ),
			amber_f_link( 'header_cta_secondary', __( 'Header Secondary Link', 'amber' ) ),
			amber_f_truefalse( 'announcement_enabled', __( 'Show Announcement Bar', 'amber' ), array( 'default_value' => 0 ) ),
			amber_f_text( 'announcement_text', __( 'Announcement Text', 'amber' ), array( 'conditional_logic' => array( array( array( 'field' => amber_fk( 'announcement_enabled' ), 'operator' => '==', 'value' => '1' ) ) ) ) ),
			amber_f_link( 'announcement_link', __( 'Announcement Link', 'amber' ), array( 'conditional_logic' => array( array( array( 'field' => amber_fk( 'announcement_enabled' ), 'operator' => '==', 'value' => '1' ) ) ) ) ),

			amber_f( 'tab', 'tab_free_brand', __( 'Branding', 'amber' ) ),
			amber_f_image( 'logo', __( 'Logo', 'amber' ), array( 'instructions' => __( 'Leave empty to use the built-in Amber Technologies lockup.', 'amber' ) ) ),
			amber_f_image( 'favicon', __( 'Favicon', 'amber' ) ),
			amber_f_image( 'default_social_image', __( 'Default Social Share Image', 'amber' ) ),
			amber_f_color( 'brand_color', __( 'Brand Accent Color', 'amber' ), array( 'default_value' => '#0ea5e9' ) ),

			/* ---- Footer ---- */
			amber_f( 'tab', 'tab_free_footer', __( 'Footer', 'amber' ) ),
			amber_f_image( 'footer_logo', __( 'Footer Logo', 'amber' ) ),
			amber_f_textarea( 'footer_description', __( 'Footer Description', 'amber' ) ),
			amber_f_text( 'footer_services_heading', __( 'Services Column Heading', 'amber' ), array( 'default_value' => 'Services', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'footer_quick_heading', __( 'Company Column Heading', 'amber' ), array( 'default_value' => 'Company', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_truefalse( 'newsletter_enabled', __( 'Show Newsletter Signup', 'amber' ), array( 'default_value' => 1 ) ),
			amber_f_text( 'newsletter_heading', __( 'Newsletter Heading', 'amber' ), array( 'default_value' => 'Stay in the loop', 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'newsletter_text', __( 'Newsletter Subtext', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'copyright', __( 'Copyright Text', 'amber' ), array( 'instructions' => __( 'Use %year% for the current year.', 'amber' ), 'default_value' => '© %year% Amber Technologies. All rights reserved.' ) ),

			/* ---- Contact & Social ---- */
			amber_f( 'tab', 'tab_free_contact', __( 'Contact & Social', 'amber' ) ),
			amber_f_text( 'contact_phone', __( 'Phone', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_email', __( 'Email', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_textarea( 'contact_address', __( 'Address', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_hours', __( 'Business Hours', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f_text( 'contact_whatsapp', __( 'WhatsApp Number', 'amber' ), array( 'instructions' => __( 'Digits only, with country code, e.g. 919544931055.', 'amber' ), 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_twitter', __( 'X / Twitter URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_linkedin', __( 'LinkedIn URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_facebook', __( 'Facebook URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_instagram', __( 'Instagram URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_youtube', __( 'YouTube URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
			amber_f( 'url', 'social_github', __( 'GitHub URL', 'amber' ), array( 'wrapper' => array( 'width' => '50' ) ) ),
		),
	)
);
