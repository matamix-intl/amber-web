<?php
/**
 * Component: Hero faux dashboard (pure CSS/SVG).
 *
 * Renders a glassmorphism product dashboard mockup for the hero carousel. The
 * `type` arg (erp|pos|restaurant|crm) picks the layout; colours come from the
 * slide theme via CSS custom properties (--dash-accent etc.), so one markup set
 * tints to each product. No image assets required.
 *
 * @package Amber
 * @var array $args { type: string }
 */

$type = isset( $args['type'] ) ? $args['type'] : 'erp';
?>
<div class="dash" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%s dashboard preview', 'amber' ), ucfirst( $type ) ) ); ?>">
	<div class="dash__glass">
		<?php // Window chrome. ?>
		<div class="dash__bar">
			<span class="dash__dots"><i></i><i></i><i></i></span>
			<span class="dash__pill"></span>
		</div>

		<div class="dash__body">
			<?php // Sidebar rail (present on all types). ?>
			<div class="dash__rail" aria-hidden="true">
				<span class="dash__rail-logo"></span>
				<span class="dash__rail-item is-on"></span>
				<span class="dash__rail-item"></span>
				<span class="dash__rail-item"></span>
				<span class="dash__rail-item"></span>
			</div>

			<div class="dash__main">
				<?php if ( 'pos' === $type ) : ?>
					<?php // POS: a receipt/checkout list + total. ?>
					<div class="dash__head"><span class="dash__title-line"></span><span class="dash__chip"></span></div>
					<ul class="dash__lines">
						<?php for ( $i = 0; $i < 4; $i++ ) : ?>
							<li><span class="dash__line-a"></span><span class="dash__line-b"></span></li>
						<?php endfor; ?>
					</ul>
					<div class="dash__total"><span></span><b></b></div>

				<?php elseif ( 'restaurant' === $type ) : ?>
					<?php // Restaurant: order tickets grid + a live status strip. ?>
					<div class="dash__head"><span class="dash__title-line"></span><span class="dash__chip"></span></div>
					<div class="dash__tiles">
						<?php for ( $i = 0; $i < 4; $i++ ) : ?>
							<span class="dash__tile"></span>
						<?php endfor; ?>
					</div>
					<div class="dash__strip"><i></i><i></i><i></i><i></i><i></i></div>

				<?php elseif ( 'crm' === $type ) : ?>
					<?php // CRM: a donut/analytics header + contact rows. ?>
					<div class="dash__head"><span class="dash__title-line"></span><span class="dash__chip"></span></div>
					<div class="dash__split">
						<span class="dash__donut" aria-hidden="true"></span>
						<span class="dash__spark"></span>
					</div>
					<ul class="dash__contacts">
						<?php for ( $i = 0; $i < 3; $i++ ) : ?>
							<li><span class="dash__avatar"></span><span class="dash__line-a"></span></li>
						<?php endfor; ?>
					</ul>

				<?php else : ?>
					<?php // ERP (default): KPI header row + a bar chart. ?>
					<div class="dash__kpis">
						<span class="dash__kpi"></span><span class="dash__kpi"></span><span class="dash__kpi"></span>
					</div>
					<div class="dash__chart" aria-hidden="true">
						<span class="dash__bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php // Glass light reflection sweep. ?>
		<span class="dash__reflection" aria-hidden="true"></span>
	</div>
</div>
