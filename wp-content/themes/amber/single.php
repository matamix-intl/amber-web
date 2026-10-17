<?php
/**
 * Single post / single CPT template.
 *
 * Articles get a centred reading column + optional sidebar. Single Service /
 * Testimonial CPT entries reuse the same shell with their featured image.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'single-post' ); ?>>

		<header class="single-post__hero is-bg-surface">
			<div class="container container--narrow">
				<?php amber_breadcrumbs(); ?>
				<?php
				$amber_cats = get_the_category();
				if ( $amber_cats && 'post' === get_post_type() ) :
					?>
					<p class="single-post__cat"><a href="<?php echo esc_url( get_category_link( $amber_cats[0]->term_id ) ); ?>"><?php echo esc_html( $amber_cats[0]->name ); ?></a></p>
				<?php endif; ?>
				<h1 class="single-post__title"><?php the_title(); ?></h1>
				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="single-post__meta">
						<span><?php echo esc_html( get_the_author() ); ?></span>
						<span aria-hidden="true">·</span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<span aria-hidden="true">·</span>
						<span><?php echo esc_html( amber_reading_time() ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-post__media container">
				<?php the_post_thumbnail( 'amber-wide', array( 'class' => 'single-post__image', 'loading' => 'eager' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="section is-space-normal">
			<div class="container single-post__layout <?php echo is_active_sidebar( 'sidebar-blog' ) ? 'single-post__layout--has-sidebar' : ''; ?>">
				<div class="entry-content single-post__content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'amber' ),
							'after'  => '</nav>',
						)
					);

					$amber_tags = get_the_tag_list( '<ul class="entry-tags"><li>', '</li><li>', '</li></ul>' );
					if ( $amber_tags ) {
						echo wp_kses_post( $amber_tags );
					}
					?>
				</div>

				<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
					<aside class="single-post__sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'amber' ); ?>">
						<?php dynamic_sidebar( 'sidebar-blog' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>

		<?php
		// Author + post navigation.
		?>
		<div class="container container--narrow">
			<nav class="post-nav" aria-label="<?php esc_attr_e( 'Post', 'amber' ); ?>">
				<div class="post-nav__prev"><?php previous_post_link( '%link', '&larr; %title' ); ?></div>
				<div class="post-nav__next"><?php next_post_link( '%link', '%title &rarr;' ); ?></div>
			</nav>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
