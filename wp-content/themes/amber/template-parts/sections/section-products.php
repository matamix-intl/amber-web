<?php
/**
 * Section: Products Showcase — Amber Technologies' four products.
 *
 * A vertical card sequence: every product pins to the top of the viewport as
 * you scroll, and the next one rides up and lands exactly over it. The card
 * being covered scales down and dims — it reads as receding into the screen
 * while its successor arrives in front of it — and is then hidden completely,
 * leaving no trace of the previous product behind the current one.
 *
 * The pinning is pure CSS (position: sticky, one card per scroll-length), so
 * the stack works with JavaScript off — modules/products-stack.js only adds
 * the recede, driven by how far the *next* card has travelled. Under
 * prefers-reduced-motion the scaling is skipped and the cards simply stack.
 *
 * @package Amber
 */

// Live sub-fields (Pro flexible-content row) fall back to the numbered ACF
// Free fields (products{n}_*), which in turn fall back to the shared demo
// defaults — see amber_home_products_defaults() in inc/helpers.php.
$products_row = amber_sub( 'products_items', null );
if ( is_array( $products_row ) ) {
	$products = $products_row;
} else {
	$products = array();
	foreach ( amber_home_products_defaults() as $i => $d ) {
		$products[] = array(
			'name'   => amber_sub( "products{$i}_name", $d['name'] ),
			'icon'   => amber_sub( "products{$i}_icon", $d['icon'] ),
			'accent' => amber_sub( "products{$i}_accent", $d['accent'] ),
			'image'  => amber_sub( "products{$i}_image", 0 ),
			'desc'   => amber_sub( "products{$i}_desc", $d['desc'] ),
			'desc2'  => amber_sub( "products{$i}_desc2", $d['desc2'] ),
			'link'   => array(
				'url'   => amber_sub( "products{$i}_link_url", $d['link']['url'] ),
				'title' => amber_sub( "products{$i}_link_label", $d['link']['title'] ),
			),
		);
	}
}
$products_has_anchor = (bool) amber_sub( 'products_anchor', '' );
?>
<?php
// Dark section, not a dark panel. The images recede into this background as
// they zoom away, so the ground they leave behind is the section itself and
// there is no floating frame around them — the same reason the reference
// design sits on a black page.
?>
<section <?php echo amber_section_attrs( 'products', 'ink' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo $products_has_anchor ? '' : ' id="products"'; ?>>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'products_eyebrow', __( 'Our Products', 'amber' ) ),
				'heading'    => amber_sub( 'products_heading', __( 'Featured Projects', 'amber' ) ),
				'subheading' => amber_sub( 'products_subheading', __( 'Designed to simplify operations, increase efficiency and help your business grow.', 'amber' ) ),
				'align'      => 'center',
			)
		);
		?>

	</div>

	<?php
	// The cards don't move in the page at all — they are stacked in one place
	// inside a pinned stage and travel along the Z axis instead, so scrolling
	// reads as moving through them rather than sliding them past each other.
	// The wrapper's height is what gives that travel its scroll length.
	$products     = array_values( (array) $products );
	$product_count = count( $products );
	?>
	<div class="pstack" data-products-stack style="--pstack-count: <?php echo (int) $product_count; ?>">
		<div class="pstack__stage" data-products-stack-stage>
		<?php foreach ( $products as $product_i => $product ) :
			$accent = ! empty( $product['accent'] ) ? $product['accent'] : '#0ea5e9';

			// The ACF image field (return_format 'array') gives an attachment
			// array; the demo defaults give a bare attachment ID (0 = none).
			$product_image_id = is_array( $product['image'] ?? null ) ? ( $product['image']['ID'] ?? 0 ) : (int) ( $product['image'] ?? 0 );
			?>
			<article
				class="pstack__card"
				data-products-stack-card
				style="--i: <?php echo (int) $product_i; ?>; --accent: <?php echo esc_attr( $accent ); ?>">

				<div class="pstack__media">
					<?php if ( $product_image_id ) : ?>
						<?php
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput
							$product_image_id,
							'large',
							false,
							array(
								'class'   => 'pstack__img',
								'alt'     => esc_attr( $product['name'] ?? '' ),
								// The first card is above the fold on most screens.
								'loading' => 0 === $product_i ? 'eager' : 'lazy',
							)
						);
						?>
					<?php else : ?>
						<?php // No image set: a branded panel carrying the product icon. ?>
						<div class="pstack__placeholder">
							<span aria-hidden="true"><?php echo amber_icon_svg( $product['icon'] ?: 'layers' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</div>
					<?php endif; ?>
					<span class="pstack__scrim" aria-hidden="true"></span>
				</div>

				<div class="pstack__body">
					<span class="pstack__index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $product_i + 1 ) ); ?></span>

					<?php if ( ! empty( $product['name'] ) ) : ?>
						<span class="pstack__pill"><?php echo esc_html( $product['name'] ); ?></span>
					<?php endif; ?>

					<h3 class="pstack__title"><?php echo esc_html( $product['name'] ?? '' ); ?></h3>

					<?php if ( ! empty( $product['desc'] ) ) : ?>
						<p class="pstack__desc"><?php echo esc_html( $product['desc'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $product['link']['url'] ) ) : ?>
						<a class="pstack__cta" href="<?php echo esc_url( $product['link']['url'] ); ?>">
							<?php echo esc_html( $product['link']['title'] ?: __( 'View Details', 'amber' ) ); ?>
							<span class="pstack__cta-icon" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</a>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div>
</section>
