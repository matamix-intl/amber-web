<?php
/**
 * Component: "Schedule a Demo" modal.
 *
 * A single, site-wide modal rendered once in footer.php. Any element with
 * data-open-modal="demo-modal" opens it (see modules/demo-modal.js). Renders
 * the Contact Form 7 "demo-request" form; falls back to a link to the
 * Contact page if the plugin/form isn't available so nothing dead-ends.
 *
 * @package Amber
 */

$amber_demo_shortcode = amber_option( 'demo_modal_shortcode', '[contact-form-7 id="e88aa0d" title="demo-request"]' );
$amber_demo_form_id   = amber_uid( 'demo-modal' );
?>
<div class="demo-modal" id="demo-modal" data-demo-modal hidden>
	<div class="demo-modal__panel" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $amber_demo_form_id ); ?>-title">
		<button type="button" class="demo-modal__close" data-demo-modal-close aria-label="<?php esc_attr_e( 'Close', 'amber' ); ?>">
			<?php echo amber_icon_svg( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<div class="demo-modal__head">
			<span class="demo-modal__eyebrow">
				<?php echo amber_icon_svg( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php esc_html_e( '15-Minute Walkthrough', 'amber' ); ?>
			</span>
			<h3 id="<?php echo esc_attr( $amber_demo_form_id ); ?>-title"><?php esc_html_e( 'Schedule a Software Demo', 'amber' ); ?></h3>
			<p class="demo-modal__lead"><?php esc_html_e( "Tell us about your business and we'll set up a time that works for you.", 'amber' ); ?></p>
		</div>
		<div class="demo-modal__body">
			<?php
			if ( shortcode_exists( 'contact-form-7' ) ) {
				echo do_shortcode( wp_kses_post( $amber_demo_shortcode ) );
			} else {
				?>
				<p><?php esc_html_e( "We'd love to show you around. Tell us a bit about your business and we'll set up a time.", 'amber' ); ?></p>
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Go to Contact page', 'amber' ); ?></a>
				<?php
			}
			?>
		</div>
	</div>
</div>
