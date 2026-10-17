<?php
/**
 * Archive template (categories, tags, CPT archives, dates, authors).
 *
 * @package Amber
 */

get_header();
?>

<header class="page-hero is-bg-surface">
	<div class="container">
		<?php amber_breadcrumbs(); ?>
		<h1 class="page-hero__title"><?php the_archive_title(); ?></h1>
		<?php
		$amber_desc = get_the_archive_description();
		if ( $amber_desc ) {
			echo '<div class="page-hero__lead">' . wp_kses_post( $amber_desc ) . '</div>';
		}
		?>
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
