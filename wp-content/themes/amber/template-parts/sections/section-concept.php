<?php
/**
 * Section: From Concept To Success — ambient grid backdrop + client marquee.
 *
 * A dark "infrastructure" band: a subtle 80px background grid with a few
 * ambient glow cells (sky/blue, never yellow) that read as light sources — not
 * UI cards. A two-tone headline and intro sit above, followed by a continuous
 * client-logo marquee that reuses the shared .logos marquee + logo-slider.js.
 * Adapted from the Pixbit "From Concept To Success" section.
 *
 * @package Amber
 */

$heading_lead = amber_sub( 'concept_heading_lead', __( 'From Concept To', 'amber' ) );
$heading_word = amber_sub( 'concept_heading_word', __( 'Success', 'amber' ) );
$intro        = amber_sub( 'concept_intro', __( 'We thrive on the power of innovation and transformation — converting your visionary ideas into real-world solutions that propel your business toward unprecedented growth, guided by a relentless pursuit of excellence.', 'amber' ) );
// Real client logos managed in wp-admin → Client Logos (the `client_logo` CPT),
// read through the shared accessor so this marquee, the trusted-by slider and
// the product pages' brand strips always show the same clients. An ACF PRO
// flexible-content row can still override it with its own list.
$logos = amber_sub( 'concept_logos', array() );
if ( empty( $logos ) || ! is_array( $logos ) ) {
	$logos = amber_client_logos();
}
?>
<section class="section section--concept is-bg-navy is-space-large" data-concept>
	<?php // Blue grid backdrop + one filled blue cell — purely decorative. ?>
	<div class="concept__grid" aria-hidden="true">
		<span class="concept__fill"></span>
	</div>

	<div class="container">
		<div class="concept__head" data-animate="fade-up">
			<h2 class="concept__title">
				<span class="concept__title-accent"><?php echo esc_html( $heading_lead ); ?></span>
				<span class="concept__title-plain"><?php echo esc_html( $heading_word ); ?></span>
			</h2>

			<?php if ( $intro ) : ?>
				<p class="concept__intro"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php // Full-bleed client marquee (animated by logo-slider.js). The blue band
	// lives on .concept__marquee; the inner .logos__viewport fades the logos at the
	// horizontal edges while the band background + rules stay solid full-width. ?>
	<div class="concept__marquee" data-animate="fade-up" data-animate-delay="120">
		<div class="logos__viewport">
		<ul class="logos__track" data-logos-track>
			<?php foreach ( $logos as $logo_row ) : ?>
				<?php $logo_name = $logo_row['name'] ?? ''; ?>
				<li class="logos__item<?php echo empty( $logo_row['logo'] ) ? ' logos__item--text' : ''; ?>">
					<?php if ( ! empty( $logo_row['logo'] ) ) : ?>
						<?php amber_image( $logo_row['logo'], 'amber-logo', array( 'class' => 'logos__img', 'alt' => $logo_name ) ); ?>
					<?php else : ?>
						<span class="logos__wordmark"><?php echo esc_html( $logo_name ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		</div>
	</div>
</section>
