<?php
/**
 * Section: FAQ — "Got Questions? We've Got Answers".
 *
 * Light-blue band with a centered white "FAQ" badge and heading, and a
 * stack of white rounded accordion cards (first one open). Accessible
 * disclosure pattern (button[aria-expanded] controlling a region) driven by
 * modules/accordion.js. Also emits FAQPage JSON-LD for rich results.
 *
 * @package Amber
 */

$eyebrow = amber_sub( 'faq_eyebrow', __( 'FAQ', 'amber' ) );
$heading = amber_sub( 'faq_heading', __( 'Got Questions? We’ve Got Answers', 'amber' ) );

// Live sub-fields (Pro flexible-content row) fall back to the numbered ACF
// Free fields (home_faq{n}_*), which in turn fall back to the shared demo
// defaults — see amber_home_faq_defaults() in inc/helpers.php.
$faq_row = amber_sub( 'faq_items', null );
if ( is_array( $faq_row ) ) {
	$items = $faq_row;
} else {
	$items = array();
	foreach ( amber_home_faq_defaults() as $i => $d ) {
		$items[] = array(
			'question' => amber_sub( "home_faq{$i}_q", $d['q'] ),
			'answer'   => amber_sub( "home_faq{$i}_a", $d['a'] ),
		);
	}
}

$faq_uid = amber_uid( 'faq' );
$schema  = array();
?>
<section class="section section--faq faq-sec" id="faq" data-faq>
	<div class="container">
		<div class="faq-sec__header">
			<span class="faq-sec__badge"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="faq-sec__title"><?php echo esc_html( $heading ); ?></h2>
		</div>

		<div class="accordion faq-sec__accordion" data-accordion>
			<?php
			foreach ( (array) $items as $i => $item ) :
				$btn_id   = $faq_uid . '-btn-' . $i;
				$panel_id = $faq_uid . '-panel-' . $i;
				$answer   = $item['answer'] ?? '';
				$schema[] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item['question'] ?? '' ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $answer ),
					),
				);
				?>
				<div class="accordion__item<?php echo 0 === $i ? ' is-open' : ''; ?>" data-animate="fade-up">
					<h3 class="accordion__heading">
						<button class="accordion__trigger" id="<?php echo esc_attr( $btn_id ); ?>" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
							<span class="accordion__question"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
							<span class="accordion__icon" aria-hidden="true"><?php echo amber_icon_svg( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</button>
					</h3>
					<div class="accordion__panel" id="<?php echo esc_attr( $panel_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $btn_id ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
						<div class="accordion__answer"><?php echo wp_kses_post( wpautop( $answer ) ); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( ! empty( $schema ) ) : ?>
		<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $schema ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
	<?php endif; ?>
</section>
