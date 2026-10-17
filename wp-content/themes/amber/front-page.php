<?php
/**
 * Front page template.
 *
 * The homepage is composed entirely from the ACF Section Builder. With no data
 * (or ACF Free) it renders the curated demo composition from page-builder.php,
 * so the site looks complete out of the box.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();
	amber_render_sections();
endwhile;

get_footer();
