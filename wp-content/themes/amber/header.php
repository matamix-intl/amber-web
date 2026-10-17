<?php
/**
 * The site header.
 *
 * Outputs the document head, skip link, optional announcement bar and the
 * sticky header (logo, primary nav with dropdowns, CTA button, mobile toggle).
 * The header shrinks on scroll — see assets/js/modules/navigation.js.
 *
 * @package Amber
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php // Swap no-js -> js before first paint so reveal styles apply without a flash. ?>
	<script>document.documentElement.className=document.documentElement.className.replace(/\bno-js\b/,'js');</script>
	<?php
	// Favicon from Theme Settings (falls back to the WP site icon).
	$amber_favicon = amber_option( 'favicon' );
	if ( is_array( $amber_favicon ) && ! empty( $amber_favicon['url'] ) ) :
		?>
		<link rel="icon" href="<?php echo esc_url( $amber_favicon['url'] ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="amber-skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'amber' ); ?></a>

<?php
// ---------------------------------------------------------------- Announcement
if ( amber_option( 'announcement_enabled' ) ) :
	$amber_ann_text = amber_option( 'announcement_text' );
	$amber_ann_link = amber_option( 'announcement_link' );
	if ( $amber_ann_text ) :
		?>
		<div class="announcement-bar">
			<div class="container announcement-bar__inner">
				<p class="announcement-bar__text">
					<?php echo esc_html( $amber_ann_text ); ?>
					<?php if ( is_array( $amber_ann_link ) && ! empty( $amber_ann_link['url'] ) ) : ?>
						<a class="announcement-bar__link" href="<?php echo esc_url( $amber_ann_link['url'] ); ?>">
							<?php echo esc_html( ! empty( $amber_ann_link['title'] ) ? $amber_ann_link['title'] : __( 'Learn more', 'amber' ) ); ?>
							<?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG. ?>
						</a>
					<?php endif; ?>
				</p>
			</div>
		</div>
		<?php
	endif;
endif;
?>

<?php
// Header CTA ("Contact us") + whether to render the designed default nav.
// Defaults to the real Contact page (resolved by page template) so the
// button always goes somewhere even when no ACF "Header Button" is set;
// falls back to the #contact anchor only if no Contact page exists.
$amber_cta         = amber_option( 'header_cta', array( 'title' => __( 'Contact us', 'amber' ), 'url' => amber_template_page_url( 'contact', '#contact' ), 'target' => '' ) );
$amber_has_menu    = has_nav_menu( 'primary' );
$amber_overlay     = is_front_page(); // Transparent over the dark hero on the home page.
$amber_header_class = 'site-header' . ( $amber_overlay ? ' site-header--overlay' : '' );

// Products dropdown — the four product pages, resolved by page template.
$amber_services = array(
	array( 'label' => __( 'Amber ERP Software', 'amber' ),    'url' => amber_template_page_url( 'amber-erp' ) ),
	array( 'label' => __( 'Amber Restaurant Software', 'amber' ),       'url' => amber_template_page_url( 'zoomie' ) ),
	array( 'label' => __( 'Amber Laundry Software', 'amber' ),       'url' => amber_template_page_url( 'gozzbe' ) ),
	array( 'label' => __( 'Amber POS Software', 'amber' ), 'url' => amber_template_page_url( 'pos' ) ),
);
?>
<header id="site-header" class="<?php echo esc_attr( $amber_header_class ); ?>" data-shrink-on-scroll>
	<div class="site-header__inner">

		<div class="site-header__brand">
			<?php
			$amber_logo = amber_option( 'logo' );
			if ( is_array( $amber_logo ) && ! empty( $amber_logo['ID'] ) ) :
				?>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php amber_image( $amber_logo, 'amber-logo', array( 'class' => 'site-logo__img', 'loading' => 'eager', 'alt' => get_bloginfo( 'name' ) ) ); ?>
				</a>
			<?php elseif ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php amber_brand_lockup(); ?>
				</a>
			<?php endif; ?>
		</div>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'amber' ); ?>">
			<?php if ( $amber_has_menu ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-nav__menu',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			<?php else : ?>
				<?php // Designed default navigation with the Services dropdown. ?>
				<ul class="site-nav__menu">
					<li class="nav-item<?php echo is_front_page() ? ' current-menu-item' : ''; ?>"><a class="nav-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'amber' ); ?></a></li>

					<li class="nav-item"><a class="nav-link" href="<?php echo esc_url( amber_template_page_url( 'about' ) ); ?>"><?php esc_html_e( 'About Us', 'amber' ); ?></a></li>

					<li class="nav-item nav-item--mega" data-mega>
						<button class="nav-link nav-link--mega" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="nav-services" data-mega-trigger>
							<?php esc_html_e( 'Products', 'amber' ); ?>
							<span class="nav-link__caret" aria-hidden="true"><?php echo amber_icon_svg( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</button>

						<div class="mega mega--list" id="nav-services" data-mega-panel>
							<div class="mega__inner">
								<ul class="mega-list">
									<?php foreach ( $amber_services as $amber_s ) : ?>
										<li class="mega-list__item">
											<a class="mega-list__link" href="<?php echo esc_url( $amber_s['url'] ); ?>">
												<?php echo esc_html( $amber_s['label'] ); ?>
												<span class="mega-list__arrow" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</li>
				</ul>
			<?php endif; ?>
		</nav>

		<div class="site-header__actions">
			<a class="btn btn--primary site-header__cta" href="<?php echo esc_url( ! empty( $amber_cta['url'] ) ? $amber_cta['url'] : '#contact' ); ?>"<?php echo ( ! empty( $amber_cta['target'] ) ? ' target="' . esc_attr( $amber_cta['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
				<?php echo esc_html( ! empty( $amber_cta['title'] ) ? $amber_cta['title'] : __( 'Contact us', 'amber' ) ); ?>
			</a>

			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e( 'Open menu', 'amber' ); ?>" data-nav-toggle>
				<span class="nav-toggle__open"><?php echo amber_icon_svg( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="nav-toggle__close"><?php echo amber_icon_svg( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</button>
		</div>
	</div>
</header>

<!-- Mobile drawer — kept OUTSIDE the floating header so its position:fixed
     targets the viewport, not the transform-containing pill. -->
<div id="mobile-menu" class="mobile-menu" data-mobile-menu hidden>
		<div class="mobile-menu__panel">
			<?php if ( $amber_has_menu ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mobile-menu__list',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			<?php else : ?>
				<ul class="mobile-menu__list">
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'amber' ); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( amber_template_page_url( 'about' ) ); ?>"><?php esc_html_e( 'About Us', 'amber' ); ?></a></li>
					<li class="menu-item menu-item-has-children">
						<span class="menu-item__label"><?php esc_html_e( 'Products', 'amber' ); ?></span>
						<ul class="sub-menu">
							<?php foreach ( $amber_services as $amber_s ) : ?>
								<li class="menu-item"><a href="<?php echo esc_url( $amber_s['url'] ); ?>"><?php echo esc_html( $amber_s['label'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>
			<?php endif; ?>
			<div class="mobile-menu__actions">
				<?php amber_button( $amber_cta, 'primary', array( 'class' => 'btn--block' ) ); ?>
			</div>
		</div>
</div><!-- #mobile-menu -->

<main id="main" class="site-main" role="main">
