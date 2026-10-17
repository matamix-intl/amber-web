<?php
/**
 * Section: Hero — centered aurora banner with product previews.
 *
 * A clean, 100vh, centered hero: white stage with soft teal/cyan aurora
 * gradients glowing in from the top two corners; a large centered headline,
 * muted subtitle and two pill CTAs. Pure CSS — no images, no slider JS.
 *
 * @package Amber
 */

$heading   = amber_sub( 'hero_heading', __( 'Helping Businesses Grow Through Smart ERP, POS & Business Management Solutions', 'amber' ) );
$desc      = amber_sub( 'hero_subheading', __( 'From ERP implementation to digital growth strategies, we help businesses automate operations, improve visibility, and scale with confidence.', 'amber' ) );
$primary   = amber_sub( 'hero_primary_btn', array( 'title' => __( "Let's talk now", 'amber' ), 'url' => home_url( '/contact-us/' ), 'target' => '' ) );
$secondary = amber_sub( 'hero_secondary_btn', array( 'title' => __( 'View Products', 'amber' ), 'url' => home_url( '/#products' ), 'target' => '' ) );
?>
<section class="section section--hero hero-aur">
	<?php // Shared aurora backdrop — the Contact page banner uses the same one. ?>
	<div class="aurora-bg" aria-hidden="true">
		<span class="aurora-bg__glow aurora-bg__glow--left"></span>
		<span class="aurora-bg__glow aurora-bg__glow--right"></span>
	</div>

	<div class="hero-aur__inner">
		<h1 class="hero-aur__title"><?php echo esc_html( $heading ); ?></h1>

		<?php if ( ! empty( $desc ) ) : ?>
			<p class="hero-aur__desc"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>

		<div class="hero-aur__actions">
			<?php if ( ! empty( $primary['url'] ) ) : ?>
				<a class="btn btn--lg hero-aur__cta-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php echo ( ! empty( $primary['target'] ) ? ' target="' . esc_attr( $primary['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
					<?php echo esc_html( ! empty( $primary['title'] ) ? $primary['title'] : __( "Let's talk now", 'amber' ) ); ?>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $secondary['url'] ) ) : ?>
				<a class="btn btn--lg hero-aur__cta-secondary" href="<?php echo esc_url( $secondary['url'] ); ?>"<?php echo ( ! empty( $secondary['target'] ) ? ' target="' . esc_attr( $secondary['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
					<?php echo esc_html( ! empty( $secondary['title'] ) ? $secondary['title'] : __( 'View Products', 'amber' ) ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>

</section>
