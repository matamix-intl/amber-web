<?php
/**
 * Section: Pricing Plans.
 *
 * @package Amber
 */

$plans = amber_sub(
	'pricing_plans',
	array(
		array(
			'name'     => __( 'Starter', 'amber' ),
			'price'    => '$0',
			'period'   => __( '/mo', 'amber' ),
			'tagline'  => __( 'For individuals getting started.', 'amber' ),
			'features' => "Up to 3 projects\nCommunity support\n1 GB storage\nCore integrations",
			'button'   => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Get started', 'amber' ) ),
			'featured' => false,
		),
		array(
			'name'     => __( 'Growth', 'amber' ),
			'price'    => '$29',
			'period'   => __( '/mo', 'amber' ),
			'tagline'  => __( 'For growing teams that need more.', 'amber' ),
			'features' => "Unlimited projects\nPriority support\n50 GB storage\nAdvanced analytics\nSSO & roles",
			'button'   => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Start free trial', 'amber' ) ),
			'featured' => true,
		),
		array(
			'name'     => __( 'Enterprise', 'amber' ),
			'price'    => __( 'Custom', 'amber' ),
			'period'   => '',
			'tagline'  => __( 'For organisations at scale.', 'amber' ),
			'features' => "Everything in Growth\nDedicated manager\nUnlimited storage\nSLA & audit logs\nCustom contracts",
			'button'   => array( 'url' => home_url( '/contact-us/' ), 'title' => __( 'Contact sales', 'amber' ) ),
			'featured' => false,
		),
	)
);
?>
<section <?php echo amber_section_attrs( 'pricing', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-pricing>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'pricing_eyebrow', __( 'Pricing', 'amber' ) ),
				'heading'    => amber_sub( 'pricing_heading', __( 'Simple, transparent pricing', 'amber' ) ),
				'subheading' => amber_sub( 'pricing_subheading', __( 'Start free. Upgrade when you’re ready. No hidden fees.', 'amber' ) ),
				'align'      => amber_sub( 'pricing_align', 'center' ),
			)
		);
		?>

		<div class="pricing-grid">
			<?php foreach ( (array) $plans as $i => $plan ) : ?>
				<?php $is_featured = ! empty( $plan['featured'] ); ?>
				<article class="price-card <?php echo $is_featured ? 'price-card--featured' : ''; ?>" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<?php if ( $is_featured ) : ?>
						<span class="price-card__badge"><?php esc_html_e( 'Most popular', 'amber' ); ?></span>
					<?php endif; ?>

					<h3 class="price-card__name"><?php echo esc_html( $plan['name'] ?? '' ); ?></h3>
					<p class="price-card__price">
						<span class="price-card__amount"><?php echo esc_html( $plan['price'] ?? '' ); ?></span>
						<?php if ( ! empty( $plan['period'] ) ) : ?><span class="price-card__period"><?php echo esc_html( $plan['period'] ); ?></span><?php endif; ?>
					</p>
					<?php if ( ! empty( $plan['tagline'] ) ) : ?>
						<p class="price-card__tagline"><?php echo esc_html( $plan['tagline'] ); ?></p>
					<?php endif; ?>

					<ul class="price-card__features">
						<?php
						$features = preg_split( '/\r\n|\r|\n/', (string) ( $plan['features'] ?? '' ) );
						foreach ( array_filter( array_map( 'trim', $features ) ) as $feature ) :
							?>
							<li><span class="price-card__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>

					<?php
					if ( ! empty( $plan['button']['url'] ) ) {
						amber_button( $plan['button'], $is_featured ? 'primary' : 'secondary', array( 'class' => 'btn--block' ) );
					}
					?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
