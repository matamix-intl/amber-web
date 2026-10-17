<?php
/**
 * Default page template.
 *
 * If the page uses the Section Builder, render those sections. Otherwise fall
 * back to a clean title + Gutenberg content layout (so standard pages and
 * block-editor content still look great).
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();

	$amber_has_builder = function_exists( 'have_rows' ) && have_rows( 'sections' );

	if ( $amber_has_builder ) {
		amber_render_sections();
	} else {
		?>
		<article <?php post_class( 'page-article' ); ?>>
			<header class="page-hero is-bg-surface">
				<div class="container container--narrow">
					<?php amber_breadcrumbs(); ?>
					<h1 class="page-hero__title"><?php the_title(); ?></h1>
				</div>
			</header>

			<div class="section is-space-normal">
				<div class="container container--narrow entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'amber' ) . '">' . esc_html__( 'Pages:', 'amber' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</div>
		</article>
		<?php
	}

endwhile;

get_footer();
