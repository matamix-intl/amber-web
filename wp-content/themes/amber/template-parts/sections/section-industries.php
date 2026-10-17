<?php
/**
 * Section: Industries — "Industries We Serve".
 *
 * A staggered dark mosaic on a near-black field: charcoal tiles carrying a
 * white line-art icon and an uppercase industry name. Tiles are placed on an
 * explicit CSS Grid (see .industries-mosaic in assets/scss/sections/
 * _amber-tech.scss) so columns step out of phase and heights alternate.
 *
 * Icons are PNGs uploaded per industry (industries{n}_img). They render as a
 * CSS mask rather than an <img>, so the theme controls the colour — white at
 * rest, accent on hover — whatever colour the uploaded file happens to be.
 * That means the PNG must have a transparent background; its own colours are
 * discarded and only its silhouette is used.
 *
 * @package Amber
 */

// Live sub-fields (Pro flexible-content row) fall back to the numbered ACF
// Free fields (industries{n}_*), which in turn fall back to the shared demo
// defaults — see amber_home_industries_defaults() in inc/helpers.php.
$industries_row = amber_sub( 'industries_items', null );
if ( is_array( $industries_row ) ) {
	$industries = $industries_row;
} else {
	$industries = array();
	foreach ( amber_home_industries_defaults() as $i => $d ) {
		$industries[] = array(
			'name' => amber_sub( "industries{$i}_name", $d['name'] ),
			'img'  => amber_sub( "industries{$i}_img", null ),
		);
	}
}

/**
 * Resolve an industry icon to a single URL.
 *
 * Accepts whatever the field returns — an ACF image array, a bare attachment
 * ID or a raw URL — and prefers a small size, since the icon renders at 44px.
 *
 * @param mixed $img Image value.
 * @return string URL, or '' when no icon is set.
 */
$amber_ind_icon_url = static function ( $img ) {
	if ( is_array( $img ) ) {
		if ( ! empty( $img['sizes']['medium'] ) ) {
			return (string) $img['sizes']['medium'];
		}
		return isset( $img['url'] ) ? (string) $img['url'] : '';
	}
	if ( is_numeric( $img ) ) {
		return (string) wp_get_attachment_image_url( (int) $img, 'medium' );
	}
	return is_string( $img ) ? $img : '';
};
?>
<section <?php echo amber_section_attrs( 'industries', 'ink' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-industries>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'industries_eyebrow', __( 'Your Industry, Our Expertise', 'amber' ) ),
				'heading'    => amber_sub( 'industries_heading', __( 'Industries We Serve', 'amber' ) ),
				'subheading' => amber_sub( 'industries_subheading', __( 'Empowering diverse industries with tailored software solutions designed to streamline operations, enhance efficiency, and drive sustainable growth.', 'amber' ) ),
				'align'      => 'center',
			)
		);
		?>

		<ul class="industries-mosaic">
			<?php
			foreach ( (array) $industries as $i => $industry ) :
				$ind_name = isset( $industry['name'] ) ? $industry['name'] : '';
				if ( '' === $ind_name ) {
					continue;
				}
				$ind_icon = $amber_ind_icon_url( $industry['img'] ?? null );
				?>
				<li class="ind-tile" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( ( $i % 4 ) * 60 ); ?>">
					<?php if ( $ind_icon ) : ?>
						<span class="ind-tile__icon" style="--ind-icon:url('<?php echo esc_url( $ind_icon ); ?>')" aria-hidden="true"></span>
					<?php else : ?>
						<?php // No PNG uploaded yet — a neutral outline keeps the tile composed. ?>
						<span class="ind-tile__icon ind-tile__icon--empty" aria-hidden="true"></span>
					<?php endif; ?>
					<h3 class="ind-tile__name"><?php echo esc_html( $ind_name ); ?></h3>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
