<?php
/**
 * Section: ERP — violet showcase band (two rows).
 *
 * A full-width violet (#864FFE) section presenting Amber ERP Software. Row 1: layered
 * faux dashboards on the left (an Odoo-style sales dashboard behind a floating
 * "Business Overview" finance card) with badge / heading / copy / a 2-column
 * check list on the right. Row 2 mirrors it: badge / heading / copy / check
 * list / white pill CTA and a 3-up stats strip on the left, three overlapping
 * mini analytics cards on the right. All visuals are pure CSS/SVG — no images.
 *
 * Copy is editable via amber_sub() fields (demo defaults below); the visuals
 * and check lists are design-controlled brand content, like the other
 * showcase sections.
 *
 * @package Amber
 */

$badge1 = amber_sub( 'erp_badge', __( 'Future-Ready ERP Solutions', 'amber' ) );
$title1 = amber_sub( 'erp_heading', __( 'Scale Your Business with Smart Amber ERP Software Solutions', 'amber' ) );
$text1a = amber_sub( 'erp_text', __( 'As a leading software company, we help businesses streamline operations, automate workflows, and improve operational efficiency through fully integrated ERP solutions designed for modern business growth.', 'amber' ) );
$text1b = amber_sub( 'erp_text2', __( 'From finance and inventory to CRM, HR, and business operations, Amber ERP Software connects every department into one intelligent platform—helping businesses work smarter, faster, and with better visibility.', 'amber' ) );

$badge2 = amber_sub( 'erp_badge2', __( 'Reliable Systems', 'amber' ) );
$title2 = amber_sub( 'erp_heading2', __( 'Powerful Amber ERP Software Solutions for Growing Businesses', 'amber' ) );
$text2a = amber_sub( 'erp2_text', __( 'Streamline operations and support long-term business growth with scalable and cloud-based Amber ERP Software solutions designed for modern businesses.', 'amber' ) );
$text2b = amber_sub( 'erp2_text2', __( 'Our ERP systems help businesses improve efficiency, automate workflows, and manage operations through one connected platform built for flexibility, performance, and reliability.', 'amber' ) );
$button = amber_sub( 'erp_btn', array( 'title' => __( 'Start Your Journey', 'amber' ), 'url' => '#contact', 'target' => '' ) );

$list1 = array(
	__( 'Global Scalability', 'amber' ),
	__( 'Real-Time Business Insights', 'amber' ),
	__( 'Smart Workflow Automation', 'amber' ),
	__( 'Better Decision-Making', 'amber' ),
	__( 'Seamless System Integration', 'amber' ),
	__( 'Data-Driven Business Growth', 'amber' ),
);
$list2 = array(
	__( '24/7 Support', 'amber' ),
	__( 'Cloud-Based ERP Integration', 'amber' ),
	__( 'Secure & Reliable Data Management', 'amber' ),
	__( 'Scalable Business Operations', 'amber' ),
	__( 'Smarter Workflow Automation', 'amber' ),
	__( 'Improved Operational Efficiency', 'amber' ),
);
$stats = array(
	array( 'icon' => 'users', 'value' => '320+', 'label' => __( 'Active Clients', 'amber' ) ),
	array( 'icon' => 'briefcase', 'value' => '48', 'label' => __( 'Projects', 'amber' ) ),
	array( 'icon' => 'check', 'value' => '1240', 'label' => __( 'Tasks Completed', 'amber' ) ),
);
?>
<section class="section section--erp erp-sec" id="erp">
	<div class="container">

		<!-- ===== Row 1 · dashboards left / intro right ===== -->
		<div class="erp-sec__row erp-sec__row--intro">
			<div class="erp-sec__visual" aria-hidden="true">
				<!-- Odoo-style sales dashboard -->
				<div class="erp-shot erp-shot--dash">
					<aside class="erp-shot__side">
						<span class="erp-shot__logo">amber</span>
						<ul class="erp-shot__menu">
							<li class="is-on"><?php esc_html_e( 'Dashboard', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Sales', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Purchase', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Inventory', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Accounting', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Reports', 'amber' ); ?></li>
							<li><?php esc_html_e( 'Settings', 'amber' ); ?></li>
						</ul>
					</aside>
					<div class="erp-shot__main">
						<div class="erp-shot__sale">
							<span><?php esc_html_e( 'Total Sales', 'amber' ); ?></span>
							<span class="erp-shot__sale-row"><b>$24,780</b><em>+18%</em></span>
						</div>
						<div class="erp-shot__panels">
							<div class="erp-shot__chart">
								<div class="erp-shot__panel-head"><b><?php esc_html_e( 'Sales Overview', 'amber' ); ?></b><i><?php esc_html_e( 'This Month', 'amber' ); ?> ▾</i></div>
								<svg viewBox="0 0 260 92" preserveAspectRatio="none" focusable="false">
									<g class="erp-shot__grid"><line x1="0" y1="24" x2="260" y2="24"/><line x1="0" y1="48" x2="260" y2="48"/><line x1="0" y1="72" x2="260" y2="72"/></g>
									<path class="erp-shot__line erp-shot__line--violet" d="M0,74 C24,66 40,50 62,54 C86,58 100,34 124,38 C148,42 162,60 186,50 C210,40 228,22 260,18"/>
									<path class="erp-shot__line erp-shot__line--green" d="M0,84 C26,80 44,68 66,70 C90,72 106,56 130,58 C154,60 170,44 194,48 C218,52 236,38 260,36"/>
								</svg>
								<div class="erp-shot__months"><i>Jan</i><i>Feb</i><i>Mar</i><i>Apr</i><i>May</i><i>Jun</i></div>
							</div>
							<div class="erp-shot__top">
								<div class="erp-shot__panel-head"><b><?php esc_html_e( 'Top Products', 'amber' ); ?></b></div>
								<ul class="erp-shot__products">
									<li><span><?php esc_html_e( 'Product A', 'amber' ); ?></span><i style="--w:92%"></i><b>$5,400</b></li>
									<li><span><?php esc_html_e( 'Product B', 'amber' ); ?></span><i style="--w:64%" class="is-green"></i><b>$3,200</b></li>
									<li><span><?php esc_html_e( 'Product C', 'amber' ); ?></span><i style="--w:52%"></i><b>$2,800</b></li>
									<li><span><?php esc_html_e( 'Product D', 'amber' ); ?></span><i style="--w:38%" class="is-green"></i><b>$1,900</b></li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<!-- Floating "Business Overview" finance card -->
				<div class="erp-shot erp-shot--fin">
					<div class="erp-fin__head"><b><?php esc_html_e( 'Business Overview', 'amber' ); ?></b><i><?php esc_html_e( 'USD', 'amber' ); ?> ▾</i></div>
					<span class="erp-fin__label"><?php esc_html_e( 'Total Balance', 'amber' ); ?></span>
					<b class="erp-fin__balance">$12,840.50</b>
					<div class="erp-fin__tabs"><i class="is-on"><?php esc_html_e( 'Revenue', 'amber' ); ?></i><i><?php esc_html_e( 'Expenses', 'amber' ); ?></i></div>
					<div class="erp-fin__person">
						<span class="erp-fin__avatar"></span>
						<span class="erp-fin__who"><b><?php esc_html_e( 'Daniel Cooper', 'amber' ); ?></b><small><?php esc_html_e( 'Finance Manager', 'amber' ); ?></small></span>
						<span class="erp-fin__donut"><b>78%</b><small><?php esc_html_e( 'Processed', 'amber' ); ?></small></span>
					</div>
					<span class="erp-fin__label"><?php esc_html_e( 'Tax', 'amber' ); ?></span>
					<div class="erp-fin__tax"><i>5%</i><i class="is-on">10%</i><i>15%</i><i>20%</i></div>
					<span class="erp-fin__pay"><?php esc_html_e( 'Process Payment', 'amber' ); ?><?php echo amber_icon_svg( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</div>
			</div>

			<div class="erp-sec__content">
				<span class="erp-sec__badge"><?php echo esc_html( $badge1 ); ?></span>
				<h2 class="erp-sec__title"><?php echo esc_html( $title1 ); ?></h2>
				<p class="erp-sec__text"><?php echo esc_html( $text1a ); ?></p>
				<p class="erp-sec__text"><?php echo esc_html( $text1b ); ?></p>
				<ul class="erp-sec__list">
					<?php foreach ( $list1 as $item ) : ?>
						<li><span class="erp-sec__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<!-- ===== Row 2 · copy + stats left / stacked analytics right ===== -->
		<div class="erp-sec__row erp-sec__row--power">
			<div class="erp-sec__content">
				<span class="erp-sec__badge"><?php echo esc_html( $badge2 ); ?></span>
				<h2 class="erp-sec__title"><?php echo esc_html( $title2 ); ?></h2>
				<p class="erp-sec__text"><?php echo esc_html( $text2a ); ?></p>
				<p class="erp-sec__text"><?php echo esc_html( $text2b ); ?></p>
				<ul class="erp-sec__list">
					<?php foreach ( $list2 as $item ) : ?>
						<li><span class="erp-sec__check" aria-hidden="true"><?php echo amber_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>

				<?php if ( ! empty( $button['url'] ) ) : ?>
					<a class="btn btn--lg erp-sec__btn" href="<?php echo esc_url( $button['url'] ); ?>"<?php echo ( ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
						<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : __( 'Start Your Journey', 'amber' ) ); ?>
					</a>
				<?php endif; ?>

				<ul class="erp-sec__stats">
					<?php foreach ( $stats as $stat ) : ?>
						<li class="erp-sec__stat">
							<span class="erp-sec__stat-icon" aria-hidden="true"><?php echo amber_icon_svg( $stat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="erp-sec__stat-value"><?php echo esc_html( $stat['value'] ); ?></span>
							<span class="erp-sec__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="erp-sec__stack" aria-hidden="true">
				<div class="erp-mini erp-mini--rev">
					<div class="erp-mini__head"><b><?php esc_html_e( 'Revenue', 'amber' ); ?></b><em>+12%</em></div>
					<b class="erp-mini__value">$84.2K</b>
					<svg viewBox="0 0 200 56" preserveAspectRatio="none" focusable="false">
						<path class="erp-mini__area" d="M0,44 C20,40 34,26 54,30 C74,34 88,16 110,20 C132,24 148,38 168,28 C182,21 192,12 200,10 L200,56 L0,56 Z"/>
						<path class="erp-mini__line" d="M0,44 C20,40 34,26 54,30 C74,34 88,16 110,20 C132,24 148,38 168,28 C182,21 192,12 200,10"/>
					</svg>
				</div>
				<div class="erp-mini erp-mini--orders">
					<div class="erp-mini__head"><b><?php esc_html_e( 'Orders by Channel', 'amber' ); ?></b></div>
					<div class="erp-mini__split">
						<span class="erp-mini__donut"><b>2.4K</b></span>
						<ul class="erp-mini__legend">
							<li class="is-violet"><?php esc_html_e( 'Online', 'amber' ); ?><b>54%</b></li>
							<li class="is-green"><?php esc_html_e( 'Retail', 'amber' ); ?><b>31%</b></li>
							<li class="is-amber"><?php esc_html_e( 'Partners', 'amber' ); ?><b>15%</b></li>
						</ul>
					</div>
				</div>
				<div class="erp-mini erp-mini--team">
					<div class="erp-mini__head"><b><?php esc_html_e( 'Weekly Performance', 'amber' ); ?></b></div>
					<div class="erp-mini__bars">
						<i style="--h:45%"></i><i style="--h:70%"></i><i style="--h:52%"></i><i style="--h:86%" class="is-peak"></i><i style="--h:60%"></i><i style="--h:74%"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
