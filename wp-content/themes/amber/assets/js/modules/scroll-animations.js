/**
 * Scroll reveal for [data-animate] elements via IntersectionObserver.
 *
 * Adds .is-visible once an element scrolls into view (then unobserves it, so
 * it's a one-shot reveal — premium, not distracting). Honours an optional
 * data-animate-delay (ms). Fully bypassed when the user prefers reduced motion
 * or IntersectionObserver is unavailable.
 *
 * @package Amber
 */

export function initScrollAnimations() {
	const els = document.querySelectorAll( '[data-animate]' );
	if ( ! els.length ) {
		return;
	}

	const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( prefersReduced || ! ( 'IntersectionObserver' in window ) ) {
		els.forEach( ( el ) => el.classList.add( 'is-visible' ) );
		return;
	}

	const observer = new IntersectionObserver(
		( entries, obs ) => {
			entries.forEach( ( entry ) => {
				if ( ! entry.isIntersecting ) {
					return;
				}
				const el = entry.target;
				const delay = parseInt( el.dataset.animateDelay || '0', 10 );
				if ( delay ) {
					el.style.transitionDelay = `${ delay }ms`;
				}
				el.classList.add( 'is-visible' );
				// Clear the delay after it has played so re-layout doesn't lag.
				el.addEventListener(
					'transitionend',
					() => { el.style.transitionDelay = ''; },
					{ once: true }
				);
				obs.unobserve( el );
			} );
		},
		{ rootMargin: '0px 0px -10% 0px', threshold: 0.12 }
	);

	els.forEach( ( el ) => observer.observe( el ) );
}
