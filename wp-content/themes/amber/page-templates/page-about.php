<?php
/**
 * Template Name: About Us
 *
 * A fully designed About page whose every string, image, stat and button is
 * editable from the admin (ACF Free) via the "Amber — About Page" field group
 * defined in inc/acf/fields-free.php. Like the section parts, it reads all
 * values through amber_sub() so it shows premium defaults out of the box and
 * live content once the fields are filled in.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/about/about-page' );
endwhile;

get_footer();
