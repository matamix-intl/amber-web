<?php
/**
 * Section: Product Showcase (alternating image/text rows).
 *
 * @package Amber
 */

$rows = amber_sub(
	'showcase_items',
	array(
		array(
			'eyebrow' => __( 'Collaborate', 'amber' ),
			'title'   => __( 'One workspace for the whole team', 'amber' ),
			'text'    => __( 'Bring docs, tasks and conversations together so context never gets lost between tools.', 'amber' ),
			'image'   => '',
			'link'    => array( 'url' => home_url( '/amber-erp/' ), 'title' => __( 'Explore collaboration', 'amber' ) ),
		),
		array(
			'eyebrow' => __( 'Automate', 'amber' ),
			'title'   => __( 'Let the busywork run itself', 'amber' ),
			'text'    => __( 'Powerful no-code automations handle the repetitive steps, so your team stays in flow.', 'amber' ),
			'image'   => '',
			'link'    => array( 'url' => home_url( '/pos-software/' ), 'title' => __( 'See automations', 'amber' ) ),
		),
	)
);
?>
<section <?php echo amber_section_attrs( 'showcase', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-showcase>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'showcase_eyebrow', __( 'Product', 'amber' ) ),
				'heading'    => amber_sub( 'showcase_heading', __( 'A closer look', 'amber' ) ),
				'subheading' => amber_sub( 'showcase_subheading', '' ),
				'align'      => amber_sub( 'showcase_align', 'center' ),
			)
		);
		?>

		<div class="showcase">
			<?php foreach ( (array) $rows as $i => $row ) : ?>
				<div class="showcase__row <?php echo ( $i % 2 ) ? 'is-reversed' : ''; ?>">
					<div class="showcase__text" data-animate="<?php echo ( $i % 2 ) ? 'fade-left' : 'fade-right'; ?>">
						<?php if ( ! empty( $row['eyebrow'] ) ) : ?>
							<p class="showcase__eyebrow"><?php echo esc_html( $row['eyebrow'] ); ?></p>
						<?php endif; ?>
						<h3 class="showcase__title"><?php echo esc_html( $row['title'] ?? '' ); ?></h3>
						<p class="showcase__desc"><?php echo esc_html( $row['text'] ?? '' ); ?></p>
						<?php
						if ( ! empty( $row['link']['url'] ) ) {
							amber_button( $row['link'], 'link', array( 'icon' => true ) );
						}
						?>
					</div>
					<div class="showcase__media" data-animate="<?php echo ( $i % 2 ) ? 'fade-right' : 'fade-left'; ?>">
						<div class="showcase__frame">
							<?php amber_image( $row['image'] ?? '', 'amber-wide', array( 'class' => 'showcase__image', 'alt' => $row['title'] ?? '' ) ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
