<?php
/**
 * Main fallback template (blog index).
 *
 * WordPress falls back to this when a more specific template is absent. It
 * powers the posts page and any unmatched query.
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
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				esc_html_e( 'Latest articles', 'amber' );
			}
			?>
		</h1>
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
