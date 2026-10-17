/**
 * Scroll-Zoom Reveal.
 *
 * As a [data-reveal] section scrolls through its pinned range, the media frame
 * scales up from 0.5 → 1 and its corners straighten (40px → 0), while the giant
 * wordmark scales subtly and fades as the frame grows to cover it. Driven by a
 * rAF-throttled scroll handler. Disabled on mobile and for reduced-motion.
 *
 * @package Amber
 */

export function initRevealZoom() {
	const sections = document.querySelectorAll( '[data-reveal]' );
	if ( ! sections.length ) {
		return;
	}

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const desktop = window.matchMedia( '(min-width: 1024px)' );

	const easeOutCubic = ( t ) => 1 - Math.pow( 1 - t, 3 );

	const header = document.getElementById( 'site-header' );

	sections.forEach( ( section ) => {
		const frame = section.querySelector( '[data-reveal-frame]' );
		const title = section.querySelector( '[data-reveal-title]' );
		if ( ! frame ) {
			return;
		}

		let ticking = false;

		const reset = () => {
			frame.style.transform = '';
			frame.style.borderRadius = '';
			if ( title ) {
				title.style.transform = '';
				title.style.opacity = '';
			}
		};

		const update = () => {
			ticking = false;

			const rect = section.getBoundingClientRect();

			// Hide the floating header while the reveal is pinned & covering the
			// viewport, so the media reads clean & full-bleed (desktop only).
			if ( header ) {
				const covering = desktop.matches && rect.top <= 0 && rect.bottom >= window.innerHeight;
				header.classList.toggle( 'is-hidden', covering );
			}

			// Static on mobile / reduced-motion.
			if ( ! desktop.matches || reduce.matches ) {
				reset();
				return;
			}
			const total = section.offsetHeight - window.innerHeight;
			const scrolled = Math.min( Math.max( -rect.top, 0 ), total );
			const p = total > 0 ? scrolled / total : 1;

			// Finish the zoom a little before the very end so it "holds" full-bleed.
			// (This only produces a real hold while .reveal__stage actually pins —
			// see the `overflow-x: clip` note in base/_layout.scss.)
			const e = easeOutCubic( Math.min( p / 0.9, 1 ) );

			const scale = 0.5 + 0.5 * e;
			const radius = 40 * ( 1 - e );
			frame.style.transform = `scale(${ scale })`;
			frame.style.borderRadius = `${ radius }px`;

			if ( title ) {
				// Counter-move for depth: as the frame advances toward the viewer
				// the wordmark recedes INWARD — scaling down and drifting back a
				// little — so the two read as separate planes instead of both
				// growing together. It also clears out of the way before the
				// frame covers it, rather than fighting it for the same space.
				// Drive the recede from raw `p` as well, so it pulls back steadily
				// across the whole track instead of collapsing in the first quarter.
				const titleScale = 1 - 0.28 * p;   // 1.00 -> 0.72 (pulls away)
				const titleZ     = -140 * p;       // subtle push into the screen
				title.style.transform = `perspective(900px) translateZ(${ titleZ }px) scale(${ titleScale })`;
				// Fade on the RAW scroll progress, not the eased value: easeOutCubic
				// front-loads `e` (25% of scroll is already e~0.8), so any curve
				// built on `e` blinked out in the first third. Keying on `p` gives
				// an even fade — fully legible to 40%, gone by 80%, well before the
				// frame reaches full-bleed and would cover it anyway.
				title.style.opacity = String( Math.max( 0, Math.min( 1, ( 0.8 - p ) / 0.4 ) ) );
			}
		};

		const onScroll = () => {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		};

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll );
		desktop.addEventListener( 'change', update );
		reduce.addEventListener( 'change', update );
		update();
	} );
}
