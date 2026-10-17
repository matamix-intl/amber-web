<?php
/**
 * Custom search form.
 *
 * @package Amber
 */

$amber_search_id = 'amber-search-' . esc_attr( uniqid() );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $amber_search_id ); ?>"><?php esc_html_e( 'Search for:', 'amber' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $amber_search_id ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Search…', 'amber' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required>
	<button type="submit" class="search-form__submit btn btn--primary"><?php esc_html_e( 'Search', 'amber' ); ?></button>
</form>
