/**
 * POS "Key Features" interactive list (reference: GoFrugal's "Key features
 * of a POS system" section).
 *
 * Click (or arrow-key) a row in the list to swap the detail panel on the
 * right — panels toggle via the `hidden` attribute (no crossfade), matching
 * the reference's plain swap rather than the Gozzbe feature sections' 3D
 * card + image-crossfade treatment. Same autoplay/observer/keyboard pattern
 * as modules/gozzbe-features.js, kept as a separate module since the two
 * sections' markup and visual design differ (list-only rows here, no
 * icon/inline description; a single detail panel with title/text/link/image
 * instead of a crossfading image stack).
 *
 * @package Amber
 */

const AUTOPLAY_INTERVAL_MS = 3200;

export function initPosKeyFeatures() {
	document.querySelectorAll( '[data-pos-key]' ).forEach( initOneSection );
}

function initOneSection( grid ) {
	const items = Array.from( grid.querySelectorAll( '[data-pos-key-item]' ) );
	const panels = Array.from( grid.querySelectorAll( '.pos-key__panel' ) );
	if ( ! items.length || ! panels.length ) {
		return;
	}

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );

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

		panels.forEach( ( panel, i ) => {
			const on = i === index;
			panel.classList.toggle( 'is-active', on );
			panel.hidden = ! on;
		} );
	};

	/* ------------------------------------------------------------ Auto-play */
	let autoplayTimer = null;

	const stopAutoplay = () => {
		if ( autoplayTimer ) {
			window.clearInterval( autoplayTimer );
			autoplayTimer = null;
		}
	};

	const startAutoplay = () => {
		if ( autoplayTimer || reduce.matches || items.length < 2 ) {
			return;
		}
		autoplayTimer = window.setInterval( () => {
			const current = items.findIndex( ( it ) => it.classList.contains( 'is-active' ) );
			activate( ( current + 1 ) % items.length, false );
		}, AUTOPLAY_INTERVAL_MS );
	};

	const handOffToVisitor = () => stopAutoplay();

	if ( 'IntersectionObserver' in window ) {
		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					startAutoplay();
				} else {
					stopAutoplay();
				}
			} );
		}, { threshold: 0.4 } );
		observer.observe( grid );
	} else {
		startAutoplay();
	}

	reduce.addEventListener( 'change', () => {
		if ( reduce.matches ) {
			stopAutoplay();
		}
	} );

	/* -------------------------------------------------------- Interaction --*/
	items.forEach( ( item, i ) => {
		item.addEventListener( 'click', () => {
			handOffToVisitor();
			activate( i, false );
		} );

		item.addEventListener( 'keydown', ( e ) => {
			if ( 'Enter' === e.key || ' ' === e.key ) {
				e.preventDefault();
				handOffToVisitor();
				activate( i, false );
			}
		} );
	} );

	grid.querySelector( '.pos-key__list' ).addEventListener( 'keydown', ( e ) => {
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
			handOffToVisitor();
			activate( next, true );
		}
	} );
}
