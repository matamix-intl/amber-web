<?php
/**
 * Amber ERP Software — page body (service page).
 *
 * Hero banner: full-bleed background-image panel with a dark overlay and a
 * single left-aligned text column (reference: Digitz "Transform Your
 * Business" hero — background-image on the section itself, no separate
 * image column). Copy is editable via amber_sub(); the background image is
 * editable via the "Hero background image" ACF field.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

// Which product is this page for? (erp | zoomie | gozzbe | pos — set by the
// page template.) The config drives the hero defaults; $erp_t() rewrites the
// shared ERP-flavoured defaults for the current product ("Amber ERP Software" → name,
// "ERP" → short), so every sibling page reads naturally out of the box.
$erp_product = amber_erp_page_product();
$erp_t       = static function ( $text ) use ( $erp_product ) {
	return str_replace( array( 'Amber ERP Software', 'ERP' ), array( $erp_product['name'], $erp_product['short'] ), $text );
};

// Editable copy (falls back to the product's defaults).
$erp_heading_accent = amber_sub( 'erp_heading_accent', $erp_product['hero_accent'] );
$erp_heading_rest   = amber_sub( 'erp_heading_rest', $erp_product['hero_rest'] );
$erp_lead_bold      = amber_sub( 'erp_lead_bold', $erp_product['hero_lead_bold'] );
$erp_lead           = amber_sub( 'erp_lead', $erp_product['hero_lead'] );
$erp_btn1_label = amber_sub( 'erp_btn1_label', __( 'Get Started', 'amber' ) );
$erp_btn1_url   = amber_sub( 'erp_btn1_url', home_url( '/contact-us/' ) );

// Hero background image — editable from the admin ("Amber — ERP Page" group,
// Dashboard & Badges tab) and falls back to the bundled default when the
// field is empty. ACF image fields return arrays; raw URLs also accepted.
$erp_hero_bg_raw = amber_sub( 'erp_hero_bg_image', '' );
if ( is_array( $erp_hero_bg_raw ) && ! empty( $erp_hero_bg_raw['url'] ) ) {
	$erp_hero_bg = $erp_hero_bg_raw['url'];
} elseif ( is_string( $erp_hero_bg_raw ) && '' !== $erp_hero_bg_raw ) {
	$erp_hero_bg = $erp_hero_bg_raw;
} else {
	$erp_hero_bg = content_url( '/uploads/2026/07/ChatGPT-Image-Jul-25-2026-12_48_55-PM.png' );
}
?>

<?php if ( 'gozzbe' === $erp_product['key'] ) : ?>
	<?php
	/* -----------------------------------------------------------------------
	 * Amber Laundry Software hero — centred "CleanCloud"-style banner (light background,
	 * big centred headline, lead paragraph, then a tabbed product-screenshot
	 * showcase). Only Amber Laundry Software uses this layout; the other three products keep
	 * the shared full-bleed background-image banner in the else branch below.
	 *
	 * On first load a laundry-basket SVG animation plays over the hero, then
	 * the headline expands into place — see modules/gozzbe-hero.js.
	 * -------------------------------------------------------------------- */
	$gz_heading = amber_sub( 'erp_gz_heading', __( "Launch, Manage and Scale Your Laundry\nfrom One Platform", 'amber' ) );
	$gz_lead    = amber_sub( 'erp_gz_lead', __( 'Amber Laundry Software is your all-in-one solution for managing and scaling your laundromat, dry cleaning, shoe repair and alterations business. Experience seamless operations with our advanced tools designed just for you.', 'amber' ) );

	// The reference design breaks the headline after "…Your Laundry" rather
	// than wherever the browser's own wrapping happens to land — a plain
	// `<h1>` here left it to `text-wrap: balance` (base/_typography.scss),
	// which broke a full line early ("Your" onto line 2) to even out line
	// lengths, not to match this specific layout. A hard break at a known
	// word boundary is the only way to pin the line split exactly, and still
	// degrades safely if the heading text is edited: split on the first space
	// at/after the midpoint so a shorter/longer custom heading still gets a
	// reasonable two-line split instead of one absurdly long line.
	$gz_heading_lines = explode( "\n", $gz_heading, 2 );
	if ( count( $gz_heading_lines ) < 2 ) {
		$gz_mid = (int) floor( mb_strlen( $gz_heading ) / 2 );
		$gz_break_at = mb_strpos( $gz_heading, ' ', $gz_mid );
		if ( false === $gz_break_at ) {
			$gz_heading_lines = array( $gz_heading, '' );
		} else {
			$gz_heading_lines = array(
				mb_substr( $gz_heading, 0, $gz_break_at ),
				mb_substr( $gz_heading, $gz_break_at + 1 ),
			);
		}
	}

	// Showcase tabs. Labels default to the page's own first 7 Key Features
	// headings (erp_ft1_label..erp_ft7_label, further down this same template)
	// rather than a fixed "Point of Sale / Workflows / …" list, so the hero
	// tabs stay in sync with whatever that section actually says — including
	// edits already made on Amber Laundry Software (Order Management, Garment Tracking, Laundry
	// POS, Customer Management, Employee Management, Pickup & Delivery
	// Management). Each tab is still independently overridable via its own
	// erp_gz_tab_{id}_label field below, same as before. Each slot's image is
	// editable; falls back to the bundled placeholder.
	$gz_ft_defaults = amber_erp_feature_defaults();
	$gz_tabs        = array(
		array( 'id' => 'pos',       'label' => amber_sub( 'erp_ft1_label', $erp_t( $gz_ft_defaults[1]['label'] ) ) ),
		array( 'id' => 'workflows', 'label' => amber_sub( 'erp_ft2_label', $erp_t( $gz_ft_defaults[2]['label'] ) ) ),
		array( 'id' => 'pickup',    'label' => amber_sub( 'erp_ft3_label', $erp_t( $gz_ft_defaults[3]['label'] ) ) ),
		array( 'id' => 'website',   'label' => amber_sub( 'erp_ft4_label', $erp_t( $gz_ft_defaults[4]['label'] ) ) ),
		array( 'id' => 'marketing', 'label' => amber_sub( 'erp_ft5_label', $erp_t( $gz_ft_defaults[5]['label'] ) ) ),
		array( 'id' => 'reporting', 'label' => amber_sub( 'erp_ft6_label', $erp_t( $gz_ft_defaults[6]['label'] ) ) ),
		array( 'id' => 'plant',     'label' => amber_sub( 'erp_ft7_label', $erp_t( $gz_ft_defaults[7]['label'] ) ) ),
	);

	$gz_placeholder = get_template_directory_uri() . '/assets/images/placeholder.svg';
	foreach ( $gz_tabs as $gz_i => $gz_tab ) {
		$gz_img_raw = amber_sub( 'erp_gz_tab_' . $gz_tab['id'] . '_image', '' );
		if ( is_array( $gz_img_raw ) && ! empty( $gz_img_raw['url'] ) ) {
			$gz_tabs[ $gz_i ]['image'] = $gz_img_raw['url'];
		} elseif ( is_string( $gz_img_raw ) && '' !== $gz_img_raw ) {
			$gz_tabs[ $gz_i ]['image'] = $gz_img_raw;
		} else {
			$gz_tabs[ $gz_i ]['image'] = $gz_placeholder;
		}
		$gz_tabs[ $gz_i ]['label'] = amber_sub( 'erp_gz_tab_' . $gz_tab['id'] . '_label', $gz_tab['label'] );
	}

	// The third tab ("Pickup and Delivery") is active first, matching the
	// reference design.
	$gz_active = 'pickup';
	?>
	<section class="gz-hero" data-gz-hero>

		<?php
		// Intro overlay: laundry-basket video on first load only. The video's
		// own `ended` event drives the reveal in gozzbe-hero.js — no fixed
		// timer — so it's editable without touching JS. Falls back to the
		// bundled SVG animation if the field is empty or the uploaded file
		// isn't there.
		$gz_intro_video_raw = amber_sub( 'erp_gz_intro_video', '' );
		if ( is_array( $gz_intro_video_raw ) && ! empty( $gz_intro_video_raw['url'] ) ) {
			$gz_intro_video = $gz_intro_video_raw['url'];
		} elseif ( is_string( $gz_intro_video_raw ) && '' !== $gz_intro_video_raw ) {
			$gz_intro_video = $gz_intro_video_raw;
		} else {
			$gz_intro_video = content_url( '/uploads/2026/07/Laundry-Basket.webm' );
		}
		?>
		<div class="gz-hero__intro" data-gz-intro aria-hidden="true">
			<?php if ( $gz_intro_video ) : ?>
				<video
					class="gz-hero__intro-video"
					data-gz-intro-video
					src="<?php echo esc_url( $gz_intro_video ); ?>"
					autoplay
					muted
					playsinline
					preload="auto"></video>
			<?php else : ?>
				<div class="gz-hero__intro-art">
					<?php
					// Fallback: inline the bundled animated SVG so its SMIL animation runs.
					$gz_svg_path = get_template_directory() . '/assets/images/gozzbe/laundry-basket.svg';
					if ( file_exists( $gz_svg_path ) ) {
						// phpcs:ignore WordPress.Security.EscapeOutput -- bundled theme asset.
						echo file_get_contents( $gz_svg_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
					}
					?>
				</div>
			<?php endif; ?>
		</div>

		<div class="gz-hero__inner">
			<h1 class="gz-hero__title" data-gz-title><?php echo esc_html( $gz_heading_lines[0] ); ?><?php if ( ! empty( $gz_heading_lines[1] ) ) : ?><br><?php echo esc_html( $gz_heading_lines[1] ); ?><?php endif; ?></h1>
			<p class="gz-hero__lead" data-gz-lead><?php echo esc_html( $gz_lead ); ?></p>

			<div class="gz-tabs" data-gz-tabs>
				<div class="gz-tabs__nav-wrap">
					<div class="gz-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Product highlights', 'amber' ); ?>">
						<?php foreach ( $gz_tabs as $gz_tab ) : ?>
							<?php $gz_is_active = ( $gz_active === $gz_tab['id'] ); ?>
							<button
								class="gz-tabs__btn<?php echo $gz_is_active ? ' is-active' : ''; ?>"
								type="button"
								role="tab"
								id="gz-tab-<?php echo esc_attr( $gz_tab['id'] ); ?>"
								aria-controls="gz-panel-<?php echo esc_attr( $gz_tab['id'] ); ?>"
								aria-selected="<?php echo $gz_is_active ? 'true' : 'false'; ?>"
								tabindex="<?php echo $gz_is_active ? '0' : '-1'; ?>"
								data-gz-tab="<?php echo esc_attr( $gz_tab['id'] ); ?>">
								<?php echo esc_html( $gz_tab['label'] ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="gz-tabs__panels" data-gz-panels>
					<?php foreach ( $gz_tabs as $gz_tab ) : ?>
						<?php $gz_is_active = ( $gz_active === $gz_tab['id'] ); ?>
						<div
							class="gz-tabs__panel<?php echo $gz_is_active ? ' is-active' : ''; ?>"
							role="tabpanel"
							id="gz-panel-<?php echo esc_attr( $gz_tab['id'] ); ?>"
							aria-labelledby="gz-tab-<?php echo esc_attr( $gz_tab['id'] ); ?>"
							data-gz-panel="<?php echo esc_attr( $gz_tab['id'] ); ?>"
							<?php echo $gz_is_active ? '' : 'hidden'; ?>>
							<img
								class="gz-tabs__img"
								src="<?php echo esc_url( $gz_tab['image'] ); ?>"
								alt="<?php echo esc_attr( $gz_tab['label'] ); ?>"
								loading="<?php echo $gz_is_active ? 'eager' : 'lazy'; ?>"
								decoding="async">
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

<?php elseif ( 'zoomie' === $erp_product['key'] ) : ?>
	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software hero — centred "Amber Laundry Software"-style banner (light background, big
	 * centred long-form two-line headline, lead paragraph), then a single
	 * large product-image stage underneath, same shape as the POS hero but
	 * with its own accent (see .gz-hero--zoomie in _gozzbe-hero.scss) and no
	 * trust-logo strip. Editable via amber_sub(); the image falls back to the
	 * bundled placeholder until a custom one is uploaded.
	 * -------------------------------------------------------------------- */
	$zm_heading = amber_sub( 'erp_zm_heading', __( "Take Orders, Run the Kitchen and Delight Diners with\nOne Platform", 'amber' ) );
	$zm_lead    = amber_sub( 'erp_zm_lead', __( 'Run your restaurant with complete restaurant billing software to get easy POS billing, advanced inventory, authentic report generating, online ordering, mobile app, KDS system, and so on. So, get in touch with Amber Restaurant Software and manage your restaurant efficiently.', 'amber' ) );

	// Same hard-break-at-known-word-boundary technique as the Amber Laundry Software hero
	// heading, so a two-line split is guaranteed even if the copy is edited
	// to a single-line string in the admin.
	$zm_heading_lines = explode( "\n", $zm_heading, 2 );
	if ( count( $zm_heading_lines ) < 2 ) {
		$zm_mid = (int) floor( mb_strlen( $zm_heading ) / 2 );
		$zm_break_at = mb_strpos( $zm_heading, ' ', $zm_mid );
		if ( false === $zm_break_at ) {
			$zm_heading_lines = array( $zm_heading, '' );
		} else {
			$zm_heading_lines = array(
				mb_substr( $zm_heading, 0, $zm_break_at ),
				mb_substr( $zm_heading, $zm_break_at + 1 ),
			);
		}
	}

	$zm_hero_img_raw = amber_sub( 'erp_zm_hero_image', '' );
	if ( is_array( $zm_hero_img_raw ) && ! empty( $zm_hero_img_raw['url'] ) ) {
		$zm_hero_img = $zm_hero_img_raw['url'];
	} elseif ( is_string( $zm_hero_img_raw ) && '' !== $zm_hero_img_raw ) {
		$zm_hero_img = $zm_hero_img_raw;
	} else {
		$zm_hero_img = content_url( '/uploads/2026/08/ChatGPT-Image-Aug-5-2026-12_21_11-PM.png' );
	}
	?>
	<section class="gz-hero gz-hero--zoomie">
		<div class="gz-hero__inner">
			<h1 class="gz-hero__title"><?php echo esc_html( $zm_heading_lines[0] ); ?><?php if ( ! empty( $zm_heading_lines[1] ) ) : ?><br><?php echo esc_html( $zm_heading_lines[1] ); ?><?php endif; ?></h1>

			<div class="gz-hero__wash">
				<p class="gz-hero__lead"><?php echo esc_html( $zm_lead ); ?></p>

				<div class="gz-hero__media">
					<?php amber_image( $zm_hero_img, 'full', array( 'class' => 'gz-hero__media-img', 'alt' => trim( str_replace( "\n", ' ', $zm_heading ) ) ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — stats strip. Reuses the homepage's .counters CSS component
	 * (four big numbers + labels) with Amber Restaurant Software-scoped erp_zm_stat{n}_* fields
	 * so it doesn't collide with the homepage's own counter{n}_* content.
	 * -------------------------------------------------------------------- */
	$zm_stats_heading   = amber_sub( 'erp_zm_stats_heading', __( 'Powering the Restaurant Businesses Operation in', 'amber' ) );
	$zm_stats_highlight = amber_sub( 'erp_zm_stats_heading_highlight', __( 'Over 1,200 Outlets', 'amber' ) );
	$zm_stats = array(
		array( 'num' => amber_sub( 'erp_zm_stat1_num', '1,200+' ), 'label' => amber_sub( 'erp_zm_stat1_label', __( 'Restaurants Relies On Amber Restaurant Software', 'amber' ) ) ),
		array( 'num' => amber_sub( 'erp_zm_stat2_num', '80%' ), 'label' => amber_sub( 'erp_zm_stat2_label', __( 'Faster Table Turnaround Over', 'amber' ) ) ),
		array( 'num' => amber_sub( 'erp_zm_stat3_num', '3 Years' ), 'label' => amber_sub( 'erp_zm_stat3_label', __( 'Amber Restaurant Software Come In the Market Over', 'amber' ) ) ),
	);
	?>
	<section class="section section--counters is-space-small is-bg-surface" data-counters>
		<div class="container">
			<?php if ( $zm_stats_heading || $zm_stats_highlight ) : ?>
				<h2 class="counters__heading" data-animate="fade-up">
					<?php echo esc_html( $zm_stats_heading ); ?>
					<?php if ( $zm_stats_highlight ) : ?>
						<span class="counters__highlight"><?php echo esc_html( $zm_stats_highlight ); ?></span>
					<?php endif; ?>
				</h2>
			<?php endif; ?>
			<div class="counters">
				<?php foreach ( $zm_stats as $i => $stat ) : ?>
					<div class="counter" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( $i * 90 ); ?>">
						<span class="counter__num"><?php echo esc_html( $stat['num'] ); ?></span>
						<span class="counter__label"><?php echo esc_html( $stat['label'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — "Smart Solutions for Every Restaurant Setup" (reference:
	 * sigai.ae/products/dine "At A Glance" panel). Image left (5/12), heading
	 * + two-column checklist right (7/12), each row a right-arrow bullet +
	 * one line of copy. Editable via amber_sub(); clearing a point's text in
	 * the admin removes that row. Image falls back to the same restaurant-POS
	 * illustration already uploaded for the hero's first draft.
	 * -------------------------------------------------------------------- */
	$zm_smart_heading = amber_sub( 'erp_zmsmart_heading', __( "Smart Solutions for Every\nRestaurant Setup", 'amber' ) );

	// Same hard-break-at-known-word-boundary technique used by the hero
	// headings above — a forced two-line split at a specific word ("Every" /
	// "Restaurant") rather than wherever the browser's own wrap happens to
	// land, so the heading reads "Smart Solutions for Every" / "Restaurant
	// Setup" regardless of container width; still degrades to a sensible
	// midpoint split if the heading is edited to a single-line string.
	$zm_smart_heading_lines = explode( "\n", $zm_smart_heading, 2 );
	if ( count( $zm_smart_heading_lines ) < 2 ) {
		$zm_smart_mid = (int) floor( mb_strlen( $zm_smart_heading ) / 2 );
		$zm_smart_break_at = mb_strpos( $zm_smart_heading, ' ', $zm_smart_mid );
		if ( false === $zm_smart_break_at ) {
			$zm_smart_heading_lines = array( $zm_smart_heading, '' );
		} else {
			$zm_smart_heading_lines = array(
				mb_substr( $zm_smart_heading, 0, $zm_smart_break_at ),
				mb_substr( $zm_smart_heading, $zm_smart_break_at + 1 ),
			);
		}
	}

	$zm_smart_img_raw = amber_sub( 'erp_zmsmart_image', '' );
	if ( is_array( $zm_smart_img_raw ) && ! empty( $zm_smart_img_raw['url'] ) ) {
		$zm_smart_img = $zm_smart_img_raw['url'];
	} elseif ( is_string( $zm_smart_img_raw ) && '' !== $zm_smart_img_raw ) {
		$zm_smart_img = $zm_smart_img_raw;
	} else {
		$zm_smart_img = content_url( '/uploads/2026/08/ChatGPT-Image-Aug-5-2026-10_30_02-AM.png' );
	}

	$zm_smart_defaults = array(
		__( 'Cloud-based restaurant management system integrated with Amber Restaurant Software ERP.', 'amber' ),
		__( 'Centralized POS for dine-in, takeaway, and delivery orders.', 'amber' ),
		__( 'Real-time inventory and recipe tracking to control food costs.', 'amber' ),
		__( 'Smart Kitchen Display System (KDS) for faster order fulfillment.', 'amber' ),
		__( 'Automated billing and digital payment processing.', 'amber' ),
		__( 'Comprehensive analytics for sales, expenses, and performance.', 'amber' ),
		__( 'Multi-branch and franchise management from a single dashboard.', 'amber' ),
		__( 'Secure, role-based access with cloud data backup and updates.', 'amber' ),
	);

	$zm_smart_points = array();
	foreach ( $zm_smart_defaults as $zm_sm_i => $zm_sm_default ) {
		$zm_sm_n = $zm_sm_i + 1;
		if ( amber_erp_slot_is_cleared( "erp_zmsmart{$zm_sm_n}_text" ) ) {
			continue;
		}
		$zm_sm_text = amber_sub( "erp_zmsmart{$zm_sm_n}_text", $zm_sm_default );
		if ( '' !== trim( (string) $zm_sm_text ) ) {
			$zm_smart_points[] = $zm_sm_text;
		}
	}
	?>
	<?php if ( $zm_smart_points ) : ?>
	<section class="zm-smart" id="zm-smart">
		<div class="container">
			<div class="zm-smart__row">
				<div class="zm-smart__media" data-animate="fade-right">
					<?php amber_image( $zm_smart_img, 'large', array( 'class' => 'zm-smart__img', 'alt' => trim( str_replace( "\n", ' ', $zm_smart_heading ) ) ) ); ?>
				</div>
				<div class="zm-smart__content" data-animate="fade-left">
					<h2 class="zm-smart__heading"><?php echo esc_html( $zm_smart_heading_lines[0] ); ?><?php if ( ! empty( $zm_smart_heading_lines[1] ) ) : ?><br><?php echo esc_html( $zm_smart_heading_lines[1] ); ?><?php endif; ?></h2>
					<ul class="zm-smart__list">
						<?php foreach ( $zm_smart_points as $zm_sm_point ) : ?>
							<li class="zm-smart__item">
								<span class="zm-smart__arrow" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<p><?php echo esc_html( $zm_sm_point ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — "Amber Restaurant Software Modules" (reference: sigai.ae/products/dine "DINE
	 * MODULES" panel — pixel-matched layout per explicit design brief, NOT
	 * the theme's usual card/icon-grid treatment). Three-column row: a fixed
	 * ~320px text-only module list on the left, a fixed-ratio photo in the
	 * middle, and a heading/description/"Key Highlights" bullet-list panel
	 * on the right. Clicking a module swaps the image + right panel; no
	 * autoplay (the reference is a static reveal, not a carousel). Each
	 * module's highlights are "Bold label: description" pairs, split on the
	 * first colon in the admin field. Editable via amber_sub(); clearing a
	 * module's heading removes it entirely.
	 * -------------------------------------------------------------------- */
	$zm_mod_placeholder = get_template_directory_uri() . '/assets/images/placeholder.svg';

	$zm_mod_defaults = array(
		array(
			'label'   => __( 'POS & Order Management', 'amber' ),
			'heading' => __( 'POS & Order Management', 'amber' ),
			'image'   => content_url( '/uploads/2026/08/ChatGPT-Image-Aug-5-2026-10_30_02-AM.png' ),
			'text'    => __( 'The POS and Order Management module serves as the central operational framework of the restaurant, ensuring accuracy, speed, and consistency across all order types. It streamlines billing, order processing, and payment workflows, enabling staff to deliver efficient service and maintain complete control over every transaction.', 'amber' ),
			'points'  => array(
				__( 'Centralized POS Interface: Manage dine-in, takeaway, and online orders within a unified system.', 'amber' ),
				__( 'Real-Time Order Tracking: Monitor table occupancy and order status through a live visual dashboard.', 'amber' ),
				__( 'Menu Configuration: Update pricing, availability, and combinations dynamically based on operational requirements.', 'amber' ),
			),
		),
		array(
			'label'   => __( 'Staff & Shift Management', 'amber' ),
			'heading' => __( 'Staff & Shift Management', 'amber' ),
			'image'   => content_url( '/uploads/2026/08/ChatGPT-Image-Aug-5-2026-12_21_11-PM.png' ),
			'text'    => __( 'The Staff and Shift Management module keeps every roster, role and attendance record in one place, so managers spend less time on spreadsheets and more time on the floor. Shift changes, overtime and permissions all stay visible and auditable.', 'amber' ),
			'points'  => array(
				__( 'Shift Scheduling: Build and publish weekly rosters with drag-and-drop assignment.', 'amber' ),
				__( 'Attendance Tracking: Clock-in and clock-out logs sync automatically to payroll.', 'amber' ),
				__( 'Role-Based Permissions: Control exactly what each staff member can see and do.', 'amber' ),
			),
		),
		array(
			'label'   => __( 'Accounting & Financial Management', 'amber' ),
			'heading' => __( 'Accounting & Financial Management', 'amber' ),
			'image'   => $zm_mod_placeholder,
			'text'    => __( 'Every transaction, refund and expense rolls up automatically into the Accounting and Financial Management module, giving owners a clear, always-current view of the business without manual reconciliation at month end.', 'amber' ),
			'points'  => array(
				__( 'Automated Reconciliation: Match POS transactions against bank deposits without spreadsheets.', 'amber' ),
				__( 'Expense Tracking: Log supplier bills and recurring costs against each outlet.', 'amber' ),
				__( 'Profit & Loss Reports: Generate outlet-level or brand-wide statements on demand.', 'amber' ),
			),
		),
		array(
			'label'   => __( 'Delivery & Third-Party Integration', 'amber' ),
			'heading' => __( 'Delivery & Third-Party Integration', 'amber' ),
			'image'   => $zm_mod_placeholder,
			'text'    => __( 'The Delivery and Third-Party Integration module pulls every delivery partner into one order queue, so kitchen staff never have to juggle multiple tablets or manually re-key an order that already arrived digitally.', 'amber' ),
			'points'  => array(
				__( 'Unified Order Queue: Aggregate orders from every delivery partner into one screen.', 'amber' ),
				__( 'Menu Sync: Push price and availability changes to all platforms simultaneously.', 'amber' ),
				__( 'Live Rider Tracking: Follow delivery status from dispatch to doorstep.', 'amber' ),
			),
		),
		array(
			'label'   => __( 'Table & Reservation Management', 'amber' ),
			'heading' => __( 'Table & Reservation Management', 'amber' ),
			'image'   => $zm_mod_placeholder,
			'text'    => __( 'The Table and Reservation Management module gives hosts a live floor plan and a booking calendar in one screen, so tables turn faster and walk-ins never get seated on top of a reservation.', 'amber' ),
			'points'  => array(
				__( 'Live Floor Plan: See every table\'s status — open, seated, or reserved — at a glance.', 'amber' ),
				__( 'Online Booking: Let guests reserve a table directly from your website or app.', 'amber' ),
				__( 'Waitlist Management: Text walk-ins automatically when their table is ready.', 'amber' ),
			),
		),
		array(
			'label'   => __( 'Online Ordering & QR Menu', 'amber' ),
			'heading' => __( 'Online Ordering & QR Menu', 'amber' ),
			'image'   => $zm_mod_placeholder,
			'text'    => __( 'The Online Ordering and QR Menu module turns every table into a self-service order point and every share link into a storefront, so guests order at their own pace and staff spend less time taking down orders by hand.', 'amber' ),
			'points'  => array(
				__( 'Scan-to-Order: Guests browse and order from a QR code at the table, no app required.', 'amber' ),
				__( 'Branded Storefront: Take direct online orders without paying marketplace commission.', 'amber' ),
				__( 'Live Menu Availability: Sold-out items disappear instantly across every channel.', 'amber' ),
			),
		),
	);

	$zm_modules = array();
	foreach ( $zm_mod_defaults as $zm_mod_i => $zm_mod_d ) {
		$zm_mod_n = $zm_mod_i + 1;
		if ( amber_erp_slot_is_cleared( "erp_zmmod{$zm_mod_n}_heading" ) ) {
			continue;
		}
		$zm_mod_heading = amber_sub( "erp_zmmod{$zm_mod_n}_heading", $zm_mod_d['heading'] );
		if ( '' === trim( (string) $zm_mod_heading ) ) {
			continue;
		}

		$zm_mod_img_raw = amber_sub( "erp_zmmod{$zm_mod_n}_image", '' );
		if ( is_array( $zm_mod_img_raw ) && ! empty( $zm_mod_img_raw['url'] ) ) {
			$zm_mod_image = $zm_mod_img_raw['url'];
		} elseif ( is_string( $zm_mod_img_raw ) && '' !== $zm_mod_img_raw ) {
			$zm_mod_image = $zm_mod_img_raw;
		} else {
			$zm_mod_image = $zm_mod_d['image'];
		}

		$zm_mod_points = array();
		foreach ( $zm_mod_d['points'] as $zm_pt_i => $zm_pt_default ) {
			$zm_pt_n = $zm_pt_i + 1;
			$zm_pt_raw = amber_sub( "erp_zmmod{$zm_mod_n}_point{$zm_pt_n}", $zm_pt_default );
			if ( '' === trim( (string) $zm_pt_raw ) ) {
				continue;
			}
			// "Bold label: rest of the sentence" — split on the first colon so
			// the label can render bold and the remainder plain, matching the
			// reference's "Centralized POS Interface: Manage dine-in…" pattern.
			$zm_pt_parts = explode( ':', $zm_pt_raw, 2 );
			$zm_mod_points[] = array(
				'label' => trim( $zm_pt_parts[0] ),
				'text'  => isset( $zm_pt_parts[1] ) ? trim( $zm_pt_parts[1] ) : '',
			);
		}

		$zm_modules[] = array(
			'label'   => amber_sub( "erp_zmmod{$zm_mod_n}_label", $zm_mod_d['label'] ),
			'heading' => $zm_mod_heading,
			'image'   => $zm_mod_image,
			'text'    => amber_sub( "erp_zmmod{$zm_mod_n}_text", $zm_mod_d['text'] ),
			'points'  => $zm_mod_points,
		);
	}

	$zm_mod_kh_label = amber_sub( 'erp_zmmod_kh_label', __( 'Key Highlights:', 'amber' ) );
	$zm_mod_eyebrow  = amber_sub( 'erp_zmmod_eyebrow', __( 'Key Features', 'amber' ) );
	$zm_mod_active   = 0;
	?>
	<?php if ( $zm_modules ) : ?>
	<section <?php echo amber_section_attrs( 'zm-features', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> id="zm-features">
		<div class="container zm-modules__container">
			<h2 class="zm-modules__eyebrow" data-animate="fade-up"><?php echo esc_html( $zm_mod_eyebrow ); ?></h2>

			<div class="zm-modules" data-zm-modules data-animate="fade-up">
				<ul class="zm-modules__nav" role="tablist" aria-label="<?php echo esc_attr( $zm_mod_eyebrow ); ?>">
					<?php foreach ( $zm_modules as $zm_mod_i => $zm_mod ) : ?>
						<?php $zm_mod_is_active = ( $zm_mod_active === $zm_mod_i ); ?>
						<li
							id="zm-mod-tab-<?php echo (int) $zm_mod_i; ?>"
							class="zm-modules__nav-item<?php echo $zm_mod_is_active ? ' is-active' : ''; ?>"
							role="tab"
							tabindex="<?php echo $zm_mod_is_active ? '0' : '-1'; ?>"
							aria-selected="<?php echo $zm_mod_is_active ? 'true' : 'false'; ?>"
							aria-controls="zm-mod-panel-<?php echo (int) $zm_mod_i; ?>"
							data-zm-modules-item
							data-zm-modules-index="<?php echo (int) $zm_mod_i; ?>">
							<?php echo esc_html( $zm_mod['label'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php foreach ( $zm_modules as $zm_mod_i => $zm_mod ) : ?>
					<?php $zm_mod_is_active = ( $zm_mod_active === $zm_mod_i ); ?>
					<div
						class="zm-modules__media<?php echo $zm_mod_is_active ? ' is-active' : ''; ?>"
						id="zm-mod-media-<?php echo (int) $zm_mod_i; ?>"
						<?php echo $zm_mod_is_active ? '' : 'hidden'; ?>>
						<?php amber_image( $zm_mod['image'], 'large', array( 'class' => 'zm-modules__img', 'alt' => $zm_mod['heading'] ) ); ?>
					</div>
				<?php endforeach; ?>

				<?php foreach ( $zm_modules as $zm_mod_i => $zm_mod ) : ?>
					<?php $zm_mod_is_active = ( $zm_mod_active === $zm_mod_i ); ?>
					<div
						class="zm-modules__panel<?php echo $zm_mod_is_active ? ' is-active' : ''; ?>"
						role="tabpanel"
						id="zm-mod-panel-<?php echo (int) $zm_mod_i; ?>"
						aria-labelledby="zm-mod-tab-<?php echo (int) $zm_mod_i; ?>"
						data-zm-modules-panel="<?php echo (int) $zm_mod_i; ?>"
						<?php echo $zm_mod_is_active ? '' : 'hidden'; ?>>
						<h3 class="zm-modules__heading"><?php echo esc_html( $zm_mod['heading'] ); ?></h3>
						<?php if ( $zm_mod['text'] ) : ?>
							<p class="zm-modules__text"><?php echo esc_html( $zm_mod['text'] ); ?></p>
						<?php endif; ?>
						<?php if ( $zm_mod['points'] ) : ?>
							<p class="zm-modules__kh"><?php echo esc_html( $zm_mod_kh_label ); ?></p>
							<ul class="zm-modules__points">
								<?php foreach ( $zm_mod['points'] as $zm_pt ) : ?>
									<li>
										<?php if ( $zm_pt['label'] ) : ?><strong><?php echo esc_html( $zm_pt['label'] ); ?>:</strong><?php endif; ?>
										<?php echo esc_html( $zm_pt['text'] ? ' ' . $zm_pt['text'] : '' ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>


	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — three-step benefits (reference: RestoraPOS
	 * "Your Business is on Safe Hand with Restora POS" — a green eyebrow +
	 * accent-highlighted heading, a horizontal pill tab bar, and a large
	 * rounded stage panel per step with a screenshot on one side and
	 * heading/text/checklist on the other, alternating sides per step).
	 * Reuses the same interactive .pos-key__list/.pos-key__panel toggle
	 * markup/JS as the POS Key Features section (modules/pos-key-features.js
	 * finds elements by those exact class names), fully restyled under the
	 * #zm-steps scope so the POS page's own Key Features section is
	 * untouched. Each step now also carries its own screenshot, editable via
	 * amber_sub(); falls back to the bundled placeholder.
	 * -------------------------------------------------------------------- */
	$zm_step_eyebrow  = amber_sub( 'erp_zmstep_eyebrow', __( "Don't Worry!!!", 'amber' ) );
	$zm_step_heading  = amber_sub( 'erp_zmstep_heading', __( 'Your Restaurant Is in Safe Hands With', 'amber' ) );
	$zm_step_accent   = amber_sub( 'erp_zmstep_heading_accent', __( 'Amber Restaurant Software', 'amber' ) );
	$zm_step_sub      = amber_sub( 'erp_zmstep_sub', __( 'Boost your restaurant with three main steps', 'amber' ) );
	// Same "Amber Resto" tablet-in-hand photo already used as the Key
	// Features section's first module image — reused here as the default
	// visual for every step until real per-step screenshots are uploaded, so
	// the section renders with real branded artwork instead of the generic
	// placeholder.
	$zm_step_placeholder = content_url( '/uploads/2026/08/ChatGPT-Image-Aug-5-2026-10_30_02-AM.png' );

	$zm_step_defaults = array(
		array(
			'title'   => __( 'Digital Management', 'amber' ),
			'text'    => __( 'Those days are over when people used to manage their restaurant manually. Now it\'s the era of digital technology, everyone has their own smart technology on their hands. Thus expects the same advanced and technical service from everyone.', 'amber' ),
			'text2'   => __( 'And to catch this digital pace, your restaurant must adopt digital management technology. Amber Restaurant Software offers exactly what you want:', 'amber' ),
			'bullets' => array(
				__( 'User-friendly interface', 'amber' ),
				__( 'Straightforward management system', 'amber' ),
				__( 'Smooth and fast billing system', 'amber' ),
			),
		),
		array(
			'title'   => __( 'Take Control On Your Own Hand', 'amber' ),
			'text'    => __( 'You can eliminate all the stress and tension of "not having a strong control and management system" in your restaurant. Now you can not only monitor but also control and record all the data.', 'amber' ),
			'text2'   => __( 'With Amber Restaurant Software, you can get all-in one solution for all your management related operations in your restaurant, no matter what size and type your restaurant is. Easy managerial system for your restaurant.', 'amber' ),
			'bullets' => array(
				__( 'Eliminate the possibilities of theft', 'amber' ),
				__( 'Better control on the operational cost', 'amber' ),
				__( 'Heads-up about monthly/yearly sales; with graphical presentation', 'amber' ),
			),
		),
		array(
			'title'   => __( 'Integration', 'amber' ),
			'text'    => __( 'Amber Restaurant Software, quite strongly integrated with Website, App and Third party delivery companies. Moreover with this powerful integration your restaurant can enrich the online visibility, establish the brand, and of course higher customer reach.', 'amber' ),
			'text2'   => __( 'This integration system opens up the opportunity to manage, record and control all the synchronization of the payment data, order data, customer data, accounts data and optimize a chronological workflow at no-time.', 'amber' ),
			'bullets' => array(
				__( 'Powerful integration with website, app and third party', 'amber' ),
				__( 'Raise-up the customer engagement', 'amber' ),
				__( 'Make your website globally ranked', 'amber' ),
			),
		),
	);

	$zm_steps = array();
	foreach ( $zm_step_defaults as $zm_st_i => $zm_st_d ) {
		$zm_st_n = $zm_st_i + 1;
		if ( amber_erp_slot_is_cleared( "erp_zmstep{$zm_st_n}_title" ) ) {
			continue;
		}
		$zm_st_title = amber_sub( "erp_zmstep{$zm_st_n}_title", $zm_st_d['title'] );
		if ( '' === trim( (string) $zm_st_title ) ) {
			continue;
		}

		$zm_st_bullets = array();
		foreach ( $zm_st_d['bullets'] as $zm_b_i => $zm_b_default ) {
			$zm_b_n = $zm_b_i + 1;
			$zm_b_text = amber_sub( "erp_zmstep{$zm_st_n}_bullet{$zm_b_n}", $zm_b_default );
			if ( '' !== trim( (string) $zm_b_text ) ) {
				$zm_st_bullets[] = $zm_b_text;
			}
		}

		$zm_st_img_raw = amber_sub( "erp_zmstep{$zm_st_n}_image", '' );
		if ( is_array( $zm_st_img_raw ) && ! empty( $zm_st_img_raw['url'] ) ) {
			$zm_st_image = $zm_st_img_raw['url'];
		} elseif ( is_string( $zm_st_img_raw ) && '' !== $zm_st_img_raw ) {
			$zm_st_image = $zm_st_img_raw;
		} else {
			$zm_st_image = $zm_step_placeholder;
		}

		$zm_steps[] = array(
			'title'   => $zm_st_title,
			'text'    => amber_sub( "erp_zmstep{$zm_st_n}_text", $zm_st_d['text'] ),
			'text2'   => amber_sub( "erp_zmstep{$zm_st_n}_text2", $zm_st_d['text2'] ),
			'bullets' => $zm_st_bullets,
			'image'   => $zm_st_image,
		);
	}

	$zm_step_active = 0;
	?>
	<?php if ( $zm_steps ) : ?>
	<section class="pos-key zm-steps" id="zm-steps">
		<div class="pos-key__container">
			<div class="zm-steps__head" data-animate="fade-up">
				<?php if ( $zm_step_eyebrow ) : ?>
					<p class="zm-steps__eyebrow"><?php echo esc_html( $zm_step_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="zm-steps__heading">
					<?php echo esc_html( $zm_step_heading ); ?>
					<?php if ( $zm_step_accent ) : ?>
						<br><span class="zm-steps__accent"><?php echo esc_html( $zm_step_accent ); ?></span>
					<?php endif; ?>
				</h2>
				<?php if ( $zm_step_sub ) : ?>
					<p class="zm-steps__sub"><?php echo esc_html( $zm_step_sub ); ?></p>
				<?php endif; ?>
			</div>

			<div class="pos-key__row zm-steps__row" data-pos-key>
				<ul class="pos-key__list zm-steps__nav" role="tablist" aria-label="<?php echo esc_attr( $zm_step_heading ); ?>">
					<?php foreach ( $zm_steps as $zm_st_i => $zm_st ) : ?>
						<?php $zm_st_is_active = ( $zm_step_active === $zm_st_i ); ?>
						<li
							class="pos-key__item<?php echo $zm_st_is_active ? ' is-active' : ''; ?>"
							role="tab"
							tabindex="<?php echo $zm_st_is_active ? '0' : '-1'; ?>"
							aria-selected="<?php echo $zm_st_is_active ? 'true' : 'false'; ?>"
							aria-controls="zm-step-panel-<?php echo (int) $zm_st_i; ?>"
							data-pos-key-item
							data-pos-key-index="<?php echo (int) $zm_st_i; ?>">
							<?php echo esc_html( $zm_st['title'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="pos-key__panels zm-steps__stage">
					<?php foreach ( $zm_steps as $zm_st_i => $zm_st ) : ?>
						<?php $zm_st_is_active = ( $zm_step_active === $zm_st_i ); ?>
						<div
							id="zm-step-panel-<?php echo (int) $zm_st_i; ?>"
							class="pos-key__panel zm-steps__panel<?php echo $zm_st_is_active ? ' is-active' : ''; ?><?php echo ( 1 === $zm_st_i % 2 ) ? ' zm-steps__panel--flip' : ''; ?>"
							role="tabpanel"
							<?php echo $zm_st_is_active ? '' : 'hidden'; ?>>
							<div class="zm-steps__media">
								<?php amber_image( $zm_st['image'], 'large', array( 'class' => 'zm-steps__img', 'alt' => $zm_st['title'] ) ); ?>
							</div>
							<div class="pos-key__panel-head zm-steps__body">
								<h3 class="pos-key__panel-title"><?php echo esc_html( $zm_st['title'] ); ?></h3>
								<p class="pos-key__panel-text"><?php echo esc_html( $zm_st['text'] ); ?></p>
								<?php if ( $zm_st['text2'] ) : ?>
									<p class="pos-key__panel-text"><?php echo esc_html( $zm_st['text2'] ); ?></p>
								<?php endif; ?>
								<?php if ( $zm_st['bullets'] ) : ?>
									<ul class="zm-step__bullets">
										<?php foreach ( $zm_st['bullets'] as $zm_bullet ) : ?>
											<li>
												<span class="zm-step__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
												<?php echo esc_html( $zm_bullet ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — restaurant types showcase. A photo-card grid
	 * (rounded image + plain centred caption underneath, five up on wide
	 * screens) matching the reference's restaurant-type strip, instead of the
	 * boxed icon cards this section used before. Each tile is optionally a
	 * link to that vertical's own page.
	 *
	 * Heading is split into three parts so the middle word renders in brand
	 * colour, the way the reference highlights "Restaurant".
	 * -------------------------------------------------------------------- */
	$zm_types_heading = amber_sub( 'erp_zmtype_heading', __( 'Built for Every Kind of', 'amber' ) );
	$zm_types_accent  = amber_sub( 'erp_zmtype_heading_accent', __( 'Restaurant', 'amber' ) );
	$zm_types_tail    = amber_sub( 'erp_zmtype_heading_after', '' );

	// Bundled placeholder until real per-type photography is uploaded — the
	// icon fallback below is what actually renders in that state, so the tiles
	// never show an empty grey box.
	$zm_type_defaults = array(
		array( 'icon' => 'star',      'label' => __( 'Cafe & Coffee Shop', 'amber' ) ),
		array( 'icon' => 'layers',    'label' => __( 'Franchise Management', 'amber' ) ),
		array( 'icon' => 'cart',      'label' => __( 'Burger & Sandwich Shop', 'amber' ) ),
		array( 'icon' => 'utensils',  'label' => __( 'Pizza Restaurant', 'amber' ) ),
		array( 'icon' => 'cloud',     'label' => __( 'Cloud Kitchen', 'amber' ) ),
		array( 'icon' => 'globe',     'label' => __( 'Seafood Restaurant', 'amber' ) ),
		array( 'icon' => 'spark',     'label' => __( 'Fine Dine Restaurant', 'amber' ) ),
		array( 'icon' => 'truck',     'label' => __( 'Food Truck', 'amber' ) ),
		array( 'icon' => 'bolt',      'label' => __( 'Quick Service Restaurant', 'amber' ) ),
		array( 'icon' => 'briefcase', 'label' => __( 'Catering Service', 'amber' ) ),
	);

	$zm_types = array();
	foreach ( $zm_type_defaults as $zm_ty_i => $zm_ty_d ) {
		$zm_ty_n = $zm_ty_i + 1;
		if ( amber_erp_slot_is_cleared( "erp_zmtype{$zm_ty_n}_label" ) ) {
			continue;
		}
		$zm_ty_label = amber_sub( "erp_zmtype{$zm_ty_n}_label", $zm_ty_d['label'] );
		if ( '' === trim( (string) $zm_ty_label ) ) {
			continue;
		}

		$zm_ty_img_raw = amber_sub( "erp_zmtype{$zm_ty_n}_image", '' );
		if ( is_array( $zm_ty_img_raw ) && ! empty( $zm_ty_img_raw['url'] ) ) {
			$zm_ty_image = $zm_ty_img_raw['url'];
		} elseif ( is_string( $zm_ty_img_raw ) && '' !== $zm_ty_img_raw ) {
			$zm_ty_image = $zm_ty_img_raw;
		} else {
			$zm_ty_image = '';
		}

		$zm_ty_link = amber_sub( "erp_zmtype{$zm_ty_n}_link", '' );
		$zm_ty_url  = '';
		$zm_ty_tgt  = '';
		if ( is_array( $zm_ty_link ) && ! empty( $zm_ty_link['url'] ) ) {
			$zm_ty_url = $zm_ty_link['url'];
			$zm_ty_tgt = ! empty( $zm_ty_link['target'] ) ? $zm_ty_link['target'] : '';
		} elseif ( is_string( $zm_ty_link ) && '' !== $zm_ty_link ) {
			$zm_ty_url = $zm_ty_link;
		}

		$zm_types[ $zm_ty_i ] = array(
			'icon'   => amber_sub( "erp_zmtype{$zm_ty_n}_icon", $zm_ty_d['icon'] ),
			'label'  => $zm_ty_label,
			'image'  => $zm_ty_image,
			'url'    => $zm_ty_url,
			'target' => $zm_ty_tgt,
		);
	}
	?>
	<?php if ( $zm_types ) : ?>
	<section <?php echo amber_section_attrs( 'zm-types', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> id="zm-types">
		<div class="container">
			<div class="section-header is-align-center" data-animate="fade-up">
				<h2 class="section-header__title zm-types__title">
					<?php echo esc_html( $zm_types_heading ); ?>
					<?php if ( $zm_types_accent ) : ?>
						<span class="zm-types__accent"><?php echo esc_html( $zm_types_accent ); ?></span>
					<?php endif; ?>
					<?php if ( $zm_types_tail ) : ?>
						<?php echo esc_html( $zm_types_tail ); ?>
					<?php endif; ?>
				</h2>
			</div>

			<div class="zm-types__grid">
				<?php
				foreach ( $zm_types as $zm_ty ) :
					// Linked tiles are anchors; unlinked ones stay non-interactive
					// divs rather than empty-href anchors.
					$zm_ty_tag  = $zm_ty['url'] ? 'a' : 'div';
					$zm_ty_attr = '';
					if ( $zm_ty['url'] ) {
						$zm_ty_attr = ' href="' . esc_url( $zm_ty['url'] ) . '"';
						if ( '_blank' === $zm_ty['target'] ) {
							$zm_ty_attr .= ' target="_blank" rel="noopener noreferrer"';
						}
					}
					?>
					<<?php echo esc_html( $zm_ty_tag ) . $zm_ty_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="zm-types__card<?php echo $zm_ty['url'] ? ' zm-types__card--link' : ''; ?>" data-animate="fade-up">
						<?php if ( $zm_ty['image'] ) : ?>
							<span class="zm-types__media">
								<img class="zm-types__img" src="<?php echo esc_url( $zm_ty['image'] ); ?>" alt="<?php echo esc_attr( $zm_ty['label'] ); ?>" loading="lazy" decoding="async" />
							</span>
						<?php else : ?>
							<span class="zm-types__media zm-types__media--icon" aria-hidden="true">
								<?php echo amber_icon_svg( $zm_ty['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
						<?php endif; ?>
						<span class="zm-types__label"><?php echo esc_html( $zm_ty['label'] ); ?></span>
					</<?php echo esc_html( $zm_ty_tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	/* -----------------------------------------------------------------------
	 * Amber Restaurant Software — testimonials. Reuses the homepage reviews-slider CSS
	 * (.reviews-sec/.review-card) with Amber Restaurant Software-scoped erp_zmti_* fields and
	 * original placeholder names/quotes (not the reference site's real
	 * clients) so it renders complete without colliding with the homepage's
	 * own testi{n}_* content.
	 * -------------------------------------------------------------------- */
	$zm_ti_eyebrow = amber_sub( 'erp_zmti_eyebrow', __( 'Reviews', 'amber' ) );
	$zm_ti_heading = amber_sub( 'erp_zmti_heading', __( 'Restaurants running better with Amber Restaurant Software', 'amber' ) );

	$zm_ti_defaults = array(
		array(
			'quote' => __( 'Amber Restaurant Software took the chaos out of our dinner rush. Orders hit the kitchen the second they\'re placed, and tickets stop getting lost.', 'amber' ),
			'name'  => __( 'Ravi Menon', 'amber' ),
			'role'  => __( 'Owner, coastal seafood restaurant', 'amber' ),
		),
		array(
			'quote' => __( 'Inventory used to be our biggest headache. Amber Restaurant Software\'s stock alerts mean we stop running out of our best-selling dishes mid-service.', 'amber' ),
			'name'  => __( 'Priya Shah', 'amber' ),
			'role'  => __( 'Owner, multi-outlet cafe chain', 'amber' ),
		),
		array(
			'quote' => __( 'The QR ordering and KDS combination alone cut our average table turnaround by nearly a third.', 'amber' ),
			'name'  => __( 'Daniel Cruz', 'amber' ),
			'role'  => __( 'Operations manager, quick-service chain', 'amber' ),
		),
		array(
			'quote' => __( 'Support actually picks up. Every time we\'ve needed help setting up a new outlet, Amber Restaurant Software\'s team has been there within minutes.', 'amber' ),
			'name'  => __( 'Fatima Noor', 'amber' ),
			'role'  => __( 'Owner, cloud kitchen network', 'amber' ),
		),
	);

	$zm_testimonials = array();
	foreach ( $zm_ti_defaults as $zm_ti_i => $zm_ti_d ) {
		$zm_ti_n = $zm_ti_i + 1;
		if ( amber_erp_slot_is_cleared( "erp_zmti{$zm_ti_n}_name" ) ) {
			continue;
		}
		$zm_ti_name = amber_sub( "erp_zmti{$zm_ti_n}_name", $zm_ti_d['name'] );
		if ( '' === trim( (string) $zm_ti_name ) ) {
			continue;
		}
		$zm_testimonials[] = array(
			'quote'  => amber_sub( "erp_zmti{$zm_ti_n}_quote", $zm_ti_d['quote'] ),
			'name'   => $zm_ti_name,
			'role'   => amber_sub( "erp_zmti{$zm_ti_n}_role", $zm_ti_d['role'] ),
			'avatar' => amber_sub( "erp_zmti{$zm_ti_n}_avatar", '' ),
		);
	}
	?>
	<?php if ( $zm_testimonials ) : ?>
	<section class="section section--testi reviews-sec" data-reviews>
		<div class="container">
			<div class="reviews-sec__header">
				<span class="reviews-sec__badge"><?php echo esc_html( $zm_ti_eyebrow ); ?></span>
				<h2 class="reviews-sec__title"><?php echo esc_html( $zm_ti_heading ); ?></h2>
			</div>
		</div>

		<div class="reviews-sec__viewport" data-reviews-viewport>
			<div class="reviews-sec__track" data-reviews-track>
				<?php foreach ( $zm_testimonials as $zm_ti ) : ?>
					<article class="review-card">
						<span class="review-card__glow" aria-hidden="true"></span>

						<figure class="review-card__avatar">
							<?php
							if ( ! empty( $zm_ti['avatar'] ) ) {
								amber_image( $zm_ti['avatar'], 'amber-square', array( 'alt' => $zm_ti['name'] ) );
							} else {
								echo '<span class="review-card__initial" aria-hidden="true">' . esc_html( mb_substr( $zm_ti['name'], 0, 1 ) ) . '</span>';
							}
							?>
						</figure>

						<blockquote class="review-card__quote"><?php echo esc_html( $zm_ti['quote'] ); ?></blockquote>

						<div class="review-card__meta">
							<p class="review-card__name"><?php echo esc_html( $zm_ti['name'] ); ?></p>
							<?php if ( ! empty( $zm_ti['role'] ) ) : ?>
								<p class="review-card__role"><?php echo esc_html( $zm_ti['role'] ); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

<?php elseif ( 'pos' === $erp_product['key'] ) : ?>
	<?php
	/* -----------------------------------------------------------------------
	 * POS hero — centred banner (same "CleanCloud"-style centred layout as
	 * the Amber Laundry Software hero: light background, big centred headline, lead
	 * paragraph), but without Amber Laundry Software's tabs/intro-video machinery — just a
	 * large open frame reserved for a product screenshot underneath the copy.
	 * Editable via the "Amber — ERP Page" group; the image falls back to the
	 * bundled placeholder graphic when empty.
	 * -------------------------------------------------------------------- */
	$pos_hero_img_raw = amber_sub( 'erp_hero_image', '' );
	if ( is_array( $pos_hero_img_raw ) && ! empty( $pos_hero_img_raw['url'] ) ) {
		$pos_hero_img = $pos_hero_img_raw['url'];
	} elseif ( is_string( $pos_hero_img_raw ) && '' !== $pos_hero_img_raw ) {
		$pos_hero_img = $pos_hero_img_raw;
	} else {
		$pos_hero_img = get_template_directory_uri() . '/assets/images/placeholder.svg';
	}
	?>
	<section class="gz-hero gz-hero--pos">
		<div class="gz-hero__inner">
			<h1 class="gz-hero__title"><span class="text-primary"><?php echo esc_html( $erp_heading_accent ); ?></span><br><?php echo esc_html( $erp_heading_rest ); ?></h1>
			
			<p class="gz-hero__lead"><?php echo esc_html( $erp_lead ); ?></p>

			<div class="gz-hero__media">
				<?php amber_image( $pos_hero_img, 'full', array( 'class' => 'gz-hero__media-img', 'alt' => trim( $erp_heading_accent . ' ' . $erp_heading_rest ) ) ); ?>
			</div>

			<?php
			/* -------------------------------------------------------------------
			 * Trusted-by strip — left-aligned label + a continuously scrolling
			 * logo marquee, sitting under the hero media. Reuses the same
			 * [data-logos-track] marquee mechanism/JS as the homepage trusted-by
			 * slider (modules/logo-slider.js). Shows the first eight of the
			 * shared site-wide client logos (Site Settings → Client Logos), so
			 * this strip can't drift from the grid further down the page.
			 * ---------------------------------------------------------------- */
			$pos_trust_label = amber_sub( 'erp_hero_trust_label', __( 'Brands trusted by', 'amber' ) );
			$pos_trust_logos = amber_client_logos( 8 );
			?>
			<div class="gz-hero__trust">
				<span class="gz-hero__trust-label"><?php echo esc_html( $pos_trust_label ); ?></span>
				<div class="gz-hero__trust-viewport">
					<ul class="gz-hero__trust-track" data-logos-track>
						<?php foreach ( $pos_trust_logos as $pos_trust_logo ) : ?>
							<li class="gz-hero__trust-item">
								<?php amber_image( $pos_trust_logo['logo'], 'amber-logo', array( 'class' => 'gz-hero__trust-img', 'alt' => $pos_trust_logo['name'] ) ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<?php
	/* -----------------------------------------------------------------------
	 * Why Amber POS Software — split "why choose us" band (reference: GoFrugal's
	 * "Why choose Gofrugal POS software" section). Heading/copy/CTA row on the
	 * left, a product screenshot on the right. Editable via the
	 * "Amber — ERP Page" group; falls back to the bundled dashboard shot.
	 * Sits right after the hero, before the Benefits/Key-Features sections.
	 * POS-only section.
	 * -------------------------------------------------------------------- */
	$pos_why_heading = amber_sub( 'erp_pwhy_heading', __( 'Why Choose Amber POS Software for Retail and Restaurant?', 'amber' ) );

	// No forced line breaks — the heading reads as one continuous phrase and
	// wraps naturally to whatever the column width allows (a fixed \n split
	// here was pinning it to 3 short lines even when the column had room for
	// much longer ones, e.g. after the media column was narrowed).
	$pos_why_heading_lines = explode( "\n", $pos_why_heading );
	$pos_why_text     = amber_sub( 'erp_pwhy_text', __( 'Amber POS Software offers the best solutions for retail and restaurant businesses, compatible and working on any device, available both on-premise and cloud. Get 100% freedom to choose your device of usage, with the flexibility to connect third-party solutions using our integrations and open APIs.', 'amber' ) );

	$pos_why_btn1_label = amber_sub( 'erp_pwhy_btn1_label', __( 'Get Started', 'amber' ) );
	$pos_why_btn1_url   = amber_sub( 'erp_pwhy_btn1_url', home_url( '/contact-us/' ) );
	$pos_why_btn2_label = amber_sub( 'erp_pwhy_btn2_label', __( 'View Features', 'amber' ) );
	$pos_why_btn2_url   = amber_sub( 'erp_pwhy_btn2_url', '#pos-benefits' );
	$pos_why_btn3_label = amber_sub( 'erp_pwhy_btn3_label', __( 'Contact Sales', 'amber' ) );
	$pos_why_btn3_url   = amber_sub( 'erp_pwhy_btn3_url', home_url( '/contact-us/' ) );

	$pos_why_image = amber_sub( 'erp_pwhy_image', AMBER_URI . 'assets/images/erp/dashboard.webp' );
	?>
	<section <?php echo amber_section_attrs( 'pos-why', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> id="pos-why">
		<div class="container">
			<div class="pos-why__row">
				<div class="pos-why__content" data-animate="fade-up">
					<h2 class="pos-why__title"><?php echo implode( '<br>', array_map( 'esc_html', $pos_why_heading_lines ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
					<p class="pos-why__text"><?php echo esc_html( $pos_why_text ); ?></p>
					<div class="pos-why__cta">
						<a href="<?php echo esc_url( $pos_why_btn1_url ); ?>" class="btn btn--pos-accent"><?php echo esc_html( $pos_why_btn1_label ); ?></a>
						<a href="<?php echo esc_url( $pos_why_btn2_url ); ?>" class="btn btn--pos-accent"><?php echo esc_html( $pos_why_btn2_label ); ?></a>
						<a href="<?php echo esc_url( $pos_why_btn3_url ); ?>" class="btn btn--pos-accent"><?php echo esc_html( $pos_why_btn3_label ); ?></a>
					</div>
				</div>
				<?php if ( $pos_why_image ) : ?>
					<div class="pos-why__media" data-animate="fade-up">
						<?php amber_image( $pos_why_image, 'large', array( 'class' => 'pos-why__media-img', 'alt' => implode( ' ', $pos_why_heading_lines ) ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	/* -----------------------------------------------------------------------
	 * Benefits — six-card icon grid (reference: GoFrugal's "Benefits of POS
	 * software" section). Editable via the "Amber — ERP Page" group; defaults
	 * come from amber_erp_pos_benefit_defaults(). POS-only section.
	 * -------------------------------------------------------------------- */
	$pos_bnft_heading = amber_sub( 'erp_pben_heading', __( 'Benefits of Amber POS Software in Retail and Restaurant Industry', 'amber' ) );

	$pos_benefits = array();
	foreach ( amber_erp_pos_benefit_defaults() as $pos_bnft_i => $pos_bnft_d ) {
		if ( amber_erp_slot_is_cleared( "erp_pben{$pos_bnft_i}_title" ) ) {
			continue;
		}
		$pos_bnft_title = amber_sub( "erp_pben{$pos_bnft_i}_title", $pos_bnft_d['title'] );
		if ( '' === trim( (string) $pos_bnft_title ) ) {
			continue;
		}
		$pos_benefits[ $pos_bnft_i ] = array(
			'icon'  => amber_sub( "erp_pben{$pos_bnft_i}_icon", $pos_bnft_d['icon'] ),
			'title' => $pos_bnft_title,
			'text'  => amber_sub( "erp_pben{$pos_bnft_i}_text", $pos_bnft_d['text'] ),
		);
	}

	$pos_bnft_btn_label = amber_sub( 'erp_pben_btn_label', __( 'Access Free Amber POS Software', 'amber' ) );
	$pos_bnft_btn_url   = amber_sub( 'erp_pben_btn_url', home_url( '/contact-us/' ) );
	?>
	<?php if ( $pos_benefits ) : ?>
	<section <?php echo amber_section_attrs( 'pos-benefits', 'surface' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> id="pos-benefits">
		<div class="container">
			<div class="section-header is-align-center" data-animate="fade-up">
				<h2 class="section-header__title"><?php echo esc_html( $pos_bnft_heading ); ?></h2>
			</div>

			<div class="pos-bnft__grid">
				<?php foreach ( $pos_benefits as $pos_bnft ) : ?>
					<div class="pos-bnft__card" data-animate="fade-up">
						<span class="pos-bnft__icon" aria-hidden="true"><?php echo amber_icon_svg( $pos_bnft['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3 class="pos-bnft__title"><?php echo esc_html( $pos_bnft['title'] ); ?></h3>
						<p class="pos-bnft__text"><?php echo esc_html( $pos_bnft['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="pos-bnft__cta">
				<a href="<?php echo esc_url( $pos_bnft_btn_url ); ?>" class="btn btn-primary banner-primary-btn"><?php echo esc_html( $pos_bnft_btn_label ); ?></a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	/* -----------------------------------------------------------------------
	 * Key Features — interactive list + detail panel (reference: GoFrugal's
	 * "Key features of a POS system" section — plain grey rows that turn solid
	 * brand-blue when active, no icon/description in the list itself; the
	 * active row's title/text/"Learn more" link and image appear together in
	 * a panel on the right). Own POS-scoped markup/CSS (.pos-key-*) — distinct
	 * from the Amber Laundry Software .gzf-* feature sections, which keep their own icon-row +
	 * crossfade-only design. Still driven by the same interactive engine
	 * (modules/gozzbe-features.js queries any [data-gzf] grid, so this reuses
	 * it as-is — only the visual design differs, via CSS/markup, not JS).
	 * Editable via the "Amber — ERP Page" group; defaults come from
	 * amber_erp_pos_key_feature_defaults(). POS-only section.
	 * -------------------------------------------------------------------- */
	$pos_key_placeholder = get_template_directory_uri() . '/assets/images/placeholder.svg';
	$pos_key_heading     = amber_sub( 'erp_pkey_heading', __( 'Key Features of a POS System', 'amber' ) );
	$pos_key_learn_label = amber_sub( 'erp_pkey_learn_label', __( 'Learn more', 'amber' ) );

	$pos_key_items = array();
	foreach ( amber_erp_pos_key_feature_defaults() as $pos_key_i => $pos_key_d ) {
		if ( amber_erp_slot_is_cleared( "erp_pkey{$pos_key_i}_title" ) ) {
			continue;
		}
		$pos_key_title = amber_sub( "erp_pkey{$pos_key_i}_title", $pos_key_d['title'] );
		if ( '' === trim( (string) $pos_key_title ) ) {
			continue;
		}

		$pos_key_img_raw = amber_sub( "erp_pkey{$pos_key_i}_image", '' );
		if ( is_array( $pos_key_img_raw ) && ! empty( $pos_key_img_raw['url'] ) ) {
			$pos_key_image = $pos_key_img_raw['url'];
		} elseif ( is_string( $pos_key_img_raw ) && '' !== $pos_key_img_raw ) {
			$pos_key_image = $pos_key_img_raw;
		} else {
			$pos_key_image = $pos_key_placeholder;
		}

		$pos_key_items[] = array(
			'title'     => $pos_key_title,
			'text'      => amber_sub( "erp_pkey{$pos_key_i}_text", $pos_key_d['text'] ),
			'image'     => $pos_key_image,
			'learn_url' => amber_sub( "erp_pkey{$pos_key_i}_url", '' ),
		);
	}

	// "Inventory management" (index 1) starts active, matching the reference.
	$pos_key_active = ( count( $pos_key_items ) > 1 ) ? 1 : 0;

	$pos_key_heading_lines = explode( "\n", $pos_key_heading, 2 );
	?>
	<?php if ( $pos_key_items ) : ?>
	<section class="pos-key" id="pos-key-features">
		<div class="pos-key__container">
			<h2 class="pos-key__heading"><?php echo esc_html( $pos_key_heading_lines[0] ); ?><?php if ( ! empty( $pos_key_heading_lines[1] ) ) : ?><br><?php echo esc_html( $pos_key_heading_lines[1] ); ?><?php endif; ?></h2>

			<div class="pos-key__row" data-pos-key>
				<ul class="pos-key__list" role="tablist" aria-label="<?php echo esc_attr( wp_strip_all_tags( str_replace( "\n", ' ', $pos_key_heading ) ) ); ?>">
					<?php foreach ( $pos_key_items as $pos_key_i => $pos_key_item ) : ?>
						<?php $pos_key_is_active = ( $pos_key_active === $pos_key_i ); ?>
						<li
							class="pos-key__item<?php echo $pos_key_is_active ? ' is-active' : ''; ?>"
							role="tab"
							tabindex="<?php echo $pos_key_is_active ? '0' : '-1'; ?>"
							aria-selected="<?php echo $pos_key_is_active ? 'true' : 'false'; ?>"
							aria-controls="pos-key-panel-<?php echo (int) $pos_key_i; ?>"
							data-pos-key-item
							data-pos-key-index="<?php echo (int) $pos_key_i; ?>">
							<?php echo esc_html( $pos_key_item['title'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="pos-key__panels">
					<?php foreach ( $pos_key_items as $pos_key_i => $pos_key_item ) : ?>
						<?php $pos_key_is_active = ( $pos_key_active === $pos_key_i ); ?>
						<div
							id="pos-key-panel-<?php echo (int) $pos_key_i; ?>"
							class="pos-key__panel<?php echo $pos_key_is_active ? ' is-active' : ''; ?>"
							role="tabpanel"
							<?php echo $pos_key_is_active ? '' : 'hidden'; ?>>
							<div class="pos-key__panel-head">
								<h3 class="pos-key__panel-title"><?php echo esc_html( $pos_key_item['title'] ); ?></h3>
								<p class="pos-key__panel-text"><?php echo esc_html( $pos_key_item['text'] ); ?></p>
								<?php if ( $pos_key_item['learn_url'] ) : ?>
									<a href="<?php echo esc_url( $pos_key_item['learn_url'] ); ?>" class="pos-key__panel-link"><?php echo esc_html( $pos_key_learn_label ); ?> &rarr;</a>
								<?php endif; ?>
							</div>
							<div class="pos-key__panel-media">
								<img
									src="<?php echo esc_url( $pos_key_item['image'] ); ?>"
									alt="<?php echo esc_attr( $pos_key_item['title'] ); ?>"
									loading="<?php echo $pos_key_is_active ? 'eager' : 'lazy'; ?>"
									decoding="async">
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

<?php elseif ( 'erp' === $erp_product['key'] ) : ?>
	<?php
	/* -----------------------------------------------------------------------
	 * Amber ERP Software hero — mirrors the VasyERP reference markup 1:1: a Bootstrap-
	 * style 3/6/3 row (decorative vector column, centred headline/lead/CTAs,
	 * decorative vector column), each vector column carrying two floating
	 * icon+label badges; a mobile-only duplicate of both vector groups
	 * (reference's own "mobile-floating-groups" block, since the desktop
	 * columns are display:none under lg); then a dashboard showcase with the
	 * main product screenshot and four floating stat cards overlapping its
	 * edges. Every image (vectors, badge icons, dashboard, floating cards) is
	 * a plain amber_sub()-editable image field, defaulting to the theme's
	 * bundled placeholder until real assets are uploaded — nothing here is
	 * pure-CSS/SVG. Section background is the uploaded "Hero background
	 * image" (erp_hero_bg_image), same field the other product pages use.
	 * Only rendered for the main Amber ERP Software product page — Amber Restaurant Software/Amber Laundry Software/POS
	 * keep their existing hero layouts.
	 * -------------------------------------------------------------------- */
	// This page's own background default (the uploaded VasyERP-style hero
	// background) overrides the shared $erp_hero_bg default set above, which
	// is still used as-is by the Amber Restaurant Software/Amber Laundry Software/POS dark-overlay hero. Reads
	// through the same erp_hero_bg_image ACF field (via $erp_hero_bg_raw,
	// already resolved above), so uploading a new image there still overrides
	// this too. Mirrors the same "is it actually set" check $erp_hero_bg
	// itself uses — an unset ACF image field comes back as bare `false`, not
	// '' or [], so a strict '' === check alone would miss it.
	$erp_hero_bg_is_set = ( is_array( $erp_hero_bg_raw ) && ! empty( $erp_hero_bg_raw['url'] ) )
		|| ( is_string( $erp_hero_bg_raw ) && '' !== $erp_hero_bg_raw );
	if ( ! $erp_hero_bg_is_set ) {
		$erp_hero_bg = content_url( '/uploads/2026/08/hero-bg-amber.webp' );
	}

	$erp_v2_placeholder = get_template_directory_uri() . '/assets/images/placeholder.svg';

	$erp_v2_img = static function ( $field, $fallback ) use ( $erp_v2_placeholder ) {
		$raw = amber_sub( $field, '' );
		if ( is_array( $raw ) && ! empty( $raw['url'] ) ) {
			return $raw['url'];
		} elseif ( is_string( $raw ) && '' !== $raw ) {
			return $raw;
		}
		return $fallback ? $fallback : $erp_v2_placeholder;
	};

	// Bundled defaults point at the actual uploaded reference assets (vectors,
	// badge icons, dashboard shot, floating cards) rather than the generic
	// placeholder — each is still overridable via its own ACF field above.
	$erp_v2_vector_left  = $erp_v2_img( 'erp_v2_vector_left', content_url( '/uploads/2026/08/vector-1.svg' ) );
	$erp_v2_vector_right = $erp_v2_img( 'erp_v2_vector_right', content_url( '/uploads/2026/08/vector-2.svg' ) );

	$erp_v2_badge1_icon  = $erp_v2_img( 'erp_v2_badge1_icon', content_url( '/uploads/2026/08/insight.svg' ) );
	$erp_v2_badge1_label = amber_sub( 'erp_v2_badge1_label', __( 'Real-time Insight', 'amber' ) );
	$erp_v2_badge2_icon  = $erp_v2_img( 'erp_v2_badge2_icon', content_url( '/uploads/2026/08/centralize.svg' ) );
	$erp_v2_badge2_label = amber_sub( 'erp_v2_badge2_label', __( 'Centralize Data Access', 'amber' ) );
	$erp_v2_badge3_icon  = $erp_v2_img( 'erp_v2_badge3_icon', content_url( '/uploads/2026/08/integration.svg' ) );
	$erp_v2_badge3_label = amber_sub( 'erp_v2_badge3_label', __( 'Seamless Integration', 'amber' ) );
	$erp_v2_badge4_icon  = $erp_v2_img( 'erp_v2_badge4_icon', content_url( '/uploads/2026/08/secure.svg' ) );
	$erp_v2_badge4_label = amber_sub( 'erp_v2_badge4_label', __( 'Secure & Reliable', 'amber' ) );

	$erp_v2_btn2_label = amber_sub( 'erp_v2_btn2_label', __( 'Explore Features', 'amber' ) );
	$erp_v2_btn2_url   = amber_sub( 'erp_v2_btn2_url', '#erp-services' );

	$erp_v2_dash = $erp_v2_img( 'erp_hero_image', content_url( '/uploads/2026/08/ChatGPT-Image-Aug-4-2026-10_25_40-AM-2.png' ) );

	$erp_v2_float1_icon = $erp_v2_img( 'erp_v2_float1_image', content_url( '/uploads/2026/08/floating-1-2.webp' ) );
	$erp_v2_float2_icon = $erp_v2_img( 'erp_v2_float2_image', content_url( '/uploads/2026/08/floating-2-2.webp' ) );
	$erp_v2_float3_icon = $erp_v2_img( 'erp_v2_float3_image', content_url( '/uploads/2026/08/floating-3-2.webp' ) );
	$erp_v2_float4_icon = $erp_v2_img( 'erp_v2_float4_image', content_url( '/uploads/2026/08/floating-4-2.webp' ) );
	?>
	<section class="erpv2-hero" style="background-image: url('<?php echo esc_url( $erp_hero_bg ); ?>');">
		<div class="container erpv2-hero__container">
			<div class="row align-items-center justify-content-center erpv2-hero__row">

				<!-- LEFT GROUP -->
				<div class="col-lg-3 d-none d-lg-block">
					<div class="erpv2-hero__vector erpv2-hero__vector--left">
						<img src="<?php echo esc_url( $erp_v2_vector_left ); ?>" class="erpv2-hero__vector-img" alt="">

						<div class="erpv2-hero__badge erpv2-hero__badge--1">
							<img src="<?php echo esc_url( $erp_v2_badge1_icon ); ?>" alt="" class="erpv2-hero__badge-icon">
							<span class="erpv2-hero__badge-text"><?php echo esc_html( $erp_v2_badge1_label ); ?></span>
						</div>

						<div class="erpv2-hero__badge erpv2-hero__badge--2">
							<img src="<?php echo esc_url( $erp_v2_badge2_icon ); ?>" alt="" class="erpv2-hero__badge-icon">
							<span class="erpv2-hero__badge-text"><?php echo esc_html( $erp_v2_badge2_label ); ?></span>
						</div>
					</div>
				</div>

				<!-- CENTER CONTENT -->
				<div class="col-lg-6 text-center">
					<h1 class="erpv2-hero__title">
						<?php echo esc_html( $erp_heading_accent ); ?>
						<span class="erpv2-hero__title-accent"><?php echo esc_html( $erp_heading_rest ); ?></span>
					</h1>

					<p class="erpv2-hero__lead"><?php echo esc_html( $erp_lead_bold ? $erp_lead_bold : $erp_lead ); ?></p>

					<div class="erpv2-hero__cta">
						<a href="<?php echo esc_url( $erp_btn1_url ); ?>" class="btn btn--erpv2-primary"><?php echo esc_html( $erp_btn1_label ); ?></a>
						<a href="<?php echo esc_url( $erp_v2_btn2_url ); ?>" class="btn btn--erpv2-outline"><?php echo esc_html( $erp_v2_btn2_label ); ?></a>
					</div>
				</div>

				<!-- RIGHT GROUP -->
				<div class="col-lg-3 d-none d-lg-block">
					<div class="erpv2-hero__vector erpv2-hero__vector--right">
						<img src="<?php echo esc_url( $erp_v2_vector_right ); ?>" class="erpv2-hero__vector-img" alt="">

						<div class="erpv2-hero__badge erpv2-hero__badge--1">
							<img src="<?php echo esc_url( $erp_v2_badge3_icon ); ?>" alt="" class="erpv2-hero__badge-icon">
							<span class="erpv2-hero__badge-text"><?php echo esc_html( $erp_v2_badge3_label ); ?></span>
						</div>

						<div class="erpv2-hero__badge erpv2-hero__badge--2">
							<img src="<?php echo esc_url( $erp_v2_badge4_icon ); ?>" alt="" class="erpv2-hero__badge-icon">
							<span class="erpv2-hero__badge-text"><?php echo esc_html( $erp_v2_badge4_label ); ?></span>
						</div>
					</div>
				</div>

				<!-- MOBILE FLOATING GROUPS -->
				

				<!-- Dashboard Showcase -->
				<div class="erpv2-hero__showcase">

					<div class="erpv2-hero__dash">
						<img src="<?php echo esc_url( $erp_v2_dash ); ?>" alt="<?php echo esc_attr( trim( $erp_heading_accent . ' ' . $erp_heading_rest ) ); ?>" class="erpv2-hero__dash-img">
					</div>

					<div class="erpv2-hero__float erpv2-hero__float--1">
						<img src="<?php echo esc_url( $erp_v2_float1_icon ); ?>" alt="<?php esc_attr_e( 'Payment', 'amber' ); ?>" class="erpv2-hero__float-img">
					</div>

					<div class="erpv2-hero__float erpv2-hero__float--2">
						<img src="<?php echo esc_url( $erp_v2_float2_icon ); ?>" alt="<?php esc_attr_e( 'Total sales', 'amber' ); ?>" class="erpv2-hero__float-img">
					</div>

					<div class="erpv2-hero__float erpv2-hero__float--3">
						<img src="<?php echo esc_url( $erp_v2_float3_icon ); ?>" alt="<?php esc_attr_e( 'New order', 'amber' ); ?>" class="erpv2-hero__float-img">
					</div>

					<div class="erpv2-hero__float erpv2-hero__float--4">
						<img src="<?php echo esc_url( $erp_v2_float4_icon ); ?>" alt="<?php esc_attr_e( 'Top product', 'amber' ); ?>" class="erpv2-hero__float-img">
					</div>

				</div>

			</div>
		</div>
	</section>
	<?php
	/* -----------------------------------------------------------------------
	 * ERP challenges — "ERP is the key to solving retail challenges" (mirrors
	 * the VasyERP reference 1:1): a centred one-line heading/lead, then a
	 * circular photo centred inside the uploaded "arrows" frame (a single
	 * square image with all four L-shaped connector arrows pre-drawn at its
	 * corners, transparent middle where the circle sits), with the four
	 * problem callouts pinned just outside each corner, and a down-arrow
	 * closing the section into whatever comes next. Every image/heading/copy
	 * is amber_sub()-editable; images fall back to the theme's placeholder
	 * until real assets are uploaded via the "Amber — ERP Page" ACF group.
	 * Only rendered on the main Amber ERP Software product page.
	 * -------------------------------------------------------------------- */
	$erp_chal_heading = amber_sub( 'erp_chal_heading', __( 'ERP is the key to solving retail challenges', 'amber' ) );
	$erp_chal_lead     = amber_sub( 'erp_chal_lead', __( 'VasyERP eliminates operational issues with its integrated ERP solution for modern retail stores.', 'amber' ) );

	$erp_chal_arrows_img = $erp_v2_img( 'erp_chal_arrows_image', content_url( '/uploads/2026/08/arrows.webp' ) );

	$erp_chal_photo_img  = $erp_v2_img( 'erp_chal_photo_image', content_url( '/uploads/2026/08/ChatGPT-Image-Aug-4-2026-11_33_56-AM.png' ) );
	$erp_chal_down_img   = $erp_v2_img( 'erp_chal_down_image', '' );

	$erp_chal_items = array(
		'streamlined' => array(
			'icon'  => $erp_v2_img( 'erp_chal1_icon', content_url( '/uploads/2026/08/streamlined-retail-operations.webp' ) ),
			'title' => amber_sub( 'erp_chal1_title', __( 'Streamlined Retail Operations', 'amber' ) ),
			'text'  => amber_sub( 'erp_chal1_text', __( 'Is managing the invoicing process, billing, accounting, CRM, payments, and navigating both -online and offline retail store a difficult task for you?', 'amber' ) ),
		),
		'inventory'   => array(
			'icon'  => $erp_v2_img( 'erp_chal2_icon', content_url( '/uploads/2026/08/inventory-tracking.webp' ) ),
			'title' => amber_sub( 'erp_chal2_title', __( 'Inventory Tracking', 'amber' ) ),
			'text'  => amber_sub( 'erp_chal2_text', __( 'Issues with tracking inventory, variants, product expiries, and day-to-day operations related to stock management for multiple stores?', 'amber' ) ),
		),
		'integration' => array(
			'icon'  => $erp_v2_img( 'erp_chal3_icon', content_url( '/uploads/2026/08/integration-complexity.webp' ) ),
			'title' => amber_sub( 'erp_chal3_title', __( 'Integration Complexity', 'amber' ) ),
			'text'  => amber_sub( 'erp_chal3_text', __( 'Do you encounter difficulties when integrating and synchronizing multiple systems and their data such as inventory management, sales, billing, and financials?', 'amber' ) ),
		),
		'analytics'   => array(
			'icon'  => $erp_v2_img( 'erp_chal4_icon', content_url( '/uploads/2026/08/business-analytics-and-reports.webp' ) ),
			'title' => amber_sub( 'erp_chal4_title', __( 'Business Analytics and Reports', 'amber' ) ),
			'text'  => amber_sub( 'erp_chal4_text', __( 'Do you have difficulties sourcing precise sales data, inventory statistics, real-time data and other important business reports necessary for informed decision-making?', 'amber' ) ),
		),
	);
	?>
	<section class="erp-chal">
		<div class="container">
			<div class="erp-chal__head">
				<h2 class="erp-chal__heading"><?php echo esc_html( $erp_chal_heading ); ?></h2>
				<p class="erp-chal__lead"><?php echo esc_html( $erp_chal_lead ); ?></p>
			</div>

			<div class="erp-chal__ring">
				<div class="erp-chal__frame">
					<img loading="lazy" src="<?php echo esc_url( $erp_chal_arrows_img ); ?>" alt="" class="erp-chal__arrows">
					<div class="erp-chal__photo">
						<?php amber_image( $erp_chal_photo_img, 'large', array( 'alt' => esc_attr__( 'Challenges in Retail Shop', 'amber' ) ) ); ?>
					</div>
				</div>

				<div class="erp-chal__item erp-chal__item--tl">
					<?php amber_image( $erp_chal_items['streamlined']['icon'], 'thumbnail', array( 'class' => 'erp-chal__icon', 'alt' => '' ) ); ?>
					<div class="erp-chal__text">
						<h4><?php echo esc_html( $erp_chal_items['streamlined']['title'] ); ?></h4>
						<p><?php echo esc_html( $erp_chal_items['streamlined']['text'] ); ?></p>
					</div>
				</div>

				<div class="erp-chal__item erp-chal__item--tr">
					<?php amber_image( $erp_chal_items['integration']['icon'], 'thumbnail', array( 'class' => 'erp-chal__icon', 'alt' => '' ) ); ?>
					<div class="erp-chal__text">
						<h4><?php echo esc_html( $erp_chal_items['integration']['title'] ); ?></h4>
						<p><?php echo esc_html( $erp_chal_items['integration']['text'] ); ?></p>
					</div>
				</div>

				<div class="erp-chal__item erp-chal__item--bl">
					<?php amber_image( $erp_chal_items['inventory']['icon'], 'thumbnail', array( 'class' => 'erp-chal__icon', 'alt' => '' ) ); ?>
					<div class="erp-chal__text">
						<h4><?php echo esc_html( $erp_chal_items['inventory']['title'] ); ?></h4>
						<p><?php echo esc_html( $erp_chal_items['inventory']['text'] ); ?></p>
					</div>
				</div>

				<div class="erp-chal__item erp-chal__item--br">
					<?php amber_image( $erp_chal_items['analytics']['icon'], 'thumbnail', array( 'class' => 'erp-chal__icon', 'alt' => '' ) ); ?>
					<div class="erp-chal__text">
						<h4><?php echo esc_html( $erp_chal_items['analytics']['title'] ); ?></h4>
						<p><?php echo esc_html( $erp_chal_items['analytics']['text'] ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	/* -----------------------------------------------------------------------
	 * Key Features — mirrors the VasyERP reference's "Key features of India's
	 * #1 ERP" carousel: centred heading/lead, then a single-slide carousel
	 * with a large illustration on the left and a light card (title + body
	 * text) on the right, auto-advancing on a timer. Each slide has its own
	 * illustration + copy, editable via the "Amber — ERP Page" group's "Key
	 * Features" tab; clearing a slide's title in the admin removes it from
	 * the carousel. Reimplemented as a small vanilla-JS crossfade slider
	 * (assets/js/modules/erp-keyfeatures-slider.js) — one active slide at a
	 * time, not a scrolling multi-card strip. Images fall back to the theme's
	 * placeholder until real assets are uploaded.
	 * -------------------------------------------------------------------- */
	$erp_kf_heading = amber_sub( 'erp_kf_heading', $erp_t( __( 'Key features of Amber ERP Software for retail stores', 'amber' ) ) );
	$erp_kf_lead    = amber_sub( 'erp_kf_lead', __( 'Retail Innovation: One Solution Fits All With Extensive Features', 'amber' ) );

	$erp_kf_defaults = array(
		1 => array(
			'title' => __( 'Seamless Omnichannel Integration', 'amber' ),
			'text'  => __( "Effortlessly expand your retail presence with Amber ERP Software's omnichannel capabilities. Whether it's an online shop, marketplace integrations, or your own custom storefront, Amber ERP Software simplifies omnichannel store management from one dashboard.", 'amber' ),
		),
		2 => array(
			'title' => __( 'Notifications and Reminders', 'amber' ),
			'text'  => __( 'Stay connected with your business through WhatsApp, SMS, and email notifications. Receive reminders for crucial reports, payment deadlines, and customer occasions such as birthdays and anniversaries — plus bulk promotional messages, stock alerts, and payable/receivable summaries.', 'amber' ),
		),
		3 => array(
			'title' => __( 'Harness the Power of Integrations', 'amber' ),
			'text'  => __( 'Connect Amber ERP Software with the accounting, payment, logistics and e-commerce tools you already use, so data flows automatically between every system instead of being re-entered by hand.', 'amber' ),
		),
		4 => array(
			'title' => __( 'Real-Time Inventory Control', 'amber' ),
			'text'  => __( 'Track stock across every outlet and warehouse in real time, with low-stock alerts and automated reorder suggestions that keep shelves full without tying up capital in excess inventory.', 'amber' ),
		),
		5 => array(
			'title' => __( 'Advanced Reporting and Analytics', 'amber' ),
			'text'  => __( 'Turn every transaction into a clear, visual report — sales trends, profit margins, staff performance and more — so decisions are backed by real data instead of guesswork.', 'amber' ),
		),
	);

	$erp_kf_items = array();
	foreach ( $erp_kf_defaults as $kf_n => $kf_d ) {
		if ( amber_erp_slot_is_cleared( "erp_kf{$kf_n}_title" ) ) {
			continue;
		}
		$kf_title = amber_sub( "erp_kf{$kf_n}_title", $kf_d['title'] );
		if ( '' === trim( (string) $kf_title ) ) {
			continue;
		}
		$erp_kf_items[] = array(
			'image' => $erp_v2_img( "erp_kf{$kf_n}_image", '' ),
			'title' => $kf_title,
			'text'  => amber_sub( "erp_kf{$kf_n}_text", $kf_d['text'] ),
		);
	}
	?>
	<?php if ( count( $erp_kf_items ) > 1 ) : ?>
	<section class="erp-kf">
		<div class="container">
			<div class="erp-kf__head">
				<h2 class="erp-kf__heading"><?php echo esc_html( $erp_kf_heading ); ?></h2>
				<p class="erp-kf__lead"><?php echo esc_html( $erp_kf_lead ); ?></p>
			</div>

			<div class="erp-kf__slider" data-erp-kf data-erp-kf-autoplay="5000">
				<div class="erp-kf__viewport">
					<div class="erp-kf__track" data-erp-kf-track role="region" aria-live="polite">
						<?php foreach ( $erp_kf_items as $kf_i => $kf_item ) : ?>
						<div class="erp-kf__slide<?php echo ( 0 === $kf_i ) ? ' is-active' : ''; ?>" data-erp-kf-slide<?php echo ( 0 !== $kf_i ) ? ' hidden' : ''; ?>>
							<div class="erp-kf__media">
								<?php amber_image( $kf_item['image'], 'large', array( 'class' => 'erp-kf__media-img', 'alt' => $kf_item['title'] ) ); ?>
							</div>
							<div class="erp-kf__card">
								<h3 class="erp-kf__card-title"><?php echo esc_html( $kf_item['title'] ); ?></h3>
								<p class="erp-kf__card-text"><?php echo esc_html( $kf_item['text'] ); ?></p>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<?php
	/* -----------------------------------------------------------------------
	 * "Why Amber is the best ERP software in India?" — mirrors the VasyERP
	 * reference's Slick.js card carousel: centred heading/lead, then 7
	 * icon+heading+text cards, 3 visible at a time (2 tablet / 1 mobile) with
	 * prev/next arrow buttons that loop. Reimplemented as a small vanilla-JS
	 * slider (assets/js/modules/erp-why-slider.js) instead of pulling in the
	 * Slick.js library — same clone-and-loop technique already used by the
	 * reviews slider. Every heading/text is amber_sub()-editable; icons
	 * default to the already-uploaded reference icon set.
	 * -------------------------------------------------------------------- */
	$erp_why_heading = amber_sub( 'erp_why_heading', __( 'Why Amber is the best ERP software in India?', 'amber' ) );
	$erp_why_lead     = amber_sub( 'erp_why_lead', __( 'An ERP software packed with features and integrations that simplify the management of retail stores.', 'amber' ) );

	$erp_why_items = array(
		array(
			'icon'  => $erp_v2_img( 'erp_why1_icon', content_url( '/uploads/2026/08/easy.webp' ) ),
			'title' => amber_sub( 'erp_why1_title', __( 'Built for Retail Businesses', 'amber' ) ),
			'text'  => amber_sub( 'erp_why1_text', __( 'Incorporates functionalities that are specifically tailored and suitable for the retail sector.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why2_icon', content_url( '/uploads/2026/08/integration.webp' ) ),
			'title' => amber_sub( 'erp_why2_title', __( 'Operational Across Multiple Devices', 'amber' ) ),
			'text'  => amber_sub( 'erp_why2_text', __( 'Utilize ERP features on any device, from mobiles to laptops, while on the move.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why3_icon', content_url( '/uploads/2026/08/Customer-Support.webp' ) ),
			'title' => amber_sub( 'erp_why3_title', __( 'Quick Support', 'amber' ) ),
			'text'  => amber_sub( 'erp_why3_text', __( 'Immediate and reliable support that minimize downtime and ensure smooth operations.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why4_icon', content_url( '/uploads/2026/08/Accounting.webp' ) ),
			'title' => amber_sub( 'erp_why4_title', __( 'Cloud-based ERP Software', 'amber' ) ),
			'text'  => amber_sub( 'erp_why4_text', __( 'Retrieve your retail information online whenever and wherever you need it.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why5_icon', content_url( '/uploads/2026/08/Hardware.webp' ) ),
			'title' => amber_sub( 'erp_why5_title', __( 'Client Satisfaction', 'amber' ) ),
			'text'  => amber_sub( 'erp_why5_text', __( '12K+ satisfied customers that vouch for our seamless services and user experience.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why6_icon', content_url( '/uploads/2026/08/No-Dataloss.webp' ) ),
			'title' => amber_sub( 'erp_why6_title', __( 'Data Security and Safety', 'amber' ) ),
			'text'  => amber_sub( 'erp_why6_text', __( 'Full-compliance, stringent security protocols and business data protection.', 'amber' ) ),
		),
		array(
			'icon'  => $erp_v2_img( 'erp_why7_icon', content_url( '/uploads/2026/08/easy.webp' ) ),
			'title' => amber_sub( 'erp_why7_title', __( 'Built for Retail Businesses', 'amber' ) ),
			'text'  => amber_sub( 'erp_why7_text', __( 'Incorporates functionalities that are specifically tailored and suitable for the retail sector.', 'amber' ) ),
		),
	);
	?>
	<section class="erp-why">
		<div class="container">
			<div class="erp-why__head">
				<h2 class="erp-why__heading"><?php echo esc_html( $erp_why_heading ); ?></h2>
				<p class="erp-why__lead"><?php echo esc_html( $erp_why_lead ); ?></p>
			</div>

			<div class="erp-why__slider" data-erp-why data-erp-why-autoplay="4000">
				<button type="button" class="erp-why__arrow erp-why__arrow--prev" data-erp-why-prev aria-label="<?php esc_attr_e( 'Previous', 'amber' ); ?>">
					<svg width="14" height="24" viewBox="0 0 14 24" fill="none" aria-hidden="true"><path d="M12 2L2 12L12 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>

				<div class="erp-why__viewport" data-erp-why-viewport>
					<div class="erp-why__track" data-erp-why-track>
						<?php foreach ( $erp_why_items as $why_item ) : ?>
						<div class="erp-why__card">
							<div class="erp-why__icon-wrap">
								<?php amber_image( $why_item['icon'], 'thumbnail', array( 'class' => 'erp-why__icon', 'alt' => '' ) ); ?>
							</div>
							<h4 class="erp-why__card-title"><?php echo esc_html( $why_item['title'] ); ?></h4>
							<p class="erp-why__card-text"><?php echo esc_html( $why_item['text'] ); ?></p>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<button type="button" class="erp-why__arrow erp-why__arrow--next" data-erp-why-next aria-label="<?php esc_attr_e( 'Next', 'amber' ); ?>">
					<svg width="14" height="24" viewBox="0 0 14 24" fill="none" aria-hidden="true"><path d="M2 2L12 12L2 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>
			</div>
		</div>
	</section>

<?php else : ?>
	<section class="hero-section-outer" style="background-image: url('<?php echo esc_url( $erp_hero_bg ); ?>');">
		<div class="container hero-section">
			<div class="automated-time-tracking-banner">
				<div class="banner-content aos-animate" data-aos="fade-up" data-aos-duration="1000">
					<h1><span class="text-primary"><?php echo esc_html( $erp_heading_accent ); ?></span> <span class="banner-title-rest"><?php echo esc_html( $erp_heading_rest ); ?></span></h1>
					<?php if ( $erp_lead_bold ) : ?>
						<p class="banner-lead-bold"><?php echo esc_html( $erp_lead_bold ); ?></p>
					<?php endif; ?>
					<p><?php echo esc_html( $erp_lead ); ?></p>
					<div class="hero-section-btn aos-animate" data-aos="fade-up" data-aos-delay="200">
						<a class="btn btn-primary banner-primary-btn" href="<?php echo esc_url( $erp_btn1_url ); ?>">
							<?php echo esc_html( $erp_btn1_label ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>

<?php
/* ---------------------------------------------------------------------------
 * POS — "How to choose the right POS solution" checklist + faux mockup.
 *
 * Sits right after the hero/trust strip, before the dark ERP-services band.
 * Same pure-CSS/SVG "Fresh Basket" panel + phone approach as the homepage
 * `choose` section (template-parts/sections/section-choose.php) — no images.
 * Only rendered on the POS product page, matching the reference design.
 * ------------------------------------------------------------------------ */
if ( 'pos' === $erp_product['key'] ) :
	amber_section_part( 'choose' );
endif;
?>

<?php
/* ---------------------------------------------------------------------------
 * Amber Laundry Software — interactive feature list ("Point of Sale" reference layout).
 *
 * Two-column layout: a clickable list of features on the left (icon + title,
 * with the active row's description + a mobile-only inline image expanding
 * beneath it), and — on desktop — a single image on the right that crossfades
 * to match whichever feature is active. Directly mirrors the CleanCloud
 * reference screenshot: click a row, the right-hand screenshot swaps.
 *
 * Only rendered for Amber Laundry Software; every other product keeps its existing page flow.
 * Content is fully editable via amber_sub(); images fall back to the theme's
 * placeholder so the section always renders complete with no data.
 * ------------------------------------------------------------------------ */
if ( 'gozzbe' === $erp_product['key'] ) :
	$gz_placeholder_img = get_template_directory_uri() . '/assets/images/placeholder.svg';
	$gz_icons_dir_uri   = get_template_directory_uri() . '/assets/images/gozzbe/icons/';

	/**
	 * One interactive feature-list section. $slug namespaces the ACF fields
	 * (erp_gzf_{slug}_...) so multiple groups (Point of Sale, Marketing,
	 * Workflows…) can reuse this without colliding. $active_index picks which
	 * item starts expanded/visible (server-rendered, before JS's autoplay
	 * takes over) — the reference marks a different item active per section.
	 */
	$amber_render_gz_feature_section = function ( $slug, $heading_default, $sub_default, $items_default, $active_index = 0 ) use ( $gz_placeholder_img, $gz_icons_dir_uri ) {
		$heading = amber_sub( "erp_gzf_{$slug}_heading", $heading_default );
		$sub     = amber_sub( "erp_gzf_{$slug}_sub", $sub_default );

		$items = array();
		foreach ( $items_default as $i => $d ) {
			$n = $i + 1;
			if ( amber_erp_slot_is_cleared( "erp_gzf_{$slug}{$n}_title" ) ) {
				continue;
			}
			$title = amber_sub( "erp_gzf_{$slug}{$n}_title", $d['title'] );
			if ( '' === trim( (string) $title ) ) {
				continue;
			}

			$img_raw = amber_sub( "erp_gzf_{$slug}{$n}_image", '' );
			if ( is_array( $img_raw ) && ! empty( $img_raw['url'] ) ) {
				$image = $img_raw['url'];
			} elseif ( is_string( $img_raw ) && '' !== $img_raw ) {
				$image = $img_raw;
			} else {
				$image = $gz_placeholder_img;
			}

			$items[] = array(
				'title' => $title,
				'desc'  => amber_sub( "erp_gzf_{$slug}{$n}_desc", $d['desc'] ),
				'icon'  => $d['icon'],
				'image' => $image,
			);
		}

		if ( ! $items ) {
			return;
		}

		// Clamp: if editing cleared out items above the requested index, fall
		// back to the first item rather than rendering nothing as "active".
		if ( $active_index < 0 || $active_index >= count( $items ) ) {
			$active_index = 0;
		}
		?>
		<section class="gzf-section" id="gzf-<?php echo esc_attr( $slug ); ?>">
			<div class="gzf-container">
				<div class="gzf-head">
					<h2 class="gzf-head__title"><?php echo esc_html( $heading ); ?></h2>
					<?php if ( $sub ) : ?>
						<p class="gzf-head__sub"><?php echo esc_html( $sub ); ?></p>
					<?php endif; ?>
				</div>

				<div class="gzf-grid" data-gzf>
					<div class="gzf-list" role="tablist" aria-label="<?php echo esc_attr( $heading ); ?>">
						<?php foreach ( $items as $i => $item ) : ?>
							<?php $is_active = ( $active_index === $i ); ?>
							<div
								class="gzf-item<?php echo $is_active ? ' is-active' : ''; ?>"
								role="tab"
								tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
								aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
								aria-controls="gzf-<?php echo esc_attr( $slug ); ?>-img-<?php echo (int) $i; ?>"
								data-gzf-item
								data-gzf-index="<?php echo (int) $i; ?>">
								<div class="gzf-item__head">
									<span class="gzf-item__icon" aria-hidden="true">
										<img src="<?php echo esc_url( $gz_icons_dir_uri . $item['icon'] . '.svg' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async">
									</span>
									<h3 class="gzf-item__title"><?php echo esc_html( $item['title'] ); ?></h3>
								</div>
								<div class="gzf-item__body">
									<div class="gzf-item__body-inner">
										<p class="gzf-item__desc">
											<?php echo esc_html( $item['desc'] ); ?>
											<img
												class="gzf-item__img-mobile"
												src="<?php echo esc_url( $item['image'] ); ?>"
												alt="<?php echo esc_attr( $item['title'] ); ?>"
												loading="lazy"
												decoding="async">
										</p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="gzf-visual" aria-hidden="true">
						<div class="gzf-visual__stage">
							<?php foreach ( $items as $i => $item ) : ?>
								<img
									id="gzf-<?php echo esc_attr( $slug ); ?>-img-<?php echo (int) $i; ?>"
									class="gzf-visual__img<?php echo ( $active_index === $i ) ? ' is-active' : ''; ?>"
									src="<?php echo esc_url( $item['image'] ); ?>"
									alt=""
									loading="<?php echo ( $active_index === $i ) ? 'eager' : 'lazy'; ?>"
									decoding="async">
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	};

	$amber_render_gz_feature_section(
		'pos',
		__( 'Point of Sale', 'amber' ),
		__( 'The heartbeat of your front counter, simplified. Handle every transaction with a system built for speed, accuracy, and ease — so you can focus on your customers, not your screen.', 'amber' ),
		array(
			array(
				'title' => __( 'Easy To Use', 'amber' ),
				'desc'  => __( 'A sleek, intuitive interface lets you find items and add notes in a few taps, making every transaction easy and accurate.', 'amber' ),
				'icon'  => 'easy-to-learn',
			),
			array(
				'title' => __( 'Detailing', 'amber' ),
				'desc'  => __( 'Capture every detail, from a missing button to a special detergent, to specific notes and custom care instructions.', 'amber' ),
				'icon'  => 'detailing',
			),
			array(
				'title' => __( 'Payments', 'amber' ),
				'desc'  => __( 'Our fully integrated solution lets you accept online, in-store, and in-app payments, with low fees that put more profit in your pocket.', 'amber' ),
				'icon'  => 'payments',
			),
			array(
				'title' => __( 'Digital Waivers', 'amber' ),
				'desc'  => __( 'Go paperless with integrated digital waivers. Customers can sign on-screen, and waivers are automatically saved to their profile.', 'amber' ),
				'icon'  => 'digital-waivers',
			),
			array(
				'title' => __( 'Automated Communication', 'amber' ),
				'desc'  => __( 'Automatically sends updates via SMS, Email, Push & WhatsApp when their order is ready, and send automatic reminders too.', 'amber' ),
				'icon'  => 'automated-communication',
			),
			array(
				'title' => __( 'First Order Identifier', 'amber' ),
				'desc'  => __( "A yellow '1st Order' icon lets your team know it's a customer's first order, and helps them give the best first impression.", 'amber' ),
				'icon'  => 'first-order-identifier',
			),
		)
	);

	$amber_render_gz_feature_section(
		'marketing',
		__( 'Marketing', 'amber' ),
		__( 'All the tools you need to bring in new customers, keep them coming back, and grow smarter — from one platform, so you can build your brand with confidence and automate your growth from day one.', 'amber' ),
		array(
			array(
				'title' => __( 'Website Builder', 'amber' ),
				'desc'  => __( 'Build and customize a site that looks beautiful on any device, and is fully integrated with your POS.', 'amber' ),
				'icon'  => 'website-builder',
			),
			array(
				'title' => __( 'Google Business Profile', 'amber' ),
				'desc'  => __( 'A "Book Now" button on your profile lets customers book directly, so you can turn searches into sales.', 'amber' ),
				'icon'  => 'google-business-profile',
			),
			array(
				'title' => __( 'Email, SMS & Push Marketing', 'amber' ),
				'desc'  => __( 'Send professional emails and targeted messages to key sections of your customer list, ensuring your updates are always seen.', 'amber' ),
				'icon'  => 'email-sms-push-notifications',
			),
			array(
				'title' => __( 'Automated Marketing Flows and Builder', 'amber' ),
				'desc'  => __( 'Set up automated flows that engage customers, increase orders, and drive revenue while you focus on your business.', 'amber' ),
				'icon'  => 'automated-flows',
			),
			array(
				'title' => __( '5-Star Customer Reviews', 'amber' ),
				'desc'  => __( 'Automatically request reviews from your customers on Google, Yelp, Facebook and Nextdoor.', 'amber' ),
				'icon'  => 'reviews',
			),
			array(
				'title' => __( 'Ai Agent', 'amber' ),
				'desc'  => __( "Embed our AI Agent on your website, and handle customer questions 24/7 using your store's information.", 'amber' ),
				'icon'  => 'ai-chatbot',
			),
		),
		5 // "Ai Agent" starts active, matching the reference.
	);

	$amber_render_gz_feature_section(
		'workflows',
		__( 'Workflows', 'amber' ),
		__( 'Whether you\'re running one store or managing a multi-location operation, stay in control. Assign orders, track progress, manage staff, and handle payments — all from one dashboard.', 'amber' ),
		array(
			array(
				'title' => __( 'Wash and Fold Tracking', 'amber' ),
				'desc'  => __( 'Assign orders to washers and dryers and track activity in real time — so every load stays on schedule.', 'amber' ),
				'icon'  => 'wash-and-fold-tracking',
			),
			array(
				'title' => __( 'Auto and Assisted Assembly', 'amber' ),
				'desc'  => __( "Connect with MetalProgetti and QuickSort to automate your flow, whether you're managing a single store or full-scale plant.", 'amber' ),
				'icon'  => 'assembly',
			),
			array(
				'title' => __( 'Lockers', 'amber' ),
				'desc'  => __( 'Choose one of our locker partners to automate the workflow between your lockers and Amber Laundry Software.', 'amber' ),
				'icon'  => 'lockers',
			),
			array(
				'title' => __( 'Barcode Tagging', 'amber' ),
				'desc'  => __( 'Scan barcode labels and instantly assign garments to the right rack — no tags, no manual sorting.', 'amber' ),
				'icon'  => 'barcode-tagging',
			),
			array(
				'title' => __( 'Washer/Dryer Payment Integrations', 'amber' ),
				'desc'  => __( 'The systems you rely on are connected — so you can move faster and stay focused on the job.', 'amber' ),
				'icon'  => 'dryer-integrations',
			),
			array(
				'title' => __( 'Multi-Store Plant', 'amber' ),
				'desc'  => __( 'Barcode-powered tracking, real-time status updates, and customizable workflows built for how you actually work.', 'amber' ),
				'icon'  => 'plant',
			),
		),
		4 // "Washer/Dryer Payment Integrations" starts active, matching the reference.
	);

	unset( $amber_render_gz_feature_section );
endif;
?>

<?php
/* ---------------------------------------------------------------------------
 * ERP Services — numbered two-column services grid (dark, per the reference):
 * big split heading left / right-aligned intro, a hairline rule, then ten
 * numbered services in two columns with vertical hairlines between them and a
 * faint grid + glow background. Editable via the "Amber — ERP Services" group;
 * defaults come from amber_erp_service_defaults().
 * ------------------------------------------------------------------------ */
$erp_svc_heading = amber_sub( 'erp_svc_heading', __( 'End-to-End', 'amber' ) );
$erp_svc_accent  = amber_sub( 'erp_svc_heading_accent', $erp_t( __( 'ERP Solutions & Services', 'amber' ) ) );
$erp_svc_desc    = amber_sub(
	'erp_svc_desc',
	$erp_t( __( 'Our experienced team delivers top-tier ERP implementation and support. We specialise in finance, inventory, sales and operations, and provide complete solutions — including custom modules and integrations — to ensure your business runs smoothly across every department.', 'amber' ) )
);

$erp_services = array();
foreach ( amber_erp_service_defaults() as $i => $d ) {
	$erp_services[ $i ] = array(
		'title' => amber_sub( "erp_svc{$i}_title", $erp_t( $d['title'] ) ),
		'text'  => amber_sub( "erp_svc{$i}_text", $erp_t( $d['text'] ) ),
	);
}
?>

<!-- ERP Services — numbered two-column grid -->


<?php
/* ---------------------------------------------------------------------------
 * Industries We Serve — per the reference: heading + intro + outline CTA on
 * the left, a staggered grid of white icon cards on the right with a soft
 * brand blob behind. Editable via the "Amber — ERP Industries" group;
 * defaults come from amber_erp_industry_defaults().
 * ------------------------------------------------------------------------ */
$erp_ind_heading   = amber_sub( 'erp_ind_heading', __( 'Industries We Serve', 'amber' ) );
$erp_ind_desc      = amber_sub( 'erp_ind_desc', $erp_t( __( 'We have extensive experience delivering Amber ERP Software across every kind of operation — each rollout tuned to the workflows, compliance needs and pace of your industry.', 'amber' ) ) );
$erp_ind_btn_label = amber_sub( 'erp_ind_btn_label', __( 'Explore Industry Solutions', 'amber' ) );
$erp_ind_btn_url   = amber_sub( 'erp_ind_btn_url', home_url( '/contact-us/' ) );

$erp_industries = array();
foreach ( amber_erp_industry_defaults() as $i => $d ) {
	$erp_industries[ $i ] = array(
		'icon'  => amber_sub( "erp_ind{$i}_icon", $d['icon'] ),
		'label' => amber_sub( "erp_ind{$i}_label", $d['label'] ),
	);
}

// Staggered 2/3/3 column split, like the reference's offset card columns.
$erp_ind_cols = array(
	array( 1, 2 ),
	array( 3, 4, 5 ),
	array( 6, 7, 8 ),
);
?>

<!-- Industries We Serve — staggered icon cards -->


<?php
/* ---------------------------------------------------------------------------
 * Brands — "Brands That Trust Amber ERP Software" logo strip: large split-accent
 * heading + subtitle, then a continuously auto-scrolling marquee of brand
 * logos (reuses the same [data-logos-track] mechanism/JS as the homepage
 * trusted-by slider). Sits above the CTA panel. Editable via the
 * "Amber — ERP Brands" ACF group; each slot defaults to a bundled placeholder
 * logo SVG, overridden by an admin-uploaded image.
 * ------------------------------------------------------------------------ */
$erp_brands_heading  = amber_sub( 'erp_brands_heading', sprintf( '%s %s %s', __( 'Connect', 'amber' ), $erp_product['name'], __( 'with your favorite tools and apps', 'amber' ) ) );
$erp_brands_sub      = amber_sub( 'erp_brands_sub', $erp_t( __( 'Supercharge your experience by connecting with the tools you already use – and manage everything in one place, seamlessly.', 'amber' ) ) );
$erp_brands_btn_label = amber_sub( 'erp_brands_btn_label', __( 'Start a free trial', 'amber' ) );
$erp_brands_btn_url   = amber_sub( 'erp_brands_btn_url', home_url( '/contact-us/' ) );
$erp_brands_btn2_label = amber_sub( 'erp_brands_btn2_label', __( 'View all integrations', 'amber' ) );
$erp_brands_btn2_url   = amber_sub( 'erp_brands_btn2_url', home_url( '/contact-us/' ) );

// The logos themselves are one shared site-wide set (wp-admin → Client Logos),
// so this grid, the POS hero strip and the homepage marquee always show the
// same clients. Only the heading and buttons above stay per-product. The grid
// is a fixed 6-across, 3-row block, so the set is cycled to fill all 18 tiles.
$erp_brands = amber_client_logos_grid( 18, 6 );
?>

<!-- Brands That Trust — integrations logo grid -->
<section class="section section--erp-brands is-bg-white" id="erp-brands">
	<div class="container erp-brands__container">

		<div class="erp-brands__head">
			<h2 class="erp-brands__heading"><?php echo esc_html( $erp_brands_heading ); ?></h2>
			<?php if ( $erp_brands_sub ) : ?>
				<p class="erp-brands__sub"><?php echo esc_html( $erp_brands_sub ); ?></p>
			<?php endif; ?>
		</div>

		<div class="erp-brands__grid">
			<?php foreach ( $erp_brands as $brand ) : ?>
				<div class="erp-brands__logo<?php echo empty( $brand['logo'] ) ? ' erp-brands__logo--text' : ''; ?>">
					<?php if ( ! empty( $brand['logo'] ) ) : ?>
						<?php amber_image( $brand['logo'], 'medium', array( 'alt' => $brand['name'] ) ); ?>
					<?php else : ?>
						<span class="erp-brands__wordmark"><?php echo esc_html( $brand['name'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="erp-brands__actions">
			<a href="<?php echo esc_url( $erp_brands_btn_url ); ?>" class="erp-brands__btn-primary"><?php echo esc_html( $erp_brands_btn_label ); ?></a>
			<a href="<?php echo esc_url( $erp_brands_btn2_url ); ?>" class="erp-brands__btn-gray"><?php echo esc_html( $erp_brands_btn2_label ); ?></a>
		</div>

	</div>
</section>

<?php
/* ---------------------------------------------------------------------------
 * FAQ — ERP questions answered with the theme's accessible accordion
 * (single-open, button[aria-expanded] + region) and FAQPage JSON-LD for rich
 * results. Editable via the "Amber — ERP FAQ" group; defaults come from
 * amber_erp_faq_defaults().
 * ------------------------------------------------------------------------ */
$erp_faq_eyebrow = amber_sub( 'erp_faq_eyebrow', __( 'FAQs', 'amber' ) );
$erp_faq_heading = amber_sub( 'erp_faq_heading', __( 'Frequently Asked Questions', 'amber' ) );

// "Book a call" card under the heading (left column of the split layout).
$erp_faq_card_title = amber_sub( 'erp_faq_card_title', __( 'Book a 15 min call', 'amber' ) );
$erp_faq_card_text  = amber_sub( 'erp_faq_card_text', $erp_t( __( 'Still have questions about Amber ERP Software? Book a free 15-minute call with our product specialists before you decide.', 'amber' ) ) );
$erp_faq_card_btn   = amber_sub( 'erp_faq_card_btn_label', __( 'Book a Free Call', 'amber' ) );
$erp_faq_card_url   = amber_sub( 'erp_faq_card_btn_url', '' );
// Avatar: ACF field → bundled theme file (assets/images/erp/avatar.*) →
// empty (branded icon medallion).
$erp_faq_avatar = amber_sub( 'erp_faq_avatar', '' );
if ( empty( $erp_faq_avatar ) ) {
	foreach ( array( 'webp', 'png', 'jpg', 'jpeg' ) as $ext ) {
		if ( file_exists( AMBER_DIR . 'assets/images/erp/avatar.' . $ext ) ) {
			$erp_faq_avatar = AMBER_URI . 'assets/images/erp/avatar.' . $ext;
			break;
		}
	}
}

$erp_faqs = array();
foreach ( amber_erp_faq_defaults() as $i => $d ) {
	// A page that lists fewer questions than the defaults blanks the tail
	// slots deliberately. amber_sub() treats blank as "unset" and would hand
	// back demo copy, so check the stored value first and drop the slot.
	if ( amber_erp_slot_is_cleared( "erp_faq{$i}_q" ) ) {
		continue;
	}

	$erp_faqs[ $i ] = array(
		'q' => amber_sub( "erp_faq{$i}_q", $erp_t( $d['q'] ) ),
		'a' => amber_sub( "erp_faq{$i}_a", $erp_t( $d['a'] ) ),
	);
}

$erp_faq_uid    = amber_uid( 'erp-faq' );
$erp_faq_schema = array();
?>

<!-- FAQ — ERP questions (split layout: intro + call card | hairline accordion) -->
<section class="section section--erp-faq is-bg-surface" id="erp-faq">
	<div class="container">
		<div class="erp-faq__grid">

			<!-- Left: dot eyebrow, big heading, "book a call" card -->
			<div class="erp-faq__intro">
				<span class="erp-faq__eyebrow"><?php echo esc_html( $erp_faq_eyebrow ); ?></span>
				<h2 class="erp-faq__heading"><?php echo esc_html( $erp_faq_heading ); ?></h2>

				<div class="erp-faq__card">
					<span class="erp-faq__avatar<?php echo empty( $erp_faq_avatar ) ? ' is-empty' : ''; ?>">
						<?php if ( ! empty( $erp_faq_avatar ) ) : ?>
							<?php amber_image( $erp_faq_avatar, 'thumbnail', array( 'class' => 'erp-faq__avatar-img', 'alt' => esc_attr( $erp_faq_card_title ) ) ); ?>
						<?php else : ?>
							<?php echo amber_icon_svg( 'support' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endif; ?>
					</span>
					<h3 class="erp-faq__card-title"><?php echo esc_html( $erp_faq_card_title ); ?></h3>
					<p class="erp-faq__card-text"><?php echo esc_html( $erp_faq_card_text ); ?></p>
					<a class="btn btn-primary banner-primary-btn" href="<?php echo esc_url( $erp_faq_card_url ?: '#' ); ?>"<?php echo $erp_faq_card_url ? '' : ' data-open-modal="demo-modal"'; ?>><?php echo esc_html( $erp_faq_card_btn ); ?></a>
				</div>
			</div>

			<!-- Right: hairline accordion with +/× icons -->
			<div class="accordion erp-faq__list" data-accordion>
				<?php
				foreach ( $erp_faqs as $i => $faq ) :
					$btn_id   = $erp_faq_uid . '-btn-' . $i;
					$panel_id = $erp_faq_uid . '-panel-' . $i;

					$erp_faq_schema[] = array(
						'@type'          => 'Question',
						'name'           => wp_strip_all_tags( $faq['q'] ),
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $faq['a'] ),
						),
					);
					?>
					<div class="accordion__item" data-animate="fade-up">
						<h3 class="accordion__heading">
							<button class="accordion__trigger" id="<?php echo esc_attr( $btn_id ); ?>" type="button" aria-expanded="<?php echo 1 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
								<span class="accordion__question"><?php echo esc_html( $faq['q'] ); ?></span>
								<span class="accordion__icon" aria-hidden="true"><?php echo amber_icon_svg( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							</button>
						</h3>
						<div class="accordion__panel" id="<?php echo esc_attr( $panel_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $btn_id ); ?>" <?php echo 1 === $i ? '' : 'hidden'; ?>>
							<div class="accordion__answer"><?php echo wp_kses_post( wpautop( $faq['a'] ) ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>

	<?php if ( ! empty( $erp_faq_schema ) ) : ?>
		<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $erp_faq_schema ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
	<?php endif; ?>
</section>

<?php
/* ---------------------------------------------------------------------------
 * CTA — "Try free / Talk to an expert" split, per the reference: two equal
 * solid brand-blue cards side by side (stacked on mobile), each with a
 * heading, supporting text, a white pill button, and a full-width screenshot
 * flush against the card's bottom edge. Editable via the "Amber — ERP CTA"
 * group; each card's image falls back to the bundled dashboard screenshot.
 * Sits last on the page (after the FAQ), per the reference layout.
 * ------------------------------------------------------------------------ */
$erp_cta1_heading = amber_sub( 'erp_cta1_heading', sprintf( '%s %s', __( 'Try', 'amber' ), $erp_product['name'] ) . "\n" . __( 'Free for 14 Days', 'amber' ) );
$erp_cta1_text     = amber_sub( 'erp_cta1_text', $erp_t( __( 'Try Amber ERP Software free for 14 days. Set up your workspace, test workflows, and see how easy it is to launch or optimize.', 'amber' ) ) );
$erp_cta1_btn      = amber_sub( 'erp_cta1_btn_label', __( 'Start Free Trial', 'amber' ) );
$erp_cta1_url      = amber_sub( 'erp_cta1_btn_url', home_url( '/contact-us/' ) );
$erp_cta1_image    = amber_sub( 'erp_cta1_image', AMBER_URI . 'assets/images/erp/dashboard.webp' );

$erp_cta2_heading = amber_sub( 'erp_cta2_heading', sprintf( '%s %s', __( 'See How', 'amber' ), $erp_product['name'] ) . "\n" . __( 'Fits Your Workflow', 'amber' ) );
$erp_cta2_text     = amber_sub( 'erp_cta2_text', $erp_t( __( 'Speak with an expert to explore how Amber ERP Software fits your business — from daily operations to reporting.', 'amber' ) ) );
$erp_cta2_btn      = amber_sub( 'erp_cta2_btn_label', __( 'Talk to an Expert', 'amber' ) );
$erp_cta2_url      = amber_sub( 'erp_cta2_btn_url', home_url( '/contact-us/' ) );
$erp_cta2_image    = amber_sub( 'erp_cta2_image', AMBER_URI . 'assets/images/erp/dashboard.webp' );

/**
 * Split a CTA heading into two lines at a `\n` marker (matches the
 * reference's hard `<br>` — e.g. "Try CleanCloud Free<br>for 14 Days" — so
 * the break always lands in the same place regardless of container width,
 * rather than wherever the browser's own soft-wrap happens to land). Falls
 * back to a mid-point word-boundary split so a custom heading typed without
 * a line break still gets a reasonable two-line result instead of one
 * overlong line.
 *
 * @param string $heading Heading text, optionally containing a literal \n.
 * @return string[] Exactly two lines (the second may be '').
 */
$erp_cta_split_heading = static function ( $heading ) {
	$lines = explode( "\n", $heading, 2 );
	if ( count( $lines ) === 2 ) {
		return $lines;
	}
	$mid   = (int) floor( mb_strlen( $heading ) / 2 );
	$break = mb_strpos( $heading, ' ', $mid );
	if ( false === $break ) {
		return array( $heading, '' );
	}
	return array( mb_substr( $heading, 0, $break ), mb_substr( $heading, $break + 1 ) );
};

$erp_cta1_lines = $erp_cta_split_heading( $erp_cta1_heading );
$erp_cta2_lines = $erp_cta_split_heading( $erp_cta2_heading );
?>

<!-- CTA — try free / talk to an expert split -->
<section class="section section--erp-cta is-bg-white" id="erp-cta">
	<div class="container erp-cta__row">

		<div class="erp-cta__card">
			<div class="erp-cta__card-body">
				<h3 class="erp-cta__card-heading"><?php echo esc_html( $erp_cta1_lines[0] ); ?><?php if ( ! empty( $erp_cta1_lines[1] ) ) : ?><br><?php echo esc_html( $erp_cta1_lines[1] ); ?><?php endif; ?></h3>
				<?php if ( $erp_cta1_text ) : ?>
					<p class="erp-cta__card-text"><?php echo esc_html( $erp_cta1_text ); ?></p>
				<?php endif; ?>
				<div class="erp-cta__card-action">
					<a href="<?php echo esc_url( $erp_cta1_url ); ?>" class="erp-cta__card-btn"><?php echo esc_html( $erp_cta1_btn ); ?></a>
				</div>
			</div>
			<?php if ( $erp_cta1_image ) : ?>
				<div class="erp-cta__card-media">
					<?php amber_image( $erp_cta1_image, 'large', array( 'alt' => implode( ' ', $erp_cta1_lines ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="erp-cta__card">
			<div class="erp-cta__card-body">
				<h3 class="erp-cta__card-heading"><?php echo esc_html( $erp_cta2_lines[0] ); ?><?php if ( ! empty( $erp_cta2_lines[1] ) ) : ?><br><?php echo esc_html( $erp_cta2_lines[1] ); ?><?php endif; ?></h3>
				<?php if ( $erp_cta2_text ) : ?>
					<p class="erp-cta__card-text"><?php echo esc_html( $erp_cta2_text ); ?></p>
				<?php endif; ?>
				<div class="erp-cta__card-action">
					<a href="<?php echo esc_url( $erp_cta2_url ); ?>" class="erp-cta__card-btn"><?php echo esc_html( $erp_cta2_btn ); ?></a>
				</div>
			</div>
			<?php if ( $erp_cta2_image ) : ?>
				<div class="erp-cta__card-media">
					<?php amber_image( $erp_cta2_image, 'large', array( 'alt' => implode( ' ', $erp_cta2_lines ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
