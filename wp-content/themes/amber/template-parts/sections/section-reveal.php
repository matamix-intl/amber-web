<?php
/**
 * Section: Scroll-Zoom Reveal.
 *
 * A giant gradient wordmark sits sticky while a media frame scales up from a
 * small rounded card to full-bleed as the visitor scrolls (Pixbit-style).
 * The media is editable: a background video (Vimeo/YouTube/MP4), an image, or
 * the built-in CSS dashboard mockup. Motion lives in reveal-zoom.js and is
 * disabled on mobile + for prefers-reduced-motion.
 *
 * @package Amber
 */

$title      = amber_sub( 'reveal_title', __( 'AMBER TECHNOLOGIES', 'amber' ) );
$media_type = amber_sub( 'reveal_media_type', 'video' );
$video_url  = amber_sub( 'reveal_video_url', '' );
$image      = amber_sub( 'reveal_image', '' );

// Resolve the media markup. When nothing is set the frame stays blank — add a
// video or image from the Section settings in the admin.
$media = '';
if ( 'video' === $media_type && $video_url ) {
	$media = amber_video_embed( $video_url, 'reveal__video', $title );
} elseif ( 'image' === $media_type && $image ) {
	ob_start();
	amber_image( $image, 'amber-wide', array( 'class' => 'reveal__img', 'alt' => $title ) );
	$media = ob_get_clean();
}
?>
<section class="section--reveal" data-reveal>
	<?php if ( $title ) : ?>
		<h2 class="screen-reader-text"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>

	<div class="reveal__stage">
		<p class="reveal__title" data-reveal-title aria-hidden="true"><?php echo esc_html( $title ); ?></p>

		<div class="reveal__frame" data-reveal-frame>
			<div class="reveal__media">
				<?php
				if ( $media ) {
					echo $media; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in amber_video_embed / amber_image.
				} else {
					// Blank media slot — add a video/image from the Section settings.
					?>
					<div class="reveal__placeholder" aria-hidden="true">
						<span class="reveal__placeholder-icon"><?php echo amber_icon_svg( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
