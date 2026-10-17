<?php
/**
 * Component: Section Header (eyebrow + heading + subheading).
 *
 * Shared heading block used by most section layouts to keep typography and
 * spacing consistent. Pass values via the $args array:
 *   eyebrow, heading, subheading, align ('center'|'left'), level (h2/h1),
 *   class (extra classes).
 *
 * @package Amber
 */

$amber_args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'    => '',
		'heading'    => '',
		'subheading' => '',
		'align'      => 'center',
		'level'      => 'h2',
		'class'      => '',
	)
);

if ( '' === $amber_args['eyebrow'] && '' === $amber_args['heading'] && '' === $amber_args['subheading'] ) {
	return;
}

$amber_tag = in_array( $amber_args['level'], array( 'h1', 'h2', 'h3' ), true ) ? $amber_args['level'] : 'h2';
$amber_cls = 'section-header is-align-' . sanitize_html_class( $amber_args['align'] );
if ( $amber_args['class'] ) {
	$amber_cls .= ' ' . $amber_args['class'];
}
?>
<div class="<?php echo esc_attr( $amber_cls ); ?>" data-animate="fade-up">
	<?php if ( $amber_args['eyebrow'] ) : ?>
		<p class="section-header__eyebrow"><?php echo esc_html( $amber_args['eyebrow'] ); ?></p>
	<?php endif; ?>

	<?php if ( $amber_args['heading'] ) : ?>
		<<?php echo esc_html( $amber_tag ); ?> class="section-header__title"><?php echo esc_html( $amber_args['heading'] ); ?></<?php echo esc_html( $amber_tag ); ?>>
	<?php endif; ?>

	<?php if ( $amber_args['subheading'] ) : ?>
		<p class="section-header__subtitle"><?php echo esc_html( $amber_args['subheading'] ); ?></p>
	<?php endif; ?>
</div>
