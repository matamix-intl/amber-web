<?php
/**
 * Section: Features Grid.
 *
 * @package Amber
 */

$items = amber_sub(
	'features_items',
	array(
		array( 'icon' => 'bolt', 'title' => __( 'Lightning fast', 'amber' ), 'text' => __( 'A blazing-fast core engineered for performance at any scale.', 'amber' ) ),
		array( 'icon' => 'shield', 'title' => __( 'Enterprise security', 'amber' ), 'text' => __( 'SOC 2 Type II, SSO/SAML and granular roles, baked in from day one.', 'amber' ) ),
		array( 'icon' => 'layers', 'title' => __( 'Composable by design', 'amber' ), 'text' => __( 'Mix reusable building blocks to model any workflow your team needs.', 'amber' ) ),
		array( 'icon' => 'chart', 'title' => __( 'Insightful analytics', 'amber' ), 'text' => __( 'Real-time dashboards turn raw activity into decisions that matter.', 'amber' ) ),
		array( 'icon' => 'globe', 'title' => __( 'Global by default', 'amber' ), 'text' => __( 'Multi-region, multi-language and edge-cached around the world.', 'amber' ) ),
		array( 'icon' => 'rocket', 'title' => __( 'Ships with you', 'amber' ), 'text' => __( 'Automations and an open API keep everything moving forward.', 'amber' ) ),
	)
);
?>
<section <?php echo amber_section_attrs( 'features', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-features>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'features_eyebrow', __( 'Features', 'amber' ) ),
				'heading'    => amber_sub( 'features_heading', __( 'Everything you need, nothing you don’t', 'amber' ) ),
				'subheading' => amber_sub( 'features_subheading', __( 'A thoughtfully designed toolkit that scales from your first hire to your thousandth.', 'amber' ) ),
				'align'      => amber_sub( 'features_align', 'center' ),
			)
		);
		?>

		<ul class="features-grid">
			<?php foreach ( (array) $items as $i => $item ) : ?>
				<li class="feature-card" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( ( $i % 3 ) * 80 ); ?>">
					<span class="feature-card__icon" aria-hidden="true"><?php echo amber_icon_svg( $item['icon'] ?? 'bolt' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="feature-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
					<p class="feature-card__text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
