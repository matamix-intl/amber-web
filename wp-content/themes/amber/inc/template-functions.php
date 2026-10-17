<?php
/**
 * Template functions: SEO, schema, breadcrumbs, body classes, pagination.
 *
 * These are presentation helpers used by the templates. SEO output is written
 * defensively so it co-exists with a dedicated SEO plugin (Yoast/RankMath):
 * if one is active we step aside to avoid duplicate tags.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/**
 * Detect whether a major SEO plugin is handling meta output.
 *
 * @return bool
 */
function amber_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'The_SEO_Framework\\Load' );
}

/**
 * Output Open Graph + Twitter Card meta when no SEO plugin is present.
 *
 * @return void
 */
function amber_seo_meta() {
	if ( amber_seo_plugin_active() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = amber_meta_description();
	$url         = home_url( add_query_arg( null, null ) );
	$site_name   = get_bloginfo( 'name' );
	$image       = amber_social_image();
	$type        = is_singular( 'post' ) ? 'article' : 'website';
	?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>">
	<meta property="og:type" content="<?php echo esc_attr( $type ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
	<?php if ( $image ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $image ); ?>">
		<meta name="twitter:card" content="summary_large_image">
	<?php else : ?>
		<meta name="twitter:card" content="summary">
	<?php endif; ?>
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
	<?php if ( $image ) : ?>
		<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>">
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'amber_seo_meta', 5 );

/**
 * Build a reasonable meta description for the current view.
 *
 * @return string
 */
function amber_meta_description() {
	$desc = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( $post->post_content );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$desc = term_description();
	} else {
		$desc = get_bloginfo( 'description' );
	}

	$desc = wp_strip_all_tags( $desc );
	return mb_strimwidth( trim( $desc ), 0, 160, '…' );
}

/**
 * Resolve a social-share image URL (featured image, else options default).
 *
 * @return string
 */
function amber_social_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( get_queried_object_id(), 'amber-wide' );
	}
	$default = amber_option( 'default_social_image' );
	if ( is_array( $default ) && ! empty( $default['url'] ) ) {
		return $default['url'];
	}
	return '';
}

/**
 * Output Organization + WebSite JSON-LD schema in the head.
 *
 * @return void
 */
function amber_schema_org() {
	if ( amber_seo_plugin_active() ) {
		return;
	}

	$logo = amber_option( 'logo' );
	$logo_url = is_array( $logo ) && ! empty( $logo['url'] ) ? $logo['url'] : '';

	$social = array();
	foreach ( (array) amber_option( 'social_links', array() ) as $row ) {
		if ( ! empty( $row['url'] ) ) {
			$social[] = $row['url'];
		}
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'  => 'Organization',
				'@id'    => home_url( '/#organization' ),
				'name'   => get_bloginfo( 'name' ),
				'url'    => home_url( '/' ),
				'logo'   => $logo_url,
				'sameAs' => array_values( $social ),
			),
			array(
				'@type'     => 'WebSite',
				'@id'       => home_url( '/#website' ),
				'url'       => home_url( '/' ),
				'name'      => get_bloginfo( 'name' ),
				'publisher' => array( '@id' => home_url( '/#organization' ) ),
			),
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'amber_schema_org', 6 );

/**
 * Accessible breadcrumb trail with BreadcrumbList schema.
 *
 * @return void
 */
function amber_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$items = array();
	$items[] = array( 'name' => __( 'Home', 'amber' ), 'url' => home_url( '/' ) );

	if ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( $cats ) {
			$items[] = array( 'name' => $cats[0]->name, 'url' => get_category_link( $cats[0]->term_id ) );
		}
		$items[] = array( 'name' => get_the_title(), 'url' => '' );
	} elseif ( is_page() ) {
		$post = get_queried_object();
		if ( $post->post_parent ) {
			$parent  = get_post( $post->post_parent );
			$items[] = array( 'name' => get_the_title( $parent ), 'url' => get_permalink( $parent ) );
		}
		$items[] = array( 'name' => get_the_title(), 'url' => '' );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( 'name' => post_type_archive_title( '', false ), 'url' => '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = array( 'name' => single_term_title( '', false ), 'url' => '' );
	} elseif ( is_search() ) {
		$items[] = array( 'name' => __( 'Search results', 'amber' ), 'url' => '' );
	} elseif ( is_404() ) {
		$items[] = array( 'name' => __( 'Not found', 'amber' ), 'url' => '' );
	} elseif ( is_archive() ) {
		$items[] = array( 'name' => get_the_archive_title(), 'url' => '' );
	}

	// Markup.
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'amber' ) . '"><ol class="breadcrumbs__list">';
	$ld    = array();
	$total = count( $items );
	foreach ( $items as $i => $item ) {
		$position = $i + 1;
		$is_last  = ( $position === $total );

		echo '<li class="breadcrumbs__item">';
		if ( $item['url'] && ! $is_last ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item['name'] ) . '</span>';
		}
		echo '</li>';

		$ld[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $item['name'],
			'item'     => $item['url'] ? $item['url'] : home_url( add_query_arg( null, null ) ),
		);
	}
	echo '</ol></nav>';

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $ld,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>';
}

/**
 * Add helpful body classes for styling hooks.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function amber_body_classes( $classes ) {
	if ( ! is_singular() || ! is_active_sidebar( 'sidebar-blog' ) ) {
		$classes[] = 'has-no-sidebar';
	}
	if ( is_page_template( array( 'page-templates/page-full-width.php' ) ) || is_front_page() ) {
		$classes[] = 'is-full-width';
	}
	// Pages that lead with a full-bleed banner let it run to the very top of the
	// window, with the floating header sitting over it — so they drop the body's
	// top offset. Everything else keeps the offset and starts below the header.
	// Both the home hero and the Contact banner carry the aurora backdrop, which
	// only reads right if the colour starts at the top edge rather than under a
	// white band the height of the header.
	if ( is_front_page() || is_page_template( 'page-templates/page-contact.php' ) ) {
		$classes[] = 'has-overlay-hero';
	}
	$classes[] = 'amber';
	return $classes;
}
add_filter( 'body_class', 'amber_body_classes' );

/**
 * Estimate reading time for the current post (~200 wpm).
 *
 * @return string
 */
function amber_reading_time() {
	$words   = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: estimated reading time in minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'amber' ), $minutes );
}

/**
 * Numbered, accessible pagination for archives.
 *
 * @return void
 */
function amber_pagination() {
	$links = paginate_links(
		array(
			'mid_size'  => 1,
			'prev_text' => __( '&larr; Prev', 'amber' ),
			'next_text' => __( 'Next &rarr;', 'amber' ),
			'type'      => 'array',
		)
	);

	if ( ! $links ) {
		return;
	}

	echo '<nav class="pagination" aria-label="' . esc_attr__( 'Posts navigation', 'amber' ) . '"><ul class="pagination__list">';
	foreach ( $links as $link ) {
		echo '<li class="pagination__item">' . wp_kses_post( $link ) . '</li>';
	}
	echo '</ul></nav>';
}
