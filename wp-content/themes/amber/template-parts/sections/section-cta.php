<?php
/**
 * Section: CTA Banner — centered light-blue panel with navy outline shapes.
 *
 * Pixel-matched to a reference "pricing CTA" band: soft gradient wash,
 * centered heading/subtext/button, a triangle outline pinned top-left and a
 * diamond outline pinned right, both floating at the section's own corners
 * rather than inside the copy column. Re-tinted to the Amber sky-blue brand
 * with navy (--amber-ink) text and shapes instead of the reference's peach/
 * orange palette.
 *
 * @package Amber
 */

$heading = amber_sub( 'cta_heading', __( 'Ready to Transform Your Business?', 'amber' ) );
$text    = amber_sub( 'cta_text', __( 'Join thousands of businesses already growing with Amber Technologies.', 'amber' ) );
$primary = amber_sub( 'cta_primary', array( 'title' => __( 'Book a Demo', 'amber' ), 'url' => home_url( '/contact-us/' ), 'target' => '' ) );
?>
<section class="section section--cta cta-banner" data-cta>
	<svg class="cta-banner__deco cta-banner__deco--tri" viewBox="0 0 44 40" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round" aria-hidden="true">
		<path d="M22 5 39 35H5L22 5Z"></path>
	</svg>
	<svg class="cta-banner__deco cta-banner__deco--diamond" viewBox="0 0 44 44" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round" aria-hidden="true">
		<rect x="10" y="10" width="24" height="24" transform="rotate(20 22 22)"></rect>
	</svg>

	<div class="container">
		<div class="cta-banner__inner" data-animate="scale-in">
			<h2 class="cta-banner__title"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $text ) : ?>
				<p class="cta-banner__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php amber_button( $primary, 'cta-navy', array( 'class' => 'btn--lg cta-banner__btn' ) ); ?>
		</div>
	</div>
</section>
