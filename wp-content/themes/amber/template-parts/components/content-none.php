<?php
/**
 * Component: empty-state for archives / search with no results.
 *
 * @package Amber
 */

?>
<div class="empty-state">
	<h2 class="empty-state__title"><?php esc_html_e( 'Nothing found', 'amber' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p class="empty-state__text"><?php esc_html_e( 'We couldn’t find anything for that search. Try different keywords.', 'amber' ); ?></p>
	<?php else : ?>
		<p class="empty-state__text"><?php esc_html_e( 'There’s nothing here yet. Please check back soon.', 'amber' ); ?></p>
	<?php endif; ?>
	<?php get_search_form(); ?>
</div>
