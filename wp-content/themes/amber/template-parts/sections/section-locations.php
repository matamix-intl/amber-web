<?php
/**
 * Section: Global Presence / Our Locations.
 *
 * Reseller cards over a full-bleed background video. ACF Free has no repeater,
 * so each reseller is a numbered slot of flat fields (loc{n}_*) edited on the
 * Contact page; amber_reseller_defaults() supplies the copy until they are.
 * Clearing a slot's Country drops that card.
 *
 * @package Amber
 */

$amber_locations = array();
foreach ( amber_reseller_defaults() as $amber_n => $amber_default ) {
	// A slot saved empty is one the page means to drop — without this check
	// amber_sub() would read the blank as "unset" and restore the default.
	if ( amber_field_is_cleared( "loc{$amber_n}_city" ) ) {
		continue;
	}

	$amber_city = amber_sub( "loc{$amber_n}_city", $amber_default['city'] );
	if ( '' === trim( (string) $amber_city ) ) {
		continue;
	}

	// Three separate number fields, collapsed into the list the card renders.
	$amber_phones = array();
	foreach ( array( 'phone1', 'phone2', 'phone3' ) as $amber_slot ) {
		$amber_phone = amber_sub( "loc{$amber_n}_{$amber_slot}", $amber_default[ $amber_slot ] );
		if ( '' !== trim( (string) $amber_phone ) ) {
			$amber_phones[] = $amber_phone;
		}
	}

	$amber_locations[] = array(
		'city'    => $amber_city,
		'flag'    => amber_sub( "loc{$amber_n}_flag", $amber_default['flag'] ),
		'company' => amber_sub( "loc{$amber_n}_company", $amber_default['company'] ),
		'address' => amber_sub( "loc{$amber_n}_address", $amber_default['address'] ),
		'phone'   => $amber_phones,
		'email'   => amber_sub( "loc{$amber_n}_email", $amber_default['email'] ),
		'website' => amber_sub( "loc{$amber_n}_website", $amber_default['website'] ),
	);
}

// Full-bleed background video behind the reseller cards. ACF file/image fields
// hand back arrays; a raw URL is accepted too. The <video> carries no src — the
// file is heavy, so modules/loc-video.js attaches it once the section is close
// to the viewport. Clearing this leaves the plain tinted band.
$amber_loc_video_raw = amber_sub( 'locations_video', content_url( '/uploads/2026/08/20548607-hd_1920_1080_30fps.mp4' ) );
if ( is_array( $amber_loc_video_raw ) && ! empty( $amber_loc_video_raw['url'] ) ) {
	$amber_loc_video = $amber_loc_video_raw['url'];
} elseif ( is_string( $amber_loc_video_raw ) ) {
	$amber_loc_video = $amber_loc_video_raw;
} else {
	$amber_loc_video = '';
}
?>
<section <?php echo amber_section_attrs( 'locations', 'ink', 'loc--video' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-locations>

	<div class="loc-media" aria-hidden="true">
		<?php if ( $amber_loc_video ) : ?>
			<video
				class="loc-media__video"
				data-loc-video
				data-src="<?php echo esc_url( $amber_loc_video ); ?>"
				muted
				loop
				playsinline
				preload="none"></video>
		<?php endif; ?>
		<span class="loc-media__scrim"></span>
	</div>

	<div class="container">
		<div class="section-header is-align-center" data-animate="fade-up">
			<span class="pill-badge">
				<?php echo amber_icon_svg( 'globe' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo esc_html( amber_sub( 'locations_eyebrow', __( 'Global Presence', 'amber' ) ) ); ?>
			</span>
			<h2 class="section-header__title">
				<?php echo esc_html( amber_sub( 'locations_heading', __( 'Our', 'amber' ) ) ); ?>
				<span class="loc-title-accent"><?php echo esc_html( amber_sub( 'locations_heading_accent', __( 'Locations', 'amber' ) ) ); ?></span>
			</h2>
			<p class="section-header__subtitle"><?php echo esc_html( amber_sub( 'locations_subheading', __( 'Serving clients worldwide from our offices across the globe.', 'amber' ) ) ); ?></p>
		</div>

		<ul class="loc-grid" data-animate="fade-up" data-animate-delay="120">
		<?php foreach ( $amber_locations as $amber_loc ) : ?>
			<li class="loc-card">
				<p class="loc-card__city"><span class="loc-card__flag" aria-hidden="true"><?php echo esc_html( $amber_loc['flag'] ?? '' ); ?></span><?php echo esc_html( $amber_loc['city'] ?? '' ); ?></p>

				<?php if ( ! empty( $amber_loc['company'] ) ) : ?>
					<p class="loc-card__company"><?php echo esc_html( $amber_loc['company'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $amber_loc['address'] ) ) : ?>
					<p class="loc-card__address">
						<span class="loc-card__icon" aria-hidden="true"><?php echo amber_icon_svg( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php echo esc_html( $amber_loc['address'] ); ?></span>
					</p>
				<?php endif; ?>

				<?php
				// A reseller may list several numbers; accept one string or a list.
				$amber_phones = array_values( array_filter( array_map( 'trim', (array) ( $amber_loc['phone'] ?? array() ) ) ) );
				?>
				<?php if ( $amber_phones || ! empty( $amber_loc['email'] ) || ! empty( $amber_loc['website'] ) ) : ?>
					<div class="loc-card__contact">
						<?php foreach ( $amber_phones as $amber_phone ) : ?>
							<a class="loc-card__link loc-card__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $amber_phone ) ); ?>">
								<span class="loc-card__icon" aria-hidden="true"><?php echo amber_icon_svg( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span><?php echo esc_html( $amber_phone ); ?></span>
							</a>
						<?php endforeach; ?>
						<?php if ( ! empty( $amber_loc['email'] ) ) : ?>
							<?php
							// An address like support@amalgamatetechnologies.com is one
							// unbreakable "word", so the card's emergency overflow-wrap
							// used to split it mid-domain ("…technolo / gies.com").
							// <wbr> after the @ and each dot gives the line-breaker real
							// opportunities, which it prefers over breaking anywhere.
							$amber_email_html = str_replace( array( '@', '.' ), array( '@<wbr>', '.<wbr>' ), esc_html( $amber_loc['email'] ) );
							?>
							<a class="loc-card__link loc-card__email" href="mailto:<?php echo esc_attr( $amber_loc['email'] ); ?>">
								<span class="loc-card__icon" aria-hidden="true"><?php echo amber_icon_svg( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span><?php echo $amber_email_html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above, <wbr> added deliberately. ?></span>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $amber_loc['website'] ) ) : ?>
							<?php
							// Show the bare domain — the scheme and any trailing
							// slash are plumbing, not something to read on a card.
							$amber_site_label = preg_replace( '~^https?://|/$~', '', $amber_loc['website'] );
							// Same dot-break opportunities as the email above.
							$amber_site_html  = str_replace( '.', '.<wbr>', esc_html( $amber_site_label ) );
							?>
							<a class="loc-card__link loc-card__website" href="<?php echo esc_url( $amber_loc['website'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="loc-card__icon" aria-hidden="true"><?php echo amber_icon_svg( 'globe' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span><?php echo $amber_site_html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above, <wbr> added deliberately. ?></span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>

	</div>
</section>
