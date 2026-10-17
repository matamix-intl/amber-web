<?php
/**
 * Section: Statistics Counter.
 *
 * Numbers animate up on scroll (see counters.js, driven by the data-counter
 * attribute). Respects prefers-reduced-motion by rendering the final value.
 *
 * @package Amber
 */

$stats = amber_sub(
	'stats_items',
	array(
		array( 'value' => 5000, 'suffix' => '+', 'label' => __( 'Businesses', 'amber' ) ),
		array( 'value' => 200000, 'suffix' => '+', 'label' => __( 'Daily Transactions', 'amber' ) ),
		array( 'value' => 50, 'suffix' => '+', 'label' => __( 'Cities', 'amber' ) ),
		array( 'value' => 99.9, 'suffix' => '%', 'label' => __( 'System Reliability', 'amber' ) ),
	)
);
?>
<section <?php echo amber_section_attrs( 'stats', 'ink' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-stats>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'stats_eyebrow', '' ),
				'heading'    => amber_sub( 'stats_heading', __( 'Numbers teams trust', 'amber' ) ),
				'subheading' => amber_sub( 'stats_subheading', '' ),
				'align'      => amber_sub( 'stats_align', 'center' ),
			)
		);
		?>

		<ul class="stats-grid">
			<?php foreach ( (array) $stats as $i => $stat ) : ?>
				<li class="stat" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<p class="stat__value">
						<?php if ( ! empty( $stat['prefix'] ) ) : ?><span class="stat__prefix"><?php echo esc_html( $stat['prefix'] ); ?></span><?php endif; ?>
						<span class="stat__number" data-counter data-counter-to="<?php echo esc_attr( $stat['value'] ?? 0 ); ?>"><?php echo esc_html( $stat['value'] ?? 0 ); ?></span>
						<?php if ( ! empty( $stat['suffix'] ) ) : ?><span class="stat__suffix"><?php echo esc_html( $stat['suffix'] ); ?></span><?php endif; ?>
					</p>
					<p class="stat__label"><?php echo esc_html( $stat['label'] ?? '' ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
