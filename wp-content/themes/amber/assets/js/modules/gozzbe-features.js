/**
 * Gozzbe interactive feature list ("Point of Sale" reference layout).
 *
 * Click (or arrow-key) a row in the left list to expand its description and
 * swap the crossfaded image in the right-hand visual stage. Multiple
 * instances of this section can exist on one page (each has its own
 * [data-gzf] grid), so every wire-up is scoped per-instance.
 *
 * Auto-play: once the section scrolls into view, the rows step through
 * themselves top-to-bottom, one at a time, each pass re-syncing the
 * right-hand image — a self-running demo of the whole list. Any real click,
 * keyboard interaction, or arrow-key press permanently hands control back to
 * the visitor for that instance (auto-play does not resume after that).
 *
 * No-JS: every row's description stays permanently expanded via CSS
 * (`.no-js .gzf-item__body`), and the desktop image stage is hidden — see
 * sections/_gozzbe-features.scss — so the content is still fully readable.
 *
 * @package Amber
 */

const AUTOPLAY_INTERVAL_MS = 3200;

export function initGozzbeFeatures() {
	document.querySelectorAll( '[data-gzf]' ).forEach( initOneSection );
}

function initOneSection( grid ) {
	const items = Array.from( grid.querySelectorAll( '[data-gzf-item]' ) );
	if ( ! items.length ) {
		return;
	}

	const images = Array.from( grid.querySelectorAll( '.gzf-visual__img' ) );
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

		images.forEach( ( img, i ) => {
			img.classList.toggle( 'is-active', i === index );
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

	// Any deliberate interaction hands control to the visitor permanently —
	// an auto-advancing list that keeps stealing focus/position back from
	// someone actively reading it would be worse than not auto-playing at all.
	const handOffToVisitor = () => stopAutoplay();

	// Start once the section is actually on screen, not the instant the page
	// loads (it may be well below the fold on first paint).
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

		// Rows are plain <div role="tab">, not native buttons — wire up Enter
		// and Space so they're operable from the keyboard, matching what a
		// <button> would give for free.
		item.addEventListener( 'keydown', ( e ) => {
			if ( 'Enter' === e.key || ' ' === e.key ) {
				e.preventDefault();
				handOffToVisitor();
				activate( i, false );
			}
		} );
	} );

	// Roving-tabindex arrow-key navigation between rows, per the ARIA tabs
	// pattern (same approach as modules/gozzbe-hero.js's tab bar).
	grid.querySelector( '.gzf-list' ).addEventListener( 'keydown', ( e ) => {
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
