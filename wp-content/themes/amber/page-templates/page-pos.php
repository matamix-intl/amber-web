<?php
/**
 * Template Name: Amber POS Software
 *
 * Amber POS Software product page. Reuses the shared product-page body
 * (template-parts/erp/amber-erp-page.php) with POS content defaults; the
 * yellow theme comes from the page-template body class (see the product
 * theme mixin in assets/scss/sections/_erp.scss).
 *
 * @package Amber
 */

$GLOBALS['amber_erp_product'] = 'pos';

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/erp/amber-erp-page' );
endwhile;

get_footer();
