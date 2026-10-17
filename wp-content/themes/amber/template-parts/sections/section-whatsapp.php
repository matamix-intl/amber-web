<?php
/**
 * Section: WhatsApp Integration — chat mockup left, checklist + CTA right.
 *
 * Pitches connecting Amber ERP Software with WhatsApp Business for order updates,
 * reminders and customer chat. Re-uses the "choose" section's checklist
 * pattern (heading/intro/checklist/CTA) but skinned in WhatsApp's green and
 * paired with a pure-CSS chat-bubble phone mockup instead of an editable
 * image, so the section never depends on an uploaded asset.
 *
 * @package Amber
 */

$heading = amber_sub( 'whatsapp_heading', __( 'Bring Amber ERP Software straight into your customers’ WhatsApp', 'amber' ) );
$intro   = amber_sub( 'whatsapp_intro', __( 'Connect Amber ERP Software with WhatsApp Business and turn every order, reminder and support chat into a conversation your customers already check ten times a day.', 'amber' ) );
$button  = amber_sub( 'whatsapp_btn', array( 'title' => __( 'Connect WhatsApp Business', 'amber' ), 'url' => home_url( '/contact-us/' ), 'target' => '' ) );

$points = array();
foreach ( amber_whatsapp_points_defaults() as $point_i => $point_default ) {
	$points[] = amber_sub( "whatsapp_point{$point_i}", $point_default );
}
?>
<section class="section section--whatsapp is-space-small">
	<div class="container">
		<div class="whatsapp-sec__row">
			<div class="whatsapp-sec__visual" data-animate="fade-right">
				<div class="wa-mock" aria-hidden="true">
					<div class="wa-mock__bar">
						<span class="wa-mock__avatar"><?php echo amber_icon_svg( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="wa-mock__who">
							<span class="wa-mock__name"><?php esc_html_e( 'Amber ERP Software', 'amber' ); ?></span>
							<span class="wa-mock__status"><?php esc_html_e( 'online', 'amber' ); ?></span>
						</span>
					</div>
					<div class="wa-mock__body">
						<div class="wa-bubble wa-bubble--in">
							<?php esc_html_e( 'Hi! Your order #A1042 has been packed and is out for delivery today.', 'amber' ); ?>
							<span class="wa-bubble__time">9:41</span>
						</div>
						<div class="wa-bubble wa-bubble--out">
							<?php esc_html_e( 'Perfect, thank you! Can I get an invoice copy?', 'amber' ); ?>
							<span class="wa-bubble__time"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> 9:42</span>
						</div>
						<div class="wa-bubble wa-bubble--in">
							<?php esc_html_e( 'Sure — invoice INV-2291 is attached. Thanks for shopping with us!', 'amber' ); ?>
							<span class="wa-bubble__time">9:42</span>
						</div>
						<div class="wa-chip"><?php esc_html_e( '✓ Synced to Amber ERP Software', 'amber' ); ?></div>
					</div>
				</div>
			</div>

			<div class="whatsapp-sec__content" data-animate="fade-left">
				<span class="whatsapp-sec__eyebrow"><?php echo amber_icon_svg( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php esc_html_e( 'WhatsApp Integration', 'amber' ); ?></span>
				<h2 class="whatsapp-sec__title"><?php echo esc_html( $heading ); ?></h2>

				<?php if ( $intro ) : ?>
					<p class="whatsapp-sec__intro"><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>

				<ul class="whatsapp-sec__list">
					<?php foreach ( $points as $point ) : ?>
						<li><span class="whatsapp-sec__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span class="whatsapp-sec__point"><?php echo esc_html( $point ); ?></span></li>
					<?php endforeach; ?>
				</ul>

				<?php if ( ! empty( $button['url'] ) ) : ?>
					<a class="btn btn--whatsapp btn--lg whatsapp-sec__btn" href="<?php echo esc_url( $button['url'] ); ?>"<?php echo ( ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
						<?php echo amber_icon_svg( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : __( 'Connect WhatsApp Business', 'amber' ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
