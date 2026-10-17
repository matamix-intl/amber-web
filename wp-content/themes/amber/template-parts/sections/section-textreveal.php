<?php
/**
 * Section: Scroll-Fill Text Reveal.
 *
 * A large statement that fills in word-by-word (faded → solid) as the visitor
 * scrolls through the pinned section, with a "Learn More" button. Adapted from
 * the Pixbit text-blend effect; motion lives in text-reveal.js and is disabled
 * on mobile + for reduced motion.
 *
 * @package Amber
 */

$heading = amber_sub( 'textreveal_heading', __( 'Smart software for enterprise, retail, restaurants and laundry — one connected ecosystem you can trust.', 'amber' ) );
$button  = amber_sub( 'textreveal_button', array( 'url' => home_url( '/about-us/' ), 'title' => __( 'Learn More About Us', 'amber' ) ) );

$words = preg_split( '/\s+/', trim( wp_strip_all_tags( $heading ) ) );
?>
<section class="section--textreveal" data-textreveal>
	<div class="textreveal__sticky">
		<div class="container">
			<h2 class="textreveal__heading" aria-label="<?php echo esc_attr( $heading ); ?>">
				<?php
				foreach ( $words as $word ) :
					echo '<span class="textreveal__word">' . esc_html( $word ) . '</span> ';
				endforeach;
				?>
			</h2>

			<?php if ( ! empty( $button['url'] ) ) : ?>
				<div class="textreveal__cta">
					<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $button['url'] ); ?>"<?php echo ( ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
						<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : __( 'Learn More', 'amber' ) ); ?>
						<span class="btn__icon" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
