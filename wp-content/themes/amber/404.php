<?php
/**
 * 404 — Not found.
 *
 * @package Amber
 */

get_header();
?>

<section class="section is-space-large error-404">
	<div class="container container--narrow error-404__inner">
		<p class="error-404__code">404</p>
		<h1 class="error-404__title"><?php esc_html_e( 'This page took a wrong turn', 'amber' ); ?></h1>
		<p class="error-404__text"><?php esc_html_e( 'The page you’re looking for doesn’t exist or has moved. Try a search, or head back home.', 'amber' ); ?></p>

		<?php get_search_form(); ?>

		<div class="error-404__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'amber' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
