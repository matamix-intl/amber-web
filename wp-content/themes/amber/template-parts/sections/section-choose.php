<?php
/**
 * Section: How To Choose — copy + checklist left, product image right.
 *
 * A light band placed right after the hero. Mirrors the "How to choose the
 * right POS solution" reference layout: heading + intro + a feature checklist
 * (check-icon list) and a CTA on the left; a single editable image on the
 * right (falls back to the theme's bundled placeholder when empty, same
 * pattern as the POS hero's `erp_hero_image`).
 *
 * @package Amber
 */

$heading = amber_sub( 'choose_heading', __( "How to choose the right POS\nsolution for restaurants & retail\nstores?", 'amber' ) );

// Hard line breaks pin the heading to the reference's exact 3-line split
// (same approach as the Amber Laundry Software hero / ERP CTA headings elsewhere in this
// theme) rather than leaving it to the browser's own soft-wrap, which would
// break at different words depending on the column's rendered width.
$heading_lines = explode( "\n", $heading );
$intro   = amber_sub( 'choose_intro', __( 'Before choosing a POS system, understand that POS software contributes to your business in every area far beyond managing bills and transactions. Look out for these features and stay ahead of the competition to be the best in your business:', 'amber' ) );
$button  = amber_sub( 'choose_btn', array( 'title' => __( 'Try Amber POS Software now', 'amber' ), 'url' => home_url( '/contact-us/' ), 'target' => '' ) );

$points = array();
foreach ( amber_choose_points_defaults() as $point_i => $point_default ) {
	$points[] = amber_sub( "choose_point{$point_i}", $point_default );
}

$image_raw = amber_sub( 'choose_image', '' );
if ( empty( $image_raw ) ) {
	$image_raw = get_template_directory_uri() . '/assets/images/placeholder.svg';
}
?>
<section <?php echo amber_section_attrs( 'choose', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="container">
		<div class="choose-sec__row">
			<div class="choose-sec__content" data-animate="fade-right">
				<h2 class="choose-sec__title"><?php echo implode( '<br>', array_map( 'esc_html', $heading_lines ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>

				<?php if ( $intro ) : ?>
					<p class="choose-sec__intro"><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>

				<ul class="choose-sec__list">
					<?php foreach ( $points as $point ) : ?>
						<li><span class="choose-sec__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php echo esc_html( $point ); ?></li>
					<?php endforeach; ?>
				</ul>

				<?php if ( ! empty( $button['url'] ) ) : ?>
					<a class="btn btn--primary btn--lg choose-sec__btn" href="<?php echo esc_url( $button['url'] ); ?>"<?php echo ( ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
						<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : __( 'Try Amber POS Software now', 'amber' ) ); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="choose-sec__visual" data-animate="fade-left">
				<?php amber_image( $image_raw, 'large', array( 'class' => 'choose-sec__img', 'alt' => wp_strip_all_tags( implode( ' ', $heading_lines ) ) ) ); ?>
			</div>
		</div>
	</div>
</section>
