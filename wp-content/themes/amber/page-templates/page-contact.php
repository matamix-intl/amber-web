<?php
/**
 * Template Name: Contact Us
 *
 * Dedicated Contact page: "Send us a message" form (Contact Form 7) beside a
 * "How to reach us" details panel, reusing the existing contact section markup
 * and styles (template-parts/sections/section-contact.php, _cta-contact.scss).
 *
 * @package Amber
 */

get_header();
?>
<div class="page-contact">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/sections/section-contact',
			null,
			array(
				'shortcode'     => '[contact-form-7 id="f851848" title="contact-us"]',
				'heading_level' => 'h1',
			)
		);
		get_template_part( 'template-parts/sections/section-locations' );
	endwhile;
	?>
</div>
<?php
get_footer();
