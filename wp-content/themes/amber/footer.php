<?php
/**
 * The site footer — compact centred stack:
 * logo lockup, a single row of pipe-separated links, circular social icons,
 * a hairline rule and the copyright line.
 *
 * Links: the registered footer menus (footer_quick / footer_company) are
 * flattened into the single row when set; otherwise curated demo links render
 * so the footer always looks complete. Social icons read the Theme Settings
 * options and fall back to placeholder circles.
 *
 * @package Amber
 */

?>
</main><!-- #main -->

<footer class="site-footer" role="contentinfo">
	<div class="container">

		<div class="site-footer__brand">
			<?php
			// A Theme Settings "Footer Logo" wins; otherwise fall back to the
			// Amber wordmark in the media library. Built from the uploads URL
			// rather than hard-coded so it survives a move off localhost.
			$amber_flogo = amber_option( 'footer_logo' );
			if ( is_array( $amber_flogo ) && ! empty( $amber_flogo['ID'] ) ) :
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo" rel="home">
					<?php amber_image( $amber_flogo, 'amber-logo', array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
				</a>
				<?php
			else :
				$amber_uploads  = wp_get_upload_dir();
				$amber_logo_url = trailingslashit( $amber_uploads['baseurl'] ) . '2026/07/cropped-Amber-LOGO-scaled-1.png';
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<img src="<?php echo esc_url( $amber_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="2220" height="681" loading="lazy" decoding="async">
				</a>
			<?php endif; ?>
		</div>

		<?php
		// One centred row of links. Assigned menus win; otherwise these curated
		// defaults render. Only link to pages that actually exist on a fresh
		// install — no Careers/Blog page exists yet, so those are omitted
		// rather than pointed at a misleading destination.
		$amber_footer_links = array(
			array( __( 'Home', 'amber' ), home_url( '/' ) ),
			array( __( 'Amber ERP Software', 'amber' ), home_url( '/amber-erp/' ) ),
			array( __( 'Amber POS Software', 'amber' ), home_url( '/pos-software/' ) ),
			array( __( 'Amber Restaurant Software', 'amber' ), home_url( '/zoomie-restaurant/' ) ),
			array( __( 'Amber Laundry Software', 'amber' ), home_url( '/gozzbe-laundry/' ) ),
			array( __( 'About Us', 'amber' ), home_url( '/about-us/' ) ),
			array( __( 'Contact Us', 'amber' ), home_url( '/contact-us/' ) ),
		);
		?>
		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'amber' ); ?>">
			<?php
			// The two footer menu locations flatten into the same single row,
			// so an admin can still curate the links without regaining columns.
			$amber_menu_locs = array_filter( array( 'footer_quick', 'footer_company' ), 'has_nav_menu' );

			if ( $amber_menu_locs ) {
				foreach ( $amber_menu_locs as $amber_loc ) {
					wp_nav_menu(
						array(
							'theme_location' => $amber_loc,
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}
			} else {
				echo '<ul class="footer-menu">';
				foreach ( $amber_footer_links as $amber_l ) {
					echo '<li><a href="' . esc_url( $amber_l[1] ) . '">' . esc_html( $amber_l[0] ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</nav>

		<?php
		// Circular social icons, centred. amber_social_profiles() merges the
		// Theme Settings values over the official profiles, so this row and the
		// contact panel always show the identical set. A network left at '#'
		// renders as a non-clickable circle.
		$amber_socials = amber_social_profiles();
		?>
		<ul class="social-list" aria-label="<?php esc_attr_e( 'Social media', 'amber' ); ?>">
			<?php foreach ( $amber_socials as $amber_net => $amber_url ) : ?>
				<?php $amber_is_placeholder = '#' === $amber_url; ?>
				<li>
					<?php if ( $amber_is_placeholder ) : ?>
						<span class="social-list__link" aria-hidden="true">
							<?php echo amber_social_svg( $amber_net ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG. ?>
						</span>
					<?php else : ?>
						<a class="social-list__link" href="<?php echo esc_url( $amber_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( 'twitter' === $amber_net ? __( 'Twitter / X', 'amber' ) : ucfirst( $amber_net ) ); ?>">
							<?php echo amber_social_svg( $amber_net ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG. ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="site-footer__rule" role="none"></div>

		<p class="site-footer__copy">
			<?php
			$amber_copy = amber_option( 'copyright', __( '©Copyright. All rights reserved.', 'amber' ) );
			echo esc_html( str_replace( '%year%', gmdate( 'Y' ), $amber_copy ) );
			?>
		</p>

		<?php
		if ( has_nav_menu( 'legal' ) ) {
			wp_nav_menu( array( 'theme_location' => 'legal', 'container' => 'nav', 'container_class' => 'site-footer__legal', 'menu_class' => 'legal-menu', 'depth' => 1, 'fallback_cb' => false ) );
		}
		?>

	</div>
</footer>

<?php get_template_part( 'template-parts/components/demo-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
