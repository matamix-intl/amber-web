<?php
/**
 * Section: Featured Case Studies — horizontal card rail.
 *
 * A light band (placed after the dark "Concept" section) that showcases
 * flagship projects as tall image cards in a scroll-snapping rail. The rail is
 * native horizontal scroll, so it works with no JS / on touch; case-studies.js
 * layers on synced pagination dots. Each card reveals a short summary on hover.
 *
 * Real entries come from the `case_study` CPT (wp-admin → Case Studies): title
 * = name, featured image = card, excerpt = hover summary, Tags = the pills. On
 * ACF Free / before any posts exist it falls back to a set of premium gradient
 * placeholder cards, so the section is never empty. Adapted from the Pixbit
 * "Featured Case Studies" section.
 *
 * @package Amber
 */

$heading_lead = amber_sub( 'cases_heading_lead', __( 'Featured', 'amber' ) );
$heading_word = amber_sub( 'cases_heading_word', __( 'Case Studies', 'amber' ) );
$intro        = amber_sub( 'cases_intro', __( 'Explore our case studies — real projects that show how our solutions have supported our clients’ success and growth.', 'amber' ) );

// Real case studies managed in wp-admin → Case Studies (the `case_study` CPT).
$case_ids = get_posts(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => 9,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);

$archive_link = get_post_type_archive_link( 'case_study' );
if ( ! $archive_link ) {
	$archive_link = '#';
}

// Demo fallback: premium gradient cards (icons keyed in CSS) so the rail looks
// complete on ACF Free / before any case studies are added.
$demo_cards = array(
	array( 'title' => __( 'Unified Property Management Solution', 'amber' ), 'tags' => array( __( 'App Development', 'amber' ), __( 'UI/UX', 'amber' ), __( 'CRM', 'amber' ) ), 'text' => __( 'A single platform that streamlines real-estate operations for greater efficiency and customer satisfaction.', 'amber' ), 'icon' => 'briefcase' ),
	array( 'title' => __( 'Holistic FinTech Solution', 'amber' ), 'tags' => array( __( 'App Development', 'amber' ), __( 'UI/UX', 'amber' ) ), 'text' => __( 'We upgraded financial services to deliver a future-ready, superior customer experience.', 'amber' ), 'icon' => 'chart' ),
	array( 'title' => __( 'Innovative EV Charging App', 'amber' ), 'tags' => array( __( 'App Development', 'amber' ) ), 'text' => __( 'Unlock real-time charging-station access and detailed insights from one intuitive app.', 'amber' ), 'icon' => 'bolt' ),
	array( 'title' => __( 'Smart Online Gifting Platform', 'amber' ), 'tags' => array( __( 'App Development', 'amber' ), __( 'UI/UX', 'amber' ), __( 'Web development', 'amber' ) ), 'text' => __( 'A modern, user-focused shopping journey that reinvents how people send gifts online.', 'amber' ), 'icon' => 'cart' ),
	array( 'title' => __( 'Smart Dorm Booking App', 'amber' ), 'tags' => array( __( 'App Development', 'amber' ), __( 'UI/UX', 'amber' ) ), 'text' => __( 'From reservation to management, the whole dorm-booking journey made simple and organised.', 'amber' ), 'icon' => 'layers' ),
	array( 'title' => __( 'Digital Makeover of a Cosmetics Clinic', 'amber' ), 'tags' => array( __( 'UI/UX', 'amber' ), __( 'Web development', 'amber' ) ), 'text' => __( 'Advanced digital innovations that boost customer interaction and streamline clinic services.', 'amber' ), 'icon' => 'health' ),
);

// Normalise CPT posts and the demo fallback into one card shape.
$cards = array();
if ( ! empty( $case_ids ) ) {
	foreach ( $case_ids as $cid ) {
		$terms = get_the_terms( $cid, 'case_study_tag' );
		$tags  = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
		$cards[] = array(
			'title' => get_the_title( $cid ),
			'url'   => get_permalink( $cid ),
			'tags'  => $tags,
			'text'  => get_the_excerpt( $cid ),
			'image' => get_post_thumbnail_id( $cid ),
			'icon'  => 'layers',
		);
	}
} else {
	foreach ( $demo_cards as $demo ) {
		$cards[] = array(
			'title' => $demo['title'],
			'url'   => $archive_link,
			'tags'  => $demo['tags'],
			'text'  => $demo['text'],
			'image' => 0,
			'icon'  => $demo['icon'],
		);
	}
}
?>
<section class="section section--casestudies is-bg-white is-space-large" data-cases>
	<div class="container">
		<div class="cases__head" data-animate="fade-up">
			<h2 class="cases__title">
				<span class="cases__title-plain"><?php echo esc_html( $heading_lead ); ?></span>
				<span class="cases__title-accent"><?php echo esc_html( $heading_word ); ?></span>
			</h2>

			<?php if ( $intro ) : ?>
				<p class="cases__intro"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php
	// A 3D coverflow carousel (case-studies.js). Before JS boots the `.cases`
	// wrapper is a plain horizontal scroll rail (the `no-js` fallback below), so
	// the cards are always usable; the module upgrades it to the perspective
	// carousel with autoplay, drag/swipe and looping.
	?>
	<div
		class="cases"
		data-animate="fade-up"
		data-animate-delay="120"
		data-cases-carousel
		data-cases-autoplay="3500"
	>
		<div class="cases__rail">
			<?php // Prev / next controls (shown only once the carousel initialises). ?>
			<button type="button" class="cases__nav cases__nav--prev" data-cases-prev aria-label="<?php esc_attr_e( 'Previous case study', 'amber' ); ?>">
				<?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hand-authored SVG. ?>
			</button>
			<button type="button" class="cases__nav cases__nav--next" data-cases-next aria-label="<?php esc_attr_e( 'Next case study', 'amber' ); ?>">
				<?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hand-authored SVG. ?>
			</button>

			<div class="cases__stage" data-cases-stage>
				<ul class="cases__track" data-cases-track>
				<?php foreach ( $cards as $card ) : ?>
					<li class="cases__slide" data-cases-slide>
						<a class="case-card<?php echo $card['image'] ? '' : ' case-card--placeholder'; ?>" href="<?php echo esc_url( $card['url'] ); ?>">
							<div class="case-card__media">
								<?php if ( $card['image'] ) : ?>
									<?php echo wp_get_attachment_image( (int) $card['image'], 'amber-portrait', false, array( 'class' => 'case-card__img', 'alt' => esc_attr( $card['title'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core escapes. ?>
								<?php else : ?>
									<span class="case-card__watermark" aria-hidden="true"><?php echo amber_icon_svg( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hand-authored SVG. ?></span>
								<?php endif; ?>
							</div>

							<div class="case-card__overlay">
								<h3 class="case-card__title"><?php echo esc_html( $card['title'] ); ?></h3>

								<?php if ( ! empty( $card['tags'] ) ) : ?>
									<ul class="case-card__tags">
										<?php foreach ( $card['tags'] as $tag ) : ?>
											<li class="case-card__tag"><?php echo esc_html( $tag ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>

								<?php if ( ! empty( $card['text'] ) ) : ?>
									<p class="case-card__desc"><?php echo esc_html( $card['text'] ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<?php // Pagination dots are built + synced by case-studies.js. ?>
		<div class="cases__dots" data-cases-dots role="group" aria-label="<?php esc_attr_e( 'Case studies pagination', 'amber' ); ?>"></div>
	</div>

	<div class="cases__cta">
		<a class="btn btn--outline" href="<?php echo esc_url( $archive_link ); ?>">
			<?php esc_html_e( 'Explore Case Studies', 'amber' ); ?>
			<span class="btn__icon" aria-hidden="true"><?php echo amber_icon_svg( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hand-authored SVG. ?></span>
		</a>
	</div>
</section>
