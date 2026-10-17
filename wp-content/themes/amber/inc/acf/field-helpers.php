<?php
/**
 * ACF field-array builders.
 *
 * acf_add_local_field_group() accepts the same array shape as ACF's export
 * JSON. Hand-writing those arrays is verbose and error-prone, so these tiny
 * factory functions produce well-formed field arrays and keep the actual
 * field-group files declarative and readable. Field keys are derived
 * deterministically from the field name so they stay stable across releases.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a stable field key from a name.
 *
 * @param string $name Field name.
 * @return string
 */
function amber_fk( $name ) {
	return 'field_amber_' . substr( md5( $name ), 0, 16 ) . '_' . sanitize_key( $name );
}

/**
 * Generic field factory.
 *
 * @param string $type  ACF field type.
 * @param string $name  Field name.
 * @param string $label Field label.
 * @param array  $args  Extra ACF args (instructions, default_value, etc.).
 * @return array
 */
function amber_f( $type, $name, $label, $args = array() ) {
	return array_merge(
		array(
			'key'   => amber_fk( $name ),
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		),
		$args
	);
}

/** Convenience wrappers for the field types used across the theme. */
function amber_f_text( $name, $label, $args = array() ) {
	return amber_f( 'text', $name, $label, $args );
}
function amber_f_textarea( $name, $label, $args = array() ) {
	return amber_f( 'textarea', $name, $label, array_merge( array( 'rows' => 3 ), $args ) );
}
function amber_f_wysiwyg( $name, $label, $args = array() ) {
	return amber_f( 'wysiwyg', $name, $label, array_merge( array( 'tabs' => 'all', 'media_upload' => 1 ), $args ) );
}
function amber_f_image( $name, $label, $args = array() ) {
	return amber_f( 'image', $name, $label, array_merge( array( 'return_format' => 'array', 'preview_size' => 'medium', 'library' => 'all' ), $args ) );
}
function amber_f_link( $name, $label, $args = array() ) {
	return amber_f( 'link', $name, $label, array_merge( array( 'return_format' => 'array' ), $args ) );
}
function amber_f_select( $name, $label, $choices, $args = array() ) {
	return amber_f( 'select', $name, $label, array_merge( array( 'choices' => $choices, 'ui' => 1 ), $args ) );
}
function amber_f_truefalse( $name, $label, $args = array() ) {
	return amber_f( 'true_false', $name, $label, array_merge( array( 'ui' => 1 ), $args ) );
}
function amber_f_color( $name, $label, $args = array() ) {
	return amber_f( 'color_picker', $name, $label, $args );
}
function amber_f_number( $name, $label, $args = array() ) {
	return amber_f( 'number', $name, $label, $args );
}
function amber_f_icon( $name, $label, $args = array() ) {
	// A simple curated icon select that maps to amber_icon_svg() keys.
	$choices = array(
		'bolt'   => 'Bolt / Speed',
		'shield' => 'Shield / Security',
		'rocket' => 'Rocket / Launch',
		'layers' => 'Layers / Stack',
		'chart'  => 'Chart / Analytics',
		'globe'  => 'Globe / Global',
		'star'   => 'Star',
		'check'  => 'Check',
	);
	return amber_f_select( $name, $label, $choices, $args );
}

/**
 * Repeater factory (ACF Pro). Pass the sub-fields array.
 *
 * @param string $name       Field name.
 * @param string $label      Label.
 * @param array  $sub_fields Sub-field arrays.
 * @param array  $args       Extra args (button_label, min, max, layout...).
 * @return array
 */
function amber_f_repeater( $name, $label, $sub_fields, $args = array() ) {
	return amber_f(
		'repeater',
		$name,
		$label,
		array_merge(
			array(
				'layout'       => 'block',
				'button_label' => __( 'Add Row', 'amber' ),
				'sub_fields'   => $sub_fields,
			),
			$args
		)
	);
}

/**
 * Build a flexible-content layout array.
 *
 * @param string $name       Layout name (machine).
 * @param string $label      Layout label.
 * @param array  $sub_fields Sub-fields shown when the layout is added.
 * @return array
 */
function amber_layout( $name, $label, $sub_fields ) {
	return array(
		'key'        => 'layout_amber_' . sanitize_key( $name ),
		'name'       => $name,
		'label'      => $label,
		'display'    => 'block',
		'sub_fields' => $sub_fields,
	);
}

/**
 * Standard "section header" sub-fields reused by most layouts (eyebrow,
 * heading, subheading, alignment). Returned as an array of field arrays.
 *
 * @param string $prefix Unique prefix to keep keys distinct per layout.
 * @return array
 */
function amber_section_header_fields( $prefix ) {
	return array(
		amber_f_text( $prefix . '_eyebrow', __( 'Eyebrow / Label', 'amber' ), array( 'placeholder' => __( 'Why Amber', 'amber' ) ) ),
		amber_f_text( $prefix . '_heading', __( 'Heading', 'amber' ) ),
		amber_f_textarea( $prefix . '_subheading', __( 'Subheading', 'amber' ) ),
		amber_f_select(
			$prefix . '_align',
			__( 'Alignment', 'amber' ),
			array(
				'center' => __( 'Centered', 'amber' ),
				'left'   => __( 'Left', 'amber' ),
			),
			array( 'default_value' => 'center' )
		),
	);
}

/**
 * Common per-section appearance controls (background, spacing).
 *
 * @param string $prefix Prefix for keys.
 * @return array
 */
function amber_section_style_fields( $prefix ) {
	return array(
		amber_f_select(
			$prefix . '_bg',
			__( 'Background', 'amber' ),
			array(
				'white'    => __( 'White', 'amber' ),
				'surface'  => __( 'Light Surface', 'amber' ),
				'ink'      => __( 'Dark (Ink)', 'amber' ),
				'gradient' => __( 'Brand Gradient', 'amber' ),
			),
			array( 'default_value' => 'white', 'wrapper' => array( 'width' => '33' ) )
		),
		amber_f_select(
			$prefix . '_spacing',
			__( 'Vertical Spacing', 'amber' ),
			array(
				'normal' => __( 'Normal', 'amber' ),
				'large'  => __( 'Large', 'amber' ),
				'small'  => __( 'Compact', 'amber' ),
				'none'   => __( 'None', 'amber' ),
			),
			array( 'default_value' => 'normal', 'wrapper' => array( 'width' => '33' ) )
		),
		amber_f_text( $prefix . '_anchor', __( 'Anchor ID', 'amber' ), array( 'instructions' => __( 'Optional #id for in-page links.', 'amber' ), 'wrapper' => array( 'width' => '34' ) ) ),
	);
}
