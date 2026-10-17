/**
 * Zoomie "Modules" panel (reference: sigai.ae/products/dine "DINE MODULES").
 *
 * Click (or arrow-key) a row in the left list to swap the photo and the
 * right-hand heading/description/highlights panel — plain show/hide via the
 * `hidden` attribute, no crossfade and no autoplay (the reference is a
 * static reveal, not a carousel).
 *
 * @package Amber
 */

export function initZmModules() {
	document.querySelectorAll( '[data-zm-modules]' ).forEach( initOne );
}

function initOne( root ) {
	const items = Array.from( root.querySelectorAll( '[data-zm-modules-item]' ) );
	const media = Array.from( root.querySelectorAll( '.zm-modules__media' ) );
	const panels = Array.from( root.querySelectorAll( '.zm-modules__panel' ) );
	if ( ! items.length || ! panels.length ) {
		return;
	}

	const activate = ( index, focusItem ) => {
		items.forEach( ( item, i ) => {
			const on = i === index;
			item.classList.toggle( 'is-active', on );
			item.setAttribute( 'aria-selected', String( on ) );
			item.setAttribute( 'tabindex', on ? '0' : '-1' );
			if ( on && focusItem ) {
				item.focus();
			}
		} );

		media.forEach( ( el, i ) => {
			const on = i === index;
			el.classList.toggle( 'is-active', on );
			el.hidden = ! on;
		} );

		panels.forEach( ( panel, i ) => {
			const on = i === index;
			panel.classList.toggle( 'is-active', on );
			panel.hidden = ! on;
		} );
	};

	items.forEach( ( item, i ) => {
		item.addEventListener( 'click', () => activate( i, false ) );

		item.addEventListener( 'keydown', ( e ) => {
			if ( 'Enter' === e.key || ' ' === e.key ) {
				e.preventDefault();
				activate( i, false );
			}
		} );
	} );

	root.querySelector( '.zm-modules__nav' ).addEventListener( 'keydown', ( e ) => {
		const current = items.findIndex( ( it ) => it.getAttribute( 'aria-selected' ) === 'true' );
		if ( current < 0 ) {
			return;
		}

		let next = -1;
		if ( 'ArrowDown' === e.key ) {
			next = ( current + 1 ) % items.length;
		} else if ( 'ArrowUp' === e.key ) {
			next = ( current - 1 + items.length ) % items.length;
		} else if ( 'Home' === e.key ) {
			next = 0;
		} else if ( 'End' === e.key ) {
			next = items.length - 1;
		}

		if ( next >= 0 ) {
			e.preventDefault();
			activate( next, true );
		}
	} );
}
