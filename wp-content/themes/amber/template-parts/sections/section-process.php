<?php
/**
 * Section: Process Timeline.
 *
 * @package Amber
 */

$steps = amber_sub(
	'process_steps',
	array(
		array( 'title' => __( 'Discover', 'amber' ), 'text' => __( 'We map your goals, users and constraints to a sharp, shared brief.', 'amber' ) ),
		array( 'title' => __( 'Design', 'amber' ), 'text' => __( 'Rapid prototypes and design reviews converge on a confident direction.', 'amber' ) ),
		array( 'title' => __( 'Build', 'amber' ), 'text' => __( 'Iterative delivery with demos every sprint — no big-bang surprises.', 'amber' ) ),
		array( 'title' => __( 'Launch & scale', 'amber' ), 'text' => __( 'We ship, measure and optimise so momentum keeps compounding.', 'amber' ) ),
	)
);
?>
<section <?php echo amber_section_attrs( 'process', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-process>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'process_eyebrow', __( 'How it works', 'amber' ) ),
				'heading'    => amber_sub( 'process_heading', __( 'A clear path from idea to impact', 'amber' ) ),
				'subheading' => amber_sub( 'process_subheading', __( 'A proven, transparent process that keeps everyone aligned.', 'amber' ) ),
				'align'      => amber_sub( 'process_align', 'center' ),
			)
		);
		?>

		<ol class="timeline">
			<?php foreach ( (array) $steps as $i => $step ) : ?>
				<li class="timeline__step" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<div class="timeline__marker" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></div>
					<div class="timeline__body">
						<h3 class="timeline__title"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
						<p class="timeline__text"><?php echo esc_html( $step['text'] ?? '' ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
