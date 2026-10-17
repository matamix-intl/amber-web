<?php
/**
 * Template Name: Amber Restaurant Software
 *
 * Amber Restaurant Software restaurant-management product page. Reuses the shared product-page
 * body (template-parts/erp/amber-erp-page.php) with Amber Restaurant Software content defaults;
 * the lime-green theme comes from the page-template body class (see the
 * product theme mixin in assets/scss/sections/_erp.scss).
 *
 * @package Amber
 */

$GLOBALS['amber_erp_product'] = 'zoomie';

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/erp/amber-erp-page' );
endwhile;

get_footer();
