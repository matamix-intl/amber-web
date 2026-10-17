<?php
/**
 * Comments template.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			$amber_count = (int) get_comments_number();
			/* translators: %s: comment count. */
			printf( esc_html( _n( '%s Comment', '%s Comments', $amber_count, 'amber' ) ), esc_html( number_format_i18n( $amber_count ) ) );
			?>
		</h2>

		<ol class="comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( '&larr; Older', 'amber' ),
				'next_text' => __( 'Newer &rarr;', 'amber' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="comments__closed"><?php esc_html_e( 'Comments are closed.', 'amber' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit' => 'btn btn--primary',
			'title_reply'  => __( 'Leave a comment', 'amber' ),
		)
	);
	?>
</section>
