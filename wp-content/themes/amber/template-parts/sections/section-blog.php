<?php
/**
 * Section: Blog Preview.
 *
 * Queries the latest posts and reuses the post-card component.
 *
 * @package Amber
 */

$count = (int) amber_sub( 'blog_count', 3 );
$count = max( 1, min( 6, $count ) );
$btn   = amber_sub( 'blog_btn', array( 'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ), 'title' => __( 'View all articles', 'amber' ) ) );

$blog_q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<section <?php echo amber_section_attrs( 'blog', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-blog>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'blog_eyebrow', __( 'Blog', 'amber' ) ),
				'heading'    => amber_sub( 'blog_heading', __( 'Insights & updates', 'amber' ) ),
				'subheading' => amber_sub( 'blog_subheading', __( 'Product news, engineering deep-dives and lessons from the field.', 'amber' ) ),
				'align'      => amber_sub( 'blog_align', 'center' ),
			)
		);
		?>

		<?php if ( $blog_q->have_posts() ) : ?>
			<div class="posts-grid">
				<?php
				while ( $blog_q->have_posts() ) :
					$blog_q->the_post();
					get_template_part( 'template-parts/components/card', 'post' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p class="blog-preview__empty"><?php esc_html_e( 'Your latest posts will appear here once you publish them.', 'amber' ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $btn['url'] ) ) : ?>
			<div class="blog-preview__cta"><?php amber_button( $btn, 'secondary', array( 'icon' => true ) ); ?></div>
		<?php endif; ?>
	</div>
</section>
