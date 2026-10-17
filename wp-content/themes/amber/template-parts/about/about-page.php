<?php
/**
 * About Us — page body (premium enterprise-SaaS layout, sky-blue brand).
 *
 * Composition: a full-bleed photographic banner hero (deep-navy scrim,
 * left-aligned display headline, lead line and a single pill CTA), an "About
 * our company" split (image left, copy right), a Mission & Vision band and
 * the ERP-style closing CTA panel.
 *
 * Everything reads through amber_sub() so it shows premium defaults out of the
 * box and live content once the "Amber — About Page" ACF fields are filled.
 * The whole page uses the Amber sky-blue brand — no orange.
 *
 * @package Amber
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------------------
 * 1) Hero — full-bleed banner: photo background + scrim, headline, lead, CTA
 * ------------------------------------------------------------------------ */
// Background photo: uploaded image, or the bundled banner photo as default.
$about_hero_img = amber_sub( 'about_hero_image', AMBER_URI . 'assets/images/about-hero.png' );
$about_hero_btn = amber_sub( 'about_hero_btn', array( 'title' => __( 'Get started', 'amber' ), 'url' => '#contact', 'target' => '' ) );

// Headline; the optional highlight word carries the brand gradient (off by
// default — the banner headline is plain white like the reference).
$about_heading   = amber_sub( 'about_heading', __( 'Driving Business Growth Through Technology', 'amber' ) );
$about_highlight = amber_sub( 'about_heading_highlight', '' );

$about_p1 = amber_sub(
	'about_lead',
	__( 'A UAE-based technology company delivering ERP, digital marketing, and custom digital solutions for modern businesses', 'amber' )
);
/* ---------------------------------------------------------------------------
 * 1b) About our company — reference: D Max Dental "Creating Healthy Smiles"
 * split. Left: a 2x2-ish photo collage (two stacked photos, one tall photo,
 * one solid stat card) rather than a single image; right: dash-flanked
 * eyebrow, bold heading, ONE short paragraph (was three long paragraphs —
 * trimmed to match the reference's brevity) and a 4-point checklist. Every
 * photo slot is independently amber_sub()-editable and falls back to the
 * bundled about-company.png / the theme placeholder, so the section renders
 * complete before real photography is uploaded.
 * ------------------------------------------------------------------------ */
$aboutco_badge   = amber_sub( 'about_co_badge', __( 'About Us', 'amber' ) );
$aboutco_heading = amber_sub( 'about_co_heading', __( "Empowering Business Growth\nThrough Smart Digital Solutions", 'amber' ) );
$aboutco_body    = amber_sub(
	'about_co_body',
	__( 'Amber Technologies delivers smart, connected business software for ERP, CRM, POS and restaurant management, helping modern enterprises streamline operations, strengthen their digital presence and grow with confidence.', 'amber' )
);

// Same hard-break-at-known-word-boundary technique used across the ERP/hero
// headings: a forced two-line split so the heading always reads exactly two
// lines regardless of container width, rather than however the browser's own
// wrap happens to land; still degrades to a sensible midpoint split if the
// heading is edited down to a single-line string.
$aboutco_heading_lines = explode( "\n", $aboutco_heading, 2 );
if ( count( $aboutco_heading_lines ) < 2 ) {
	$aboutco_mid       = (int) floor( mb_strlen( $aboutco_heading ) / 2 );
	$aboutco_break_at  = mb_strpos( $aboutco_heading, ' ', $aboutco_mid );
	if ( false === $aboutco_break_at ) {
		$aboutco_heading_lines = array( $aboutco_heading, '' );
	} else {
		$aboutco_heading_lines = array(
			mb_substr( $aboutco_heading, 0, $aboutco_break_at ),
			mb_substr( $aboutco_heading, $aboutco_break_at + 1 ),
		);
	}
}

$aboutco_img1 = amber_sub( 'about_co_image', AMBER_URI . 'assets/images/about-company.png' );
$aboutco_img2 = amber_sub( 'about_co_image_2', '' );
$aboutco_img3 = amber_sub( 'about_co_image_3', '' );

$aboutco_stat_num   = amber_sub( 'about_co_stat_num', '3' );
$aboutco_stat_label = amber_sub( 'about_co_stat_label', __( 'Years Experience', 'amber' ) );
$aboutco_stat_text  = amber_sub( 'about_co_stat_text', __( 'Building trusted business software since day one.', 'amber' ) );

$aboutco_points = array();
foreach (
	array(
		__( 'Experienced team who listen before they build', 'amber' ),
		__( 'Modern platform that keeps every module in sync', 'amber' ),
		__( 'Implementation plans built around your workflow', 'amber' ),
		__( 'Long-term support that goes well beyond go-live', 'amber' ),
	) as $aboutco_pt_i => $aboutco_pt_default
) {
	$aboutco_pt_n = $aboutco_pt_i + 1;
	$aboutco_pt_val = amber_sub( "about_co_point{$aboutco_pt_n}", $aboutco_pt_default );
	if ( '' !== trim( (string) $aboutco_pt_val ) ) {
		$aboutco_points[] = $aboutco_pt_val;
	}
}

/* ---------------------------------------------------------------------------
 * 1c) Mission & Vision — alternating badge-image / copy rows on a light band
 * ------------------------------------------------------------------------ */
$about_mv_rows = array(
	array(
		'title' => amber_sub( 'about_mv_m_title', __( 'Our Mission', 'amber' ) ),
		'text'  => amber_sub( 'about_mv_m_text', __( 'Our mission is to deliver smart, scalable, and result-driven software solutions that help businesses improve operations, strengthen digital presence, and achieve sustainable growth. Through our ERP, POS, CRM and restaurant management platforms, we aim to simplify business challenges with practical and future-ready technology.', 'amber' ) ),
		'img'   => amber_sub( 'about_mv_m_image', AMBER_URI . 'assets/images/about-mission.png' ),
		'flip'  => false,
	),
	array(
		'title' => amber_sub( 'about_mv_v_title', __( 'Our Vision', 'amber' ) ),
		'text'  => amber_sub( 'about_mv_v_text', __( 'Our vision is to become a trusted technology partner for businesses looking to modernise operations and accelerate growth. We strive to build a stronger digital future where businesses leverage innovation, automation, and strategic technology to unlock their full potential.', 'amber' ) ),
		'img'   => amber_sub( 'about_mv_v_image', AMBER_URI . 'assets/images/about-vision.png' ),
		'flip'  => true,
	),
);

/* ---------------------------------------------------------------------------
 * 2) Closing CTA — the same centered .cta-banner used on the homepage
 *    (section-cta.php): light-blue gradient wash, navy heading/shapes/button,
 *    no image (matches the pixel reference exactly). Own field set
 *    (about_cta_*) so it's independently editable from the homepage's cta_*
 *    fields.
 * ------------------------------------------------------------------------ */
$about_cta_heading = amber_sub( 'about_cta_heading', __( 'Contact Us for Pricing Plans', 'amber' ) );
$about_cta_text     = amber_sub(
	'about_cta_text',
	__( 'To provide you with a personalized quote that meets your specific needs and budget, please contact our team. We will work with you to understand your organization’s unique requirements and recommend the best pricing plan for you.', 'amber' )
);
$about_cta_btn = amber_sub( 'about_cta_btn', array( 'title' => __( 'Contact Us', 'amber' ), 'url' => home_url( '/contact-us/' ), 'target' => '' ) );
?>

<article <?php post_class( 'about-page about-saas' ); ?>>

	<!-- 1 · Hero — full-bleed photographic banner -->
	<section class="section section--about-hero about-banner">
		<div class="about-banner__media" aria-hidden="true">
			<?php amber_image( $about_hero_img, 'full', array( 'class' => 'about-banner__img', 'loading' => 'eager', 'alt' => '' ) ); ?>
		</div>
		<span class="about-banner__scrim" aria-hidden="true"></span>

		<div class="container about-banner__inner">
			<div class="about-banner__content">
				<h1 class="about-banner__title" data-animate="fade-up">
					<?php echo esc_html( $about_heading ); ?>
					<?php if ( $about_highlight ) : ?>
						<span class="about-banner__highlight"><?php echo esc_html( $about_highlight ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( $about_p1 ) : ?>
					<p class="about-banner__lead" data-animate="fade-up" data-animate-delay="80"><?php echo esc_html( $about_p1 ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $about_hero_btn['url'] ) ) : ?>
					<div class="about-banner__actions" data-animate="fade-up" data-animate-delay="120">
						<a class="btn btn--lg about-banner__cta" href="<?php echo esc_url( $about_hero_btn['url'] ); ?>"<?php echo ( ! empty( $about_hero_btn['target'] ) ? ' target="' . esc_attr( $about_hero_btn['target'] ) . '" rel="noopener noreferrer"' : '' ); ?>>
							<?php echo esc_html( ! empty( $about_hero_btn['title'] ) ? $about_hero_btn['title'] : __( 'Get started', 'amber' ) ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- 1b · About our company — photo collage left, eyebrow + heading + short copy + checklist right -->
	<section class="section section--about-co is-bg-white">
		<div class="container about-co__grid">
			<div class="about-co__collage" data-animate="fade-up">
				<!-- Top-left wide -->
				<div class="about-co__photo about-co__photo--a">
					<?php amber_image( $aboutco_img1, 'medium_large', array( 'class' => 'about-co__img', 'alt' => trim( str_replace( "\n", ' ', $aboutco_heading ) ) ) ); ?>
				</div>

				<!-- Top-right -->
				<div class="about-co__photo about-co__photo--b">
					<?php amber_image( $aboutco_img2 ? $aboutco_img2 : get_template_directory_uri() . '/assets/images/placeholder.svg', 'medium_large', array( 'class' => 'about-co__img', 'alt' => '' ) ); ?>
				</div>

				<!-- Bottom-right tall portrait -->
				<div class="about-co__photo about-co__photo--c">
					<?php amber_image( $aboutco_img3 ? $aboutco_img3 : get_template_directory_uri() . '/assets/images/placeholder.svg', 'medium_large', array( 'class' => 'about-co__img', 'alt' => '' ) ); ?>
				</div>

				<!-- Stats badge -->
				<div class="about-co__stat">
					<span class="about-co__stat-icon" aria-hidden="true">+</span>
					<span class="about-co__stat-num"><?php echo esc_html( $aboutco_stat_num ); ?></span>
					<span class="about-co__stat-label"><?php echo esc_html( $aboutco_stat_label ); ?></span>
					<?php if ( $aboutco_stat_text ) : ?>
						<p class="about-co__stat-text"><?php echo esc_html( $aboutco_stat_text ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Accent dots -->
				<span class="about-co__dot about-co__dot--a" aria-hidden="true"></span>
				<span class="about-co__dot about-co__dot--b" aria-hidden="true"></span>
			</div>

			<div class="about-co__copy" data-animate="fade-up" data-animate-delay="60">
				<?php if ( $aboutco_badge ) : ?>
					<span class="about-co__badge">
						<span class="about-co__badge-dash" aria-hidden="true"></span>
						<?php echo esc_html( $aboutco_badge ); ?>
						<span class="about-co__badge-dash" aria-hidden="true"></span>
					</span>
				<?php endif; ?>

				<h2 class="about-co__title"><?php echo esc_html( $aboutco_heading_lines[0] ); ?><?php if ( ! empty( $aboutco_heading_lines[1] ) ) : ?><br><?php echo esc_html( $aboutco_heading_lines[1] ); ?><?php endif; ?></h2>

				<?php if ( $aboutco_body ) : ?>
					<p class="about-co__body"><?php echo esc_html( $aboutco_body ); ?></p>
				<?php endif; ?>

				<?php if ( $aboutco_points ) : ?>
					<ul class="about-co__list">
						<?php foreach ( $aboutco_points as $aboutco_point ) : ?>
							<li class="about-co__item">
								<span class="about-co__check" aria-hidden="true"></span>
								<?php echo esc_html( $aboutco_point ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- 1c · Mission & Vision — alternating badge-image / copy rows -->
	<section class="section section--about-mv">
		<div class="container">
			<?php foreach ( $about_mv_rows as $mv ) : if ( '' === $mv['title'] ) { continue; } ?>
				<div class="about-mv__row<?php echo $mv['flip'] ? ' about-mv__row--flip' : ''; ?>">
					<div class="about-mv__media" data-animate="fade-up">
						<?php amber_image( $mv['img'], 'large', array( 'class' => 'about-mv__img', 'alt' => $mv['title'] ) ); ?>
					</div>
					<div class="about-mv__copy" data-animate="fade-up" data-animate-delay="60">
						<h2 class="about-mv__title"><?php echo esc_html( $mv['title'] ); ?></h2>
						<?php if ( $mv['text'] ) : ?><p class="about-mv__text"><?php echo esc_html( $mv['text'] ); ?></p><?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- 2 · Closing CTA — centered light-blue panel, same component as the homepage -->
	<section class="section section--cta cta-banner" id="about-cta">
		<svg class="cta-banner__deco cta-banner__deco--tri" viewBox="0 0 44 40" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round" aria-hidden="true">
			<path d="M22 5 39 35H5L22 5Z"></path>
		</svg>
		<svg class="cta-banner__deco cta-banner__deco--diamond" viewBox="0 0 44 44" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round" aria-hidden="true">
			<rect x="10" y="10" width="24" height="24" transform="rotate(20 22 22)"></rect>
		</svg>

		<div class="container">
			<div class="cta-banner__inner">
				<h2 class="cta-banner__title"><?php echo esc_html( $about_cta_heading ); ?></h2>
				<?php if ( $about_cta_text ) : ?>
					<p class="cta-banner__text"><?php echo esc_html( $about_cta_text ); ?></p>
				<?php endif; ?>
				<?php amber_button( $about_cta_btn, 'cta-navy', array( 'class' => 'btn--lg cta-banner__btn' ) ); ?>
			</div>
		</div>
	</section>

</article>
