<?php
/**
 * Section: Services.
 *
 * Pulls either from a manual repeater or the Service CPT, depending on the
 * "Content Source" select.
 *
 * @package Amber
 */

$source = amber_sub( 'services_source', 'manual' );

// Normalise both sources into a flat array of cards.
$cards = array();

if ( 'cpt' === $source && function_exists( 'get_field' ) ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 6,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);
	while ( $q->have_posts() ) {
		$q->the_post();
		$cards[] = array(
			'icon'  => (string) get_field( 'icon' ),
			'title' => get_the_title(),
			'text'  => get_the_excerpt(),
			'link'  => array( 'url' => get_permalink(), 'title' => __( 'Learn more', 'amber' ) ),
		);
	}
	wp_reset_postdata();
}

if ( empty( $cards ) ) {
	$cards = amber_sub(
		'services_items',
		array(
			array( 'icon' => 'layers', 'title' => __( 'Product Strategy', 'amber' ), 'text' => __( 'Research-driven roadmaps that align your team around the outcomes that matter.', 'amber' ), 'link' => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Learn more', 'amber' ) )),
			array( 'icon' => 'bolt', 'title' => __( 'Engineering', 'amber' ), 'text' => __( 'Production-grade builds with clean architecture, tests and CI from day one.', 'amber' ), 'link' => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Learn more', 'amber' ) )),
			array( 'icon' => 'chart', 'title' => __( 'Growth & Analytics', 'amber' ), 'text' => __( 'Instrumentation and experimentation that compound into measurable growth.', 'amber' ), 'link' => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Learn more', 'amber' ) )),
		)
	);
}
?>
<section <?php echo amber_section_attrs( 'services', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-services>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'services_eyebrow', __( 'Services', 'amber' ) ),
				'heading'    => amber_sub( 'services_heading', __( 'Services built to move you forward', 'amber' ) ),
				'subheading' => amber_sub( 'services_subheading', __( 'End-to-end expertise across the full product lifecycle.', 'amber' ) ),
				'align'      => amber_sub( 'services_align', 'center' ),
			)
		);
		?>

		<div class="services-grid">
			<?php foreach ( (array) $cards as $i => $card ) : ?>
				<article class="service-card" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( ( $i % 3 ) * 90 ); ?>">
					<span class="service-card__icon" aria-hidden="true"><?php echo amber_icon_svg( $card['icon'] ?: 'layers' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="service-card__title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
					<p class="service-card__text"><?php echo esc_html( $card['text'] ?? '' ); ?></p>
					<?php if ( ! empty( $card['link']['url'] ) ) : ?>
						<a class="service-card__link" href="<?php echo esc_url( $card['link']['url'] ); ?>">
							<?php echo esc_html( $card['link']['title'] ?: __( 'Learn more', 'amber' ) ); ?>
							<?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
