/**
 * Live product experience — accessible tabbed dashboard switcher.
 *
 * Implements the WAI-ARIA tabs pattern: click or arrow-key between product
 * tabs to swap the dashboard panel. No-op when the markup isn't present.
 *
 * @package Amber
 */

export function initLiveDemo() {
	const widgets = document.querySelectorAll( '[data-live-demo]' );
	if ( ! widgets.length ) {
		return;
	}

	widgets.forEach( ( widget ) => {
		const tabs = Array.from( widget.querySelectorAll( '[role="tab"]' ) );
		const panels = Array.from( widget.querySelectorAll( '[role="tabpanel"]' ) );
		if ( ! tabs.length ) {
			return;
		}

		const select = ( index ) => {
			tabs.forEach( ( tab, i ) => {
				const active = i === index;
				tab.setAttribute( 'aria-selected', String( active ) );
				tab.setAttribute( 'tabindex', active ? '0' : '-1' );
				tab.classList.toggle( 'is-active', active );
				if ( panels[ i ] ) {
					panels[ i ].hidden = ! active;
					panels[ i ].classList.toggle( 'is-active', active );
				}
			} );
		};

		tabs.forEach( ( tab, index ) => {
			tab.addEventListener( 'click', () => select( index ) );

			tab.addEventListener( 'keydown', ( e ) => {
				let next = null;
				if ( 'ArrowDown' === e.key || 'ArrowRight' === e.key ) { next = ( index + 1 ) % tabs.length; }
				if ( 'ArrowUp' === e.key || 'ArrowLeft' === e.key ) { next = ( index - 1 + tabs.length ) % tabs.length; }
				if ( 'Home' === e.key ) { next = 0; }
				if ( 'End' === e.key ) { next = tabs.length - 1; }
				if ( null !== next ) {
					e.preventDefault();
					select( next );
					tabs[ next ].focus();
				}
			} );
		} );
	} );
}
