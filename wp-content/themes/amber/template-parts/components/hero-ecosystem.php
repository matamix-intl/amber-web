<?php
/**
 * Component: Hero ecosystem stage — central dashboard + connected product modules.
 *
 * The hero's right-side illustration: a large glass analytics dashboard in
 * subtle 3D perspective (window bar, icon rail, KPI tiles with sparklines, an
 * animated revenue chart, a revenue-by-product donut and weekly-orders bars),
 * with the four Amber products docked at the corners as connected modules.
 * Curved SVG lines with flowing dashes and travelling pulse dots tie each
 * module into the dashboard, over a backdrop of dotted grid, orbital rings,
 * gradient blobs and floating particles. Pure CSS/SVG — no JS, no images.
 *
 * Content is design-controlled brand storytelling (like the header mega-menu
 * product list), not per-page copy.
 *
 * @package Amber
 */

// The four product modules docked around the dashboard. `pos` maps to the
// stage corner + its SVG connection path; chips summarise each product.
$modules = array(
	array(
		'pos'   => 'tl',
		'theme' => 'blue',
		'name'  => 'Amber ERP Software',
		'icon'  => 'chart',
		'chips' => array( __( 'Finance', 'amber' ), __( 'Inventory', 'amber' ), __( 'HR', 'amber' ) ),
	),
	array(
		'pos'   => 'tr',
		'theme' => 'pink',
		'name'  => 'Amber POS Software',
		'icon'  => 'pos',
		'chips' => array( __( 'Billing', 'amber' ), __( 'Multi-store', 'amber' ), __( 'Reports', 'amber' ) ),
	),
	array(
		'pos'   => 'bl',
		'theme' => 'green',
		'name'  => 'Amber Restaurant Software',
		'icon'  => 'utensils',
		'chips' => array( __( 'QR Orders', 'amber' ), __( 'Kitchen', 'amber' ), __( 'Tables', 'amber' ) ),
	),
	array(
		'pos'   => 'br',
		'theme' => 'orange',
		'name'  => 'Amber Laundry Software',
		'icon'  => 'washer',
		'chips' => array( __( 'Pickup', 'amber' ), __( 'Tracking', 'amber' ), __( 'CRM', 'amber' ) ),
	),
);

// KPI tiles across the dashboard top.
$kpis = array(
	array( 'label' => __( 'Total Revenue', 'amber' ), 'value' => '$2.4M', 'delta' => '+18.2%', 'spark' => '0,18 10,14 20,16 30,9 40,11 50,5 60,7' ),
	array( 'label' => __( 'Active Orders', 'amber' ), 'value' => '1,284', 'delta' => '+12.4%', 'spark' => '0,16 10,17 20,12 30,14 40,8 50,10 60,4' ),
	array( 'label' => __( 'Customers', 'amber' ), 'value' => '12.4K', 'delta' => '+9.1%', 'spark' => '0,19 10,15 20,17 30,12 40,13 50,8 60,6' ),
);

// Revenue-by-product donut legend (colours mirror the modules).
$split = array(
	array( 'name' => 'Amber ERP Software', 'share' => '42%', 'theme' => 'blue' ),
	array( 'name' => 'Amber POS Software', 'share' => '22%', 'theme' => 'pink' ),
	array( 'name' => 'Amber Restaurant Software', 'share' => '20%', 'theme' => 'green' ),
	array( 'name' => 'Amber Laundry Software', 'share' => '16%', 'theme' => 'orange' ),
);

$bars = array( 42, 65, 50, 80, 58, 72, 95 );
$days = array( __( 'M', 'amber' ), __( 'T', 'amber' ), __( 'W', 'amber' ), __( 'T', 'amber' ), __( 'F', 'amber' ), __( 'S', 'amber' ), __( 'S', 'amber' ) );
?>
<div class="hero-stage" aria-hidden="true">
	<!-- Backdrop: dotted grid, orbital rings, gradient blobs, particles -->
	<span class="hero-stage__grid"></span>
	<span class="hero-stage__blob hero-stage__blob--1"></span>
	<span class="hero-stage__blob hero-stage__blob--2"></span>
	<span class="hero-stage__ring hero-stage__ring--1"></span>
	<span class="hero-stage__ring hero-stage__ring--2"></span>
	<?php for ( $p = 1; $p <= 8; $p++ ) : ?>
		<span class="hero-stage__particle hero-stage__particle--<?php echo (int) $p; ?>"></span>
	<?php endfor; ?>

	<!-- Glowing connections: module → dashboard, with flowing dashes + pulses.
	     Coordinates assume the dashboard face spans ~x200–800 / y205–695 in this
	     1000×900 space: each line leaves a corner module through the side gutter
	     and docks on the glass edge at a pulsing port. -->
	<svg class="hero-stage__links" viewBox="0 0 1000 900" fill="none" focusable="false">
		<defs>
			<linearGradient id="hlk-tl" x1="160" y1="140" x2="200" y2="340" gradientUnits="userSpaceOnUse">
				<stop offset="0" stop-color="#2563eb"/><stop offset="1" stop-color="#38bdf8"/>
			</linearGradient>
			<linearGradient id="hlk-tr" x1="840" y1="140" x2="800" y2="340" gradientUnits="userSpaceOnUse">
				<stop offset="0" stop-color="#ec4899"/><stop offset="1" stop-color="#38bdf8"/>
			</linearGradient>
			<linearGradient id="hlk-bl" x1="160" y1="760" x2="200" y2="560" gradientUnits="userSpaceOnUse">
				<stop offset="0" stop-color="#22c55e"/><stop offset="1" stop-color="#38bdf8"/>
			</linearGradient>
			<linearGradient id="hlk-br" x1="840" y1="760" x2="800" y2="560" gradientUnits="userSpaceOnUse">
				<stop offset="0" stop-color="#f97316"/><stop offset="1" stop-color="#38bdf8"/>
			</linearGradient>
		</defs>

		<!-- Soft glow understroke + bright flowing dash per connection -->
		<path id="hlk-path-tl" d="M160,140 C160,225 168,295 200,340" stroke="url(#hlk-tl)"/>
		<path id="hlk-path-tr" d="M840,140 C840,225 832,295 800,340" stroke="url(#hlk-tr)"/>
		<path id="hlk-path-bl" d="M160,760 C160,675 168,605 200,560" stroke="url(#hlk-bl)"/>
		<path id="hlk-path-br" d="M840,760 C840,675 832,605 800,560" stroke="url(#hlk-br)"/>
		<g class="hero-links__glow">
			<use href="#hlk-path-tl"/><use href="#hlk-path-tr"/><use href="#hlk-path-bl"/><use href="#hlk-path-br"/>
		</g>
		<g class="hero-links__flow">
			<use href="#hlk-path-tl"/><use href="#hlk-path-tr"/><use href="#hlk-path-bl"/><use href="#hlk-path-br"/>
		</g>

		<!-- Docking ports on the dashboard edges -->
		<circle class="hero-links__port" cx="200" cy="340" r="5" fill="#2563eb"/>
		<circle class="hero-links__port" cx="800" cy="340" r="5" fill="#ec4899"/>
		<circle class="hero-links__port" cx="200" cy="560" r="5" fill="#22c55e"/>
		<circle class="hero-links__port" cx="800" cy="560" r="5" fill="#f97316"/>

		<!-- Data pulses travelling module → dashboard (negative begins stagger
		     the pulses while keeping every one mid-path from the first frame) -->
		<circle class="hero-links__pulse" r="4.5" fill="#2563eb"><animateMotion dur="3.2s" begin="-3.2s" repeatCount="indefinite"><mpath href="#hlk-path-tl"/></animateMotion></circle>
		<circle class="hero-links__pulse" r="4.5" fill="#ec4899"><animateMotion dur="3.2s" begin="-2.4s" repeatCount="indefinite"><mpath href="#hlk-path-tr"/></animateMotion></circle>
		<circle class="hero-links__pulse" r="4.5" fill="#22c55e"><animateMotion dur="3.2s" begin="-1.6s" repeatCount="indefinite"><mpath href="#hlk-path-bl"/></animateMotion></circle>
		<circle class="hero-links__pulse" r="4.5" fill="#f97316"><animateMotion dur="3.2s" begin="-0.8s" repeatCount="indefinite"><mpath href="#hlk-path-br"/></animateMotion></circle>
	</svg>

	<!-- ===== The hero element: glass analytics dashboard in perspective ===== -->
	<div class="hero-dashboard">
		<div class="hero-dashboard__glass">
			<!-- Window bar -->
			<div class="hero-dashboard__bar">
				<span class="hero-dashboard__dots"><i></i><i></i><i></i></span>
				<span class="hero-dashboard__url"><?php esc_html_e( 'suite.amber.io / overview', 'amber' ); ?></span>
				<span class="hero-dashboard__user"></span>
			</div>

			<div class="hero-dashboard__body">
				<!-- Icon rail -->
				<div class="hero-dashboard__rail">
					<span class="hero-dashboard__rail-logo">a</span>
					<?php foreach ( array( 'chart', 'cube', 'cart', 'layers', 'support' ) as $ri => $ricon ) : ?>
						<span class="hero-dashboard__rail-item<?php echo 0 === $ri ? ' is-on' : ''; ?>"><?php echo amber_icon_svg( $ricon ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php endforeach; ?>
				</div>

				<!-- Main pane -->
				<div class="hero-dashboard__main">
					<div class="hero-dashboard__head">
						<span class="hero-dashboard__title">
							<b><?php esc_html_e( 'Ecosystem Overview', 'amber' ); ?></b>
							<small><?php esc_html_e( 'All products · Last 30 days', 'amber' ); ?></small>
						</span>
						<span class="hero-dashboard__seg">
							<i><?php esc_html_e( 'Day', 'amber' ); ?></i><i><?php esc_html_e( 'Week', 'amber' ); ?></i><i class="is-on"><?php esc_html_e( 'Month', 'amber' ); ?></i>
						</span>
						<span class="hero-dashboard__live"><i></i><?php esc_html_e( 'Live', 'amber' ); ?></span>
					</div>

					<!-- KPI tiles -->
					<div class="hero-dashboard__kpis">
						<?php foreach ( $kpis as $kpi ) : ?>
							<div class="hero-dashboard__kpi">
								<span class="hero-dashboard__kpi-label"><?php echo esc_html( $kpi['label'] ); ?></span>
								<span class="hero-dashboard__kpi-row">
									<b><?php echo esc_html( $kpi['value'] ); ?></b>
									<em><?php echo esc_html( $kpi['delta'] ); ?></em>
								</span>
								<svg class="hero-dashboard__spark" viewBox="0 0 60 24" preserveAspectRatio="none" focusable="false"><polyline points="<?php echo esc_attr( $kpi['spark'] ); ?>"/></svg>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Revenue chart -->
					<div class="hero-dashboard__chart">
						<svg viewBox="0 0 340 120" preserveAspectRatio="none" focusable="false">
							<defs>
								<linearGradient id="hd-area" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0" stop-color="#2563eb" stop-opacity="0.28"/>
									<stop offset="1" stop-color="#2563eb" stop-opacity="0"/>
								</linearGradient>
							</defs>
							<g class="hero-dashboard__gridlines"><line x1="0" y1="30" x2="340" y2="30"/><line x1="0" y1="60" x2="340" y2="60"/><line x1="0" y1="90" x2="340" y2="90"/></g>
							<path class="hero-dashboard__area" fill="url(#hd-area)" d="M0,96 C28,88 44,64 72,66 C100,68 116,40 148,44 C176,47 192,72 222,60 C252,48 272,26 340,22 L340,120 L0,120 Z"/>
							<path class="hero-dashboard__line" d="M0,96 C28,88 44,64 72,66 C100,68 116,40 148,44 C176,47 192,72 222,60 C252,48 272,26 340,22"/>
						</svg>
						<span class="hero-dashboard__chart-dot"></span>
					</div>

					<!-- Bottom row: revenue split + weekly orders -->
					<div class="hero-dashboard__row">
						<div class="hero-dashboard__card">
							<span class="hero-dashboard__card-title"><?php esc_html_e( 'Revenue by product', 'amber' ); ?></span>
							<div class="hero-dashboard__split">
								<span class="hero-dashboard__donut"><b>$2.4M</b></span>
								<ul class="hero-dashboard__legend">
									<?php foreach ( $split as $seg ) : ?>
										<li class="is-<?php echo esc_attr( $seg['theme'] ); ?>"><i></i><?php echo esc_html( $seg['name'] ); ?><b><?php echo esc_html( $seg['share'] ); ?></b></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="hero-dashboard__card">
							<span class="hero-dashboard__card-title"><?php esc_html_e( 'Orders this week', 'amber' ); ?></span>
							<div class="hero-dashboard__bars">
								<?php foreach ( $bars as $bi => $h ) : ?>
									<span class="hero-dashboard__vbar<?php echo 6 === $bi ? ' is-peak' : ''; ?>">
										<i style="height:<?php echo (int) $h; ?>%"></i>
										<small><?php echo esc_html( $days[ $bi ] ); ?></small>
									</span>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Floating notification chips for depth / realism -->
		<span class="hero-toast hero-toast--pay">
			<i class="hero-toast__icon"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></i>
			<span><b><?php esc_html_e( 'Payment received', 'amber' ); ?></b><small><?php esc_html_e( 'Amber POS Software · $1,240', 'amber' ); ?></small></span>
		</span>
		<span class="hero-toast hero-toast--ai">
			<i class="hero-toast__icon"><?php echo amber_icon_svg( 'bolt' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></i>
			<span><b><?php esc_html_e( 'AI Insight', 'amber' ); ?></b><small><?php esc_html_e( 'Restock 3 stores this week', 'amber' ); ?></small></span>
		</span>
	</div>

	<!-- ===== Connected product modules ===== -->
	<div class="hero-modules">
		<?php foreach ( $modules as $module ) : ?>
			<div class="hero-module hero-module--<?php echo esc_attr( $module['theme'] ); ?> hero-module--<?php echo esc_attr( $module['pos'] ); ?>">
				<span class="hero-module__head">
					<span class="hero-module__icon"><?php echo amber_icon_svg( $module['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="hero-module__titles">
						<span class="hero-module__name"><?php echo esc_html( $module['name'] ); ?></span>
						<span class="hero-module__status"><i></i><?php esc_html_e( 'Connected', 'amber' ); ?></span>
					</span>
				</span>
				<span class="hero-module__chips">
					<?php foreach ( $module['chips'] as $chip ) : ?>
						<i><?php echo esc_html( $chip ); ?></i>
					<?php endforeach; ?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
