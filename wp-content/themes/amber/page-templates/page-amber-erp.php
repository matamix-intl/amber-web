<?php
/**
 * Template Name: Amber ERP Software
 *
 * A dedicated service page for the Amber ERP Software product. Its hero banner mirrors
 * the two-column "automated tracking" reference (editorial copy + CTA buttons on
 * the left, a product dashboard with floating stat badges on the right) but is
 * styled in the Amber sky-blue design system.
 *
 * Every string, image and stat reads through amber_sub() so the page shows
 * premium defaults out of the box and live content once the "Amber — ERP Page"
 * fields are filled in.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/erp/amber-erp-page' );
endwhile;

get_footer();
