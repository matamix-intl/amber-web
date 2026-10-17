<?php
/**
 * Component: Blog post card.
 *
 * Used by the blog index, archives, search and the homepage Blog Preview.
 *
 * @package Amber
 */

?>
<article <?php post_class( 'post-card' ); ?> data-animate="fade-up">
	<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'amber-card', array( 'class' => 'post-card__image' ) );
		} else {
			amber_image( '', 'amber-card', array( 'class' => 'post-card__image' ) );
		}
		?>
	</a>
	<div class="post-card__body">
		<?php
		$amber_cats = get_the_category();
		if ( $amber_cats ) :
			?>
			<p class="post-card__cat"><?php echo esc_html( $amber_cats[0]->name ); ?></p>
		<?php endif; ?>

		<h3 class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>

		<div class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span aria-hidden="true">·</span>
			<span><?php echo esc_html( amber_reading_time() ); ?></span>
		</div>
	</div>
</article>
