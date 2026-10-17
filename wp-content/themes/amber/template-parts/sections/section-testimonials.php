<?php
/**
 * Section: Testimonials — "Reviews" centered auto-playing slider.
 *
 * Centered white "Reviews" badge + heading, then a full-bleed card slider:
 * light-grey quote cards with the CENTERED card highlighted dark (near-black
 * with a soft pink/violet gradient glow in its corner). Autoplays and loops
 * via modules/reviews-slider.js (clones the slide set on both sides, slides
 * one card at a time, pauses on hover, disabled under reduced motion —
 * where it falls back to a static row).
 *
 * @package Amber
 */

$eyebrow = amber_sub( 'testi_eyebrow', __( 'Reviews', 'amber' ) );
$heading = amber_sub( 'testi_heading', __( 'Hear from our customers about their experiences with us', 'amber' ) );

// Live sub-fields (Pro flexible-content row) fall back to the numbered ACF
// Free fields (testi{n}_*), which in turn fall back to the shared demo
// defaults — see amber_home_testimonials_defaults() in inc/helpers.php.
$testi_row = amber_sub( 'testi_items', null );
if ( is_array( $testi_row ) ) {
	$items = $testi_row;
} else {
	$items = array();
	foreach ( amber_home_testimonials_defaults() as $i => $d ) {
		$items[] = array(
			'quote'  => amber_sub( "testi{$i}_quote", $d['quote'] ),
			'name'   => amber_sub( "testi{$i}_name", $d['name'] ),
			'role'   => amber_sub( "testi{$i}_role", $d['role'] ),
			'avatar' => amber_sub( "testi{$i}_avatar", '' ),
		);
	}
}
?>
<section class="section section--testi reviews-sec" data-reviews>
	<div class="container">
		<div class="reviews-sec__header">
			<span class="reviews-sec__badge"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="reviews-sec__title"><?php echo esc_html( $heading ); ?></h2>
		</div>
	</div>

	<div class="reviews-sec__viewport" data-reviews-viewport>
		<div class="reviews-sec__track" data-reviews-track>
			<?php foreach ( (array) $items as $item ) : ?>
				<article class="review-card">
					<span class="review-card__glow" aria-hidden="true"></span>

					<figure class="review-card__avatar">
						<?php
						if ( ! empty( $item['avatar'] ) ) {
							amber_image( $item['avatar'], 'amber-square', array( 'alt' => $item['name'] ?? '' ) );
						} else {
							echo '<span class="review-card__initial" aria-hidden="true">' . esc_html( mb_substr( $item['name'] ?? 'A', 0, 1 ) ) . '</span>';
						}
						?>
					</figure>

					<blockquote class="review-card__quote"><?php echo esc_html( $item['quote'] ?? '' ); ?></blockquote>

					<div class="review-card__meta">
						<p class="review-card__name"><?php echo esc_html( $item['name'] ?? '' ); ?></p>
						<?php if ( ! empty( $item['role'] ) ) : ?>
							<p class="review-card__role"><?php echo esc_html( $item['role'] ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
