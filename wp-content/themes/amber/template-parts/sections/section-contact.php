<?php
/**
 * Section: Contact (form + details + map).
 *
 * Renders a plugin shortcode if provided, otherwise a built-in accessible
 * AJAX contact form. Contact details come from Theme Settings.
 *
 * @package Amber
 */

$amber_args    = wp_parse_args( $args ?? array(), array() );
$shortcode     = $amber_args['shortcode'] ?? amber_sub( 'contact_shortcode', '' );
$show_details  = $amber_args['show_details'] ?? amber_sub( 'contact_show_details', true );
$map           = $amber_args['map'] ?? amber_sub( 'contact_map_embed', '' );
$heading_level = $amber_args['heading_level'] ?? 'h2';

/*


 * value in Site Settings — which is also what the contact form's notification
 * email uses, so leaving these blank keeps the two in step.
 */
$amber_detail = static function ( $field, $global_default = '' ) {
	return amber_sub( "page_{$field}", amber_option( $field, $global_default ) );
};

// Social profiles — the same helper the footer uses, so both rows always show
// the identical set of networks in the identical order.
$amber_socials = amber_social_profiles();
if ( empty( $amber_socials ) ) {
	$amber_socials = array( 'instagram' => '#', 'facebook' => '#', 'twitter' => '#', 'linkedin' => '#' );
}
?>
<?php // Off-white ground, so the white form/details cards read as cards and the aurora sits on a softer base. ?>
<section <?php echo amber_section_attrs( 'contact', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-contact>
	<?php // Same aurora backdrop as the homepage hero (see _hero.scss). ?>
	<div class="aurora-bg" aria-hidden="true">
		<span class="aurora-bg__glow aurora-bg__glow--left"></span>
		<span class="aurora-bg__glow aurora-bg__glow--right"></span>
	</div>

	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'contact_eyebrow', __( 'Contact', 'amber' ) ),
				'heading'    => amber_sub( 'contact_heading', __( 'Let’s talk', 'amber' ) ),
				'subheading' => amber_sub( 'contact_subheading', __( 'Tell us about your project and we’ll get back within one business day.', 'amber' ) ),
				'align'      => amber_sub( 'contact_align', 'center' ),
				'level'      => $heading_level,
			)
		);
		?>

		<div class="contact__grid">
			<div class="contact__form-wrap" data-animate="fade-up">
				<h3 class="contact__form-heading"><?php echo esc_html( amber_sub( 'contact_form_heading', __( 'Send us a message', 'amber' ) ) ); ?></h3>
				<p class="contact__form-text"><?php echo esc_html( amber_sub( 'contact_form_text', __( 'Fill up the form and our team will get back to you within 24 hours.', 'amber' ) ) ); ?></p>
				<?php
				if ( $shortcode ) {
					echo do_shortcode( wp_kses_post( $shortcode ) );
				} else {
					?>
					<form class="contact-form" data-contact-form aria-label="<?php esc_attr_e( 'Contact form', 'amber' ); ?>">
						<div class="form-row form-row--split">
							<p class="form-field">
								<label for="amber-c-name"><?php esc_html_e( 'Name', 'amber' ); ?> <span aria-hidden="true">*</span></label>
								<input type="text" id="amber-c-name" name="name" required autocomplete="name">
							</p>
							<p class="form-field">
								<label for="amber-c-email"><?php esc_html_e( 'Email', 'amber' ); ?> <span aria-hidden="true">*</span></label>
								<input type="email" id="amber-c-email" name="email" required autocomplete="email">
							</p>
						</div>
						<p class="form-field">
							<label for="amber-c-subject"><?php esc_html_e( 'Subject', 'amber' ); ?></label>
							<input type="text" id="amber-c-subject" name="subject" autocomplete="off">
						</p>
						<p class="form-field">
							<label for="amber-c-message"><?php esc_html_e( 'Message', 'amber' ); ?> <span aria-hidden="true">*</span></label>
							<textarea id="amber-c-message" name="message" rows="5" required></textarea>
						</p>
						<?php // Honeypot — bots fill this; humans never see it. ?>
						<p class="form-field form-field--honeypot" aria-hidden="true">
							<label for="amber-c-website"><?php esc_html_e( 'Leave this field empty', 'amber' ); ?></label>
							<input type="text" id="amber-c-website" name="website" tabindex="-1" autocomplete="off">
						</p>
						<div class="form-actions">
							<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Send message', 'amber' ); ?></button>
						</div>
						<p class="form-feedback" role="status" aria-live="polite"></p>
					</form>
					<?php
				}
				?>
			</div>

			<?php if ( $show_details ) : ?>
				<aside class="contact__details" data-animate="fade-up" data-animate-delay="120">
					<p class="contact__details-eyebrow"><?php echo esc_html( amber_sub( 'contact_details_eyebrow', __( 'How to reach us', 'amber' ) ) ); ?></p>
					<h3 class="contact__details-heading"><?php echo esc_html( amber_sub( 'contact_details_heading', __( 'Contact details', 'amber' ) ) ); ?></h3>
					<p class="contact__details-text"><?php echo esc_html( amber_sub( 'contact_details_text', __( "We're just a message or call away, ready to provide you with the support and assistance you need.", 'amber' ) ) ); ?></p>

					<ul class="contact-info">
						<?php if ( $e = $amber_detail( 'contact_email', 'info@matamix.com' ) ) : ?>
							<li>
								<span class="contact-info__icon"><?php echo amber_icon_svg( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-info__body">
									<span class="contact-info__label"><?php esc_html_e( 'Email', 'amber' ); ?></span>
									<a href="mailto:<?php echo esc_attr( $e ); ?>"><?php echo esc_html( $e ); ?></a>
								</span>
							</li>
						<?php endif; ?>
						<?php if ( $p = $amber_detail( 'contact_phone', '+91 9605000694' ) ) : ?>
							<li>
								<span class="contact-info__icon"><?php echo amber_icon_svg( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-info__body">
									<span class="contact-info__label"><?php esc_html_e( 'Phone Number', 'amber' ); ?></span>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $p ) ); ?>"><?php echo esc_html( $p ); ?></a>
								</span>
							</li>
						<?php endif; ?>
						<?php if ( $a = $amber_detail( 'contact_address' ) ) : ?>
							<li>
								<span class="contact-info__icon"><?php echo amber_icon_svg( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-info__body">
									<span class="contact-info__label"><?php esc_html_e( 'Address', 'amber' ); ?></span>
									<span><?php echo nl2br( esc_html( $a ) ); ?></span>
								</span>
							</li>
						<?php endif; ?>
						<?php // No invented default — the row hides until a real WhatsApp number is set in Theme Settings. ?>
						<?php if ( $w = $amber_detail( 'contact_whatsapp' ) ) : ?>
							<li>
								<span class="contact-info__icon"><?php echo amber_icon_svg( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-info__body">
									<span class="contact-info__label"><?php esc_html_e( 'WhatsApp', 'amber' ); ?></span>
									<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $w ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $w ); ?></a>
								</span>
							</li>
						<?php endif; ?>
						<?php // Timezone depends on the office, so no default — set it in Theme Settings. ?>
						<?php if ( $h = $amber_detail( 'contact_hours' ) ) : ?>
							<li>
								<span class="contact-info__icon"><?php echo amber_icon_svg( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-info__body">
									<span class="contact-info__label"><?php esc_html_e( 'Working Hours', 'amber' ); ?></span>
									<span><?php echo esc_html( $h ); ?></span>
								</span>
							</li>
						<?php endif; ?>
					</ul>

					<?php if ( $map ) : ?>
						<div class="contact__map">
							<iframe src="<?php echo esc_url( $map ); ?>" width="100%" height="280" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Office location map', 'amber' ); ?>"></iframe>
						</div>
					<?php endif; ?>

					<div class="contact__details-follow">
						<span class="contact__details-follow-label"><?php esc_html_e( 'Follow Us', 'amber' ); ?></span>
						<ul class="contact-social">
							<?php foreach ( $amber_socials as $amber_net => $amber_url ) : ?>
								<?php $amber_is_placeholder = '#' === $amber_url; ?>
								<li>
									<?php if ( $amber_is_placeholder ) : ?>
										<span class="contact-social__link" aria-hidden="true">
											<?php echo amber_social_svg( $amber_net ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										</span>
									<?php else : ?>
										<a class="contact-social__link" href="<?php echo esc_url( $amber_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( 'twitter' === $amber_net ? __( 'Twitter / X', 'amber' ) : ucfirst( $amber_net ) ); ?>">
											<?php echo amber_social_svg( $amber_net ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										</a>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
