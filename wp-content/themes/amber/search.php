<?php
/**
 * Search results template.
 *
 * @package Amber
 */

get_header();
?>

<header class="page-hero is-bg-surface">
	<div class="container">
		<?php amber_breadcrumbs(); ?>
		<h1 class="page-hero__title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Results for “%s”', 'amber' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
		<p class="page-hero__lead">
			<?php
			global $wp_query;
			printf(
				/* translators: %d: number of results. */
				esc_html( _n( '%d result found.', '%d results found.', (int) $wp_query->found_posts, 'amber' ) ),
				(int) $wp_query->found_posts
			);
			?>
		</p>
		<?php get_search_form(); ?>
	</div>
</header>

<div class="section is-space-normal">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="posts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/components/card', 'post' );
				endwhile;
				?>
			</div>
			<?php amber_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/components/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
