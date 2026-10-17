<?php
/**
 * Section: Why Choose Us (image + checklist split).
 *
 * @package Amber
 */

$image     = amber_sub( 'why_image', '' );
$side      = amber_sub( 'why_image_side', 'right' );
$btn       = amber_sub( 'why_btn', array( 'title' => __( 'Why teams switch to us', 'amber' ), 'url' => home_url( '/about-us/' ), 'target' => '' ) );
$points    = amber_sub(
	'why_points',
	array(
		array( 'title' => __( 'Set up in minutes', 'amber' ), 'text' => __( 'Guided onboarding and sensible defaults get you live the same day.', 'amber' ) ),
		array( 'title' => __( 'Loved by your team', 'amber' ), 'text' => __( 'An interface people actually enjoy means adoption you don’t have to chase.', 'amber' ) ),
		array( 'title' => __( 'Scales without surprises', 'amber' ), 'text' => __( 'Transparent pricing and rock-solid uptime as you grow.', 'amber' ) ),
	)
);
?>
<section <?php echo amber_section_attrs( 'why', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-why>
	<div class="container why__inner is-image-<?php echo esc_attr( $side ); ?>">
		<div class="why__media" data-animate="<?php echo 'left' === $side ? 'fade-right' : 'fade-left'; ?>">
			<div class="why__media-frame">
				<?php amber_image( $image, 'amber-wide', array( 'class' => 'why__image' ) ); ?>
			</div>
		</div>

		<div class="why__content" data-animate="<?php echo 'left' === $side ? 'fade-left' : 'fade-right'; ?>">
			<?php
			get_template_part(
				'template-parts/components/section-header',
				null,
				array(
					'eyebrow'    => amber_sub( 'why_eyebrow', __( 'Why Amber', 'amber' ) ),
					'heading'    => amber_sub( 'why_heading', __( 'The details others overlook', 'amber' ) ),
					'subheading' => amber_sub( 'why_subheading', '' ),
					'align'      => 'left',
				)
			);
			?>

			<ul class="why__list">
				<?php foreach ( (array) $points as $point ) : ?>
					<li class="why__item">
						<span class="why__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<div>
							<h3 class="why__item-title"><?php echo esc_html( $point['title'] ?? '' ); ?></h3>
							<p class="why__item-text"><?php echo esc_html( $point['text'] ?? '' ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( ! empty( $btn['url'] ) ) : ?>
				<div class="why__cta"><?php amber_button( $btn, 'primary', array( 'icon' => true ) ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
