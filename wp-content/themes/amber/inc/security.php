<?php
/**
 * Security & hardening.
 *
 * Sensible defaults for a public marketing site: remove version fingerprints,
 * disable XML-RPC, add baseline security headers, and provide a reusable
 * AJAX handler scaffold that demonstrates nonce + sanitisation discipline.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove information-leaking <head> output.
 *
 * @return void
 */
function amber_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );                 // WP version.
	remove_action( 'wp_head', 'wlwmanifest_link' );             // Windows Live Writer.
	remove_action( 'wp_head', 'rsd_link' );                     // Really Simple Discovery.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );         // Shortlink.
	remove_action( 'wp_head', 'feed_links_extra', 3 );          // Per-post comment feeds.
}
add_action( 'init', 'amber_clean_head' );

/**
 * Strip the WordPress version query string from enqueued assets.
 *
 * @param string $src Asset URL.
 * @return string
 */
function amber_remove_wp_ver( $src ) {
	if ( $src && false !== strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'amber_remove_wp_ver', 9999 );
add_filter( 'script_loader_src', 'amber_remove_wp_ver', 9999 );

/**
 * Disable XML-RPC (a common brute-force/DDoS vector for marketing sites).
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Send baseline security headers.
 *
 * Conservative defaults that won't break the front-end. A full CSP should be
 * tuned per-deployment, so it's intentionally omitted here.
 *
 * @return void
 */
function amber_security_headers() {
	if ( is_admin() || headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
}
add_action( 'send_headers', 'amber_security_headers' );

/**
 * Example AJAX endpoint: newsletter subscribe.
 *
 * Demonstrates the required security flow — verify nonce, sanitise input,
 * validate, then respond with wp_send_json_*. Wire a real ESP in production.
 *
 * @return void
 */
function amber_handle_newsletter() {
	// 1) Verify the nonce created in enqueue.php / the form.
	if ( ! check_ajax_referer( 'amber_nonce', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'amber' ) ), 403 );
	}

	// 2) Sanitise.
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	// 3) Validate.
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'amber' ) ), 422 );
	}

	/**
	 * Hook for integrating a real email-service provider (Mailchimp, etc.).
	 *
	 * @param string $email Validated subscriber email.
	 */
	do_action( 'amber_newsletter_subscribe', $email );

	wp_send_json_success( array( 'message' => __( 'Thanks! Please check your inbox to confirm.', 'amber' ) ) );
}
add_action( 'wp_ajax_amber_newsletter', 'amber_handle_newsletter' );
add_action( 'wp_ajax_nopriv_amber_newsletter', 'amber_handle_newsletter' );

/**
 * Built-in contact form handler.
 *
 * Full security flow: nonce verification, honeypot spam trap, sanitisation,
 * validation, then wp_mail() to the admin address. Returns JSON for the JS.
 *
 * @return void
 */
function amber_handle_contact() {
	if ( ! check_ajax_referer( 'amber_nonce', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'amber' ) ), 403 );
	}

	// Honeypot: a real user never fills this hidden field.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Thanks! Your message has been sent.', 'amber' ) ) ); // Silently accept to fool bots.
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	$errors = array();
	if ( '' === $name ) {
		$errors[] = __( 'Please enter your name.', 'amber' );
	}
	if ( ! is_email( $email ) ) {
		$errors[] = __( 'Please enter a valid email address.', 'amber' );
	}
	if ( '' === $message ) {
		$errors[] = __( 'Please enter a message.', 'amber' );
	}
	if ( $errors ) {
		wp_send_json_error( array( 'message' => implode( ' ', $errors ) ), 422 );
	}

	$to      = amber_option( 'contact_email' ) ? amber_option( 'contact_email' ) : get_option( 'admin_email' );
	$subject = $subject ? $subject : sprintf( /* translators: %s site name */ __( 'New enquiry via %s', 'amber' ), get_bloginfo( 'name' ) );
	$body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, '[' . get_bloginfo( 'name' ) . '] ' . $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'Thanks! Your message has been sent — we’ll be in touch shortly.', 'amber' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Sorry, something went wrong sending your message. Please email us directly.', 'amber' ) ), 500 );
}
add_action( 'wp_ajax_amber_contact', 'amber_handle_contact' );
add_action( 'wp_ajax_nopriv_amber_contact', 'amber_handle_contact' );

/**
 * Disable file editing from the admin (defence in depth).
 * Respect a pre-existing definition in wp-config.php.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
