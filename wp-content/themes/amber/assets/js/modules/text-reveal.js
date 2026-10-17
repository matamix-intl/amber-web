/**
 * Scroll-fill text reveal.
 *
 * As a [data-textreveal] section scrolls through its pinned range, the heading
 * words light up from faded to solid in reading order (with a one-word feather
 * for a smooth, continuous fill). Disabled / fully-lit on mobile and for
 * reduced motion.
 *
 * @package Amber
 */

export function initTextReveal() {
	const sections = document.querySelectorAll( '[data-textreveal]' );
	if ( ! sections.length ) {
		return;
	}

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const FADED = 0.12;

	sections.forEach( ( section ) => {
		const words = Array.from( section.querySelectorAll( '.textreveal__word' ) );
		if ( ! words.length ) {
			return;
		}

		let ticking = false;

		const update = () => {
			ticking = false;

			if ( reduce.matches ) {
				words.forEach( ( w ) => { w.style.opacity = '1'; } );
				return;
			}

			const rect = section.getBoundingClientRect();
			const total = section.offsetHeight - window.innerHeight;

			// No pinned range (mobile / short section) → show fully.
			if ( total <= 0 ) {
				words.forEach( ( w ) => { w.style.opacity = '1'; } );
				return;
			}

			const scrolled = Math.min( Math.max( -rect.top, 0 ), total );
			const p = scrolled / total;
			const lit = p * words.length; // how many words are "lit" so far

			words.forEach( ( w, i ) => {
				const o = Math.min( Math.max( lit - i, 0 ), 1 ); // 0..1 feather per word
				w.style.opacity = ( FADED + ( 1 - FADED ) * o ).toFixed( 3 );
			} );
		};

		const onScroll = () => {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		};

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll );
		reduce.addEventListener( 'change', update );
		update();
	} );
}
