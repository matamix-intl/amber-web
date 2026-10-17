<?php
/**
 * Template Name: Legal (Privacy / Terms)
 *
 * Long-form legal document layout: a large title, the effective/updated dates
 * pulled out of the body, then the content in a readable measure alongside a
 * sticky table of contents built from the document's own headings.
 *
 * The page content itself is untouched — headings are given ids and the TOC is
 * derived from them at render time, so editors keep writing plain Gutenberg
 * blocks and the navigation stays in sync automatically.
 *
 * @package Amber
 */

get_header();

while ( have_posts() ) :
	the_post();

	$amber_content = apply_filters( 'the_content', get_the_content() );

	// Pull the "Effective Date / Last Updated" line out of the body so it can
	// be presented as a meta block under the title (as in the reference).
	// Only a leading paragraph qualifies, so body copy is never stripped.
	$amber_dates = '';
	if ( preg_match( '#^\s*<p[^>]*>\s*(?:<(?:em|strong|b)>\s*)*((?:Effective Date|Last updated|Last Updated)[^<]*(?:<br\s*/?>(?:\s*<(?:em|strong|b)>\s*)*[^<]*)*)(?:\s*</(?:em|strong|b)>)*\s*</p>#i', $amber_content, $amber_m ) ) {
		$amber_dates   = $amber_m[1];
		$amber_content = substr( $amber_content, strlen( $amber_m[0] ) );
	}

	// Give every h2/h3 a stable id and collect them for the TOC.
	$amber_toc  = array();
	$amber_seen = array();

	$amber_content = preg_replace_callback(
		'#<h([23])([^>]*)>(.*?)</h\1>#is',
		function ( $m ) use ( &$amber_toc, &$amber_seen ) {
			$level = (int) $m[1];
			$attrs = $m[2];
			$label = trim( wp_strip_all_tags( $m[3] ) );

			if ( '' === $label ) {
				return $m[0];
			}

			// Reuse an author-supplied id when present; otherwise slugify the
			// heading text, de-duplicating so anchors stay unique.
			if ( preg_match( '#\sid=["\']([^"\']+)["\']#i', $attrs, $id_m ) ) {
				$id = $id_m[1];
			} else {
				$id = sanitize_title( $label );
				if ( '' === $id ) {
					$id = 'section';
				}
				if ( isset( $amber_seen[ $id ] ) ) {
					$amber_seen[ $id ]++;
					$id .= '-' . $amber_seen[ $id ];
				} else {
					$amber_seen[ $id ] = 1;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}

			$amber_toc[] = array(
				'id'    => $id,
				'label' => $label,
				'level' => $level,
			);

			return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
		},
		$amber_content
	);
	?>

	<article <?php post_class( 'legal-page' ); ?>>

		<header class="legal-hero">
			<div class="container">
				<?php amber_breadcrumbs(); ?>
				<h1 class="legal-hero__title"><?php the_title(); ?></h1>

				<?php if ( $amber_dates ) : ?>
					<p class="legal-hero__dates"><?php echo wp_kses( $amber_dates, array( 'br' => array() ) ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<div class="legal-body">
			<div class="container legal-body__inner<?php echo empty( $amber_toc ) ? ' legal-body__inner--solo' : ''; ?>">

				<?php if ( ! empty( $amber_toc ) ) : ?>
					<aside class="legal-toc" aria-labelledby="legal-toc-title">
						<div class="legal-toc__inner">
							<p class="legal-toc__title" id="legal-toc-title"><?php esc_html_e( 'On this page', 'amber' ); ?></p>
							<nav aria-labelledby="legal-toc-title">
								<ol class="legal-toc__list">
									<?php foreach ( $amber_toc as $amber_item ) : ?>
										<li class="legal-toc__item legal-toc__item--h<?php echo (int) $amber_item['level']; ?>">
											<a href="#<?php echo esc_attr( $amber_item['id'] ); ?>"><?php echo esc_html( $amber_item['label'] ); ?></a>
										</li>
									<?php endforeach; ?>
								</ol>
							</nav>
						</div>
					</aside>
				<?php endif; ?>

				<div class="legal-content entry-content">
					<?php
					echo wp_kses_post( $amber_content );

					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'amber' ) . '">' . esc_html__( 'Pages:', 'amber' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>

			</div>
		</div>

	</article>

	<?php
endwhile;

get_footer();
