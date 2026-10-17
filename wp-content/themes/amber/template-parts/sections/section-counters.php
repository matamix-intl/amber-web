<?php
/**
 * Section: Stats Counters — centered heading over blue-gradient numbers.
 *
 * Adapted from the Pixbit stat strip, in Amber's blue (no gold). Four fixed
 * slots, each editable in ACF Free (value + label) with premium defaults.
 *
 * @package Amber
 */

$counters_heading   = amber_sub( 'counters_heading', __( 'Powering the Restaurant Businesses Operation in', 'amber' ) );
$counters_highlight = amber_sub( 'counters_heading_highlight', __( 'Over 50 Cities', 'amber' ) );

$items = array(
	array( 'num' => amber_sub( 'counter1_num', '2500+' ), 'label' => amber_sub( 'counter1_label', __( 'Restaurants Relies On software', 'amber' ) ) ),
	array( 'num' => amber_sub( 'counter2_num', '83%' ), 'label' => amber_sub( 'counter2_label', __( 'Successful Restaurant Management Ratio Over', 'amber' ) ) ),
	array( 'num' => amber_sub( 'counter3_num', '5 Years' ), 'label' => amber_sub( 'counter3_label', __( 'A amber software Come In the Market Over', 'amber' ) ) ),
);
?>
<section class="section section--counters is-bg-surface is-space-small" data-counters>
	<div class="container">
		<?php if ( $counters_heading || $counters_highlight ) : ?>
			<h2 class="counters__heading" data-animate="fade-up">
				<?php echo esc_html( $counters_heading ); ?>
				<?php if ( $counters_highlight ) : ?>
					<span class="counters__highlight"><?php echo esc_html( $counters_highlight ); ?></span>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<div class="counters">
			<?php foreach ( $items as $i => $item ) : ?>
				<div class="counter" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<span class="counter__num"><?php echo esc_html( $item['num'] ); ?></span>
					<span class="counter__label"><?php echo esc_html( $item['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
