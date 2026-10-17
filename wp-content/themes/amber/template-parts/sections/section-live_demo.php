<?php
/**
 * Section: Live Product Experience — "See Amber in Action".
 *
 * An accessible tablist: product tabs on the left, a faux product dashboard on
 * the right. Switching tabs swaps the dashboard (see live-demo.js). Dashboards
 * are pure CSS so no image assets are required.
 *
 * @package Amber
 */

$tabs = amber_sub(
	'live_items',
	array(
		array(
			'name'   => 'Amber ERP Software',
			'accent' => '#0ea5e9',
			'kpis'   => array(
				array( 'label' => __( 'Revenue', 'amber' ), 'value' => '$248.5K' ),
				array( 'label' => __( 'Orders', 'amber' ), 'value' => '1,240' ),
				array( 'label' => __( 'Stock value', 'amber' ), 'value' => '$1.2M' ),
			),
		),
		array(
			'name'   => 'Amber POS Software',
			'accent' => '#f59e0b',
			'kpis'   => array(
				array( 'label' => __( 'Today’s sales', 'amber' ), 'value' => '$8,430' ),
				array( 'label' => __( 'Receipts', 'amber' ), 'value' => '312' ),
				array( 'label' => __( 'Avg. basket', 'amber' ), 'value' => '$27.0' ),
			),
		),
		array(
			'name'   => 'Amber Restaurant Software',
			'accent' => '#10b981',
			'kpis'   => array(
				array( 'label' => __( 'Live orders', 'amber' ), 'value' => '38' ),
				array( 'label' => __( 'Tables', 'amber' ), 'value' => '24/30' ),
				array( 'label' => __( 'Avg. prep', 'amber' ), 'value' => '12 min' ),
			),
		),
		array(
			'name'   => 'Amber Laundry Software',
			'accent' => '#6366f1',
			'kpis'   => array(
				array( 'label' => __( 'Pickups', 'amber' ), 'value' => '146' ),
				array( 'label' => __( 'In progress', 'amber' ), 'value' => '57' ),
				array( 'label' => __( 'On-time', 'amber' ), 'value' => '98.2%' ),
			),
		),
	)
);

$uid = amber_uid( 'live' );
?>
<section <?php echo amber_section_attrs( 'live', 'navy' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-live-demo>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'live_eyebrow', __( 'See Amber in Action', 'amber' ) ),
				'heading'    => amber_sub( 'live_heading', __( 'Smart Solutions. Real Results.', 'amber' ) ),
				'subheading' => amber_sub( 'live_subheading', __( 'Explore our products and see how they transform business operations.', 'amber' ) ),
				'align'      => 'center',
			)
		);
		?>

		<div class="live-demo">
			<div class="live-demo__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Products', 'amber' ); ?>" aria-orientation="vertical">
				<?php foreach ( (array) $tabs as $i => $tab ) : ?>
					<button
						class="live-demo__tab <?php echo 0 === $i ? 'is-active' : ''; ?>"
						id="<?php echo esc_attr( $uid . '-tab-' . $i ); ?>"
						role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $uid . '-panel-' . $i ); ?>"
						tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>"
						style="--accent: <?php echo esc_attr( $tab['accent'] ?? '#0ea5e9' ); ?>"
					>
						<span class="live-demo__tab-dot" aria-hidden="true"></span>
						<?php echo esc_html( $tab['name'] ?? '' ); ?>
						<span class="live-demo__tab-arrow" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="live-demo__stage">
				<?php foreach ( (array) $tabs as $i => $tab ) : ?>
					<div
						class="live-demo__panel <?php echo 0 === $i ? 'is-active' : ''; ?>"
						id="<?php echo esc_attr( $uid . '-panel-' . $i ); ?>"
						role="tabpanel"
						aria-labelledby="<?php echo esc_attr( $uid . '-tab-' . $i ); ?>"
						style="--accent: <?php echo esc_attr( $tab['accent'] ?? '#0ea5e9' ); ?>"
						<?php echo 0 === $i ? '' : 'hidden'; ?>
					>
						<div class="dash" aria-hidden="true">
							<div class="dash__bar">
								<span class="dash__logo"><?php echo esc_html( $tab['name'] ?? '' ); ?></span>
								<span class="dash__dots"><i></i><i></i><i></i></span>
							</div>
							<div class="dash__kpis">
								<?php
								// KPIs may be an array of {label,value} (defaults) or a
								// "Label | Value" per-line string (from ACF). Normalise both.
								$kpis = $tab['kpis'] ?? array();
								if ( is_string( $kpis ) ) {
									$parsed = array();
									foreach ( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $kpis ) ) ) as $line ) {
										$bits     = array_map( 'trim', explode( '|', $line, 2 ) );
										$parsed[] = array( 'label' => $bits[0], 'value' => $bits[1] ?? '' );
									}
									$kpis = $parsed;
								}
								foreach ( (array) $kpis as $kpi ) :
									?>
									<div class="dash__kpi">
										<span class="dash__kpi-label"><?php echo esc_html( $kpi['label'] ); ?></span>
										<span class="dash__kpi-value"><?php echo esc_html( $kpi['value'] ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="dash__chart">
								<i style="height:46%"></i><i style="height:68%"></i><i style="height:40%"></i>
								<i style="height:82%"></i><i style="height:58%"></i><i style="height:74%"></i>
								<i style="height:52%"></i><i style="height:90%"></i>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
