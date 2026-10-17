<?php
/**
 * Template Name: Full Width (Section Builder)
 *
 * A no-chrome canvas page that renders only the ACF Section Builder. Ideal for
 * the About, Services and Contact pages where you stack reusable sections.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();
	amber_render_sections();
endwhile;

get_footer();
