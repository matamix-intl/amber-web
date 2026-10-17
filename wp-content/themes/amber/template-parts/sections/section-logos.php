<?php
/**
 * Section: Trusted-By Logo Slider.
 *
 * A continuous marquee on wide screens; wraps gracefully on mobile. The track
 * is duplicated for a seamless loop (handled by CSS + logo-slider.js).
 *
 * @package Amber
 */

$heading = amber_sub( 'logos_heading', __( 'Trusted by fast-growing teams worldwide', 'amber' ) );

// The client set is shared site-wide (Site Settings → Client Logos), so this
// slider shows exactly the clients the product pages do. An ACF PRO
// flexible-content row can still override it with its own list.
$logos = amber_sub( 'logos_items', array() );
if ( empty( $logos ) || ! is_array( $logos ) ) {
	$logos = amber_client_logos();
}
?>
<section class="section section--logos is-bg-white is-space-small" data-logos>
	<div class="container">
		<?php if ( $heading ) : ?>
			<p class="logos__heading"><?php echo esc_html( $heading ); ?></p>
		<?php endif; ?>

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
