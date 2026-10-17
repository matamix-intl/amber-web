/**
 * Animated statistics counters.
 *
 * Counts [data-counter] elements up to data-counter-to when they enter view.
 * Preserves decimals and thousands separators, and respects reduced-motion by
 * jumping straight to the final formatted value.
 *
 * @package Amber
 */

export function initCounters() {
	const counters = document.querySelectorAll( '[data-counter]' );
	if ( ! counters.length ) {
		return;
	}

	const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	const format = ( value, decimals ) =>
		Number( value ).toLocaleString( undefined, {
			minimumFractionDigits: decimals,
			maximumFractionDigits: decimals,
		} );

	const run = ( el ) => {
		const target = parseFloat( el.dataset.counterTo || el.textContent ) || 0;
		const decimals = ( String( el.dataset.counterTo ).split( '.' )[ 1 ] || '' ).length;

		if ( prefersReduced ) {
			el.textContent = format( target, decimals );
			return;
		}

		const duration = 1600;
		const start = performance.now();
		// easeOutExpo for a confident, decelerating count.
		const ease = ( t ) => ( t === 1 ? 1 : 1 - Math.pow( 2, -10 * t ) );

		const tick = ( now ) => {
			const progress = Math.min( ( now - start ) / duration, 1 );
			el.textContent = format( target * ease( progress ), decimals );
			if ( progress < 1 ) {
				requestAnimationFrame( tick );
			} else {
				el.textContent = format( target, decimals );
			}
		};
		requestAnimationFrame( tick );
	};

	if ( ! ( 'IntersectionObserver' in window ) ) {
		counters.forEach( run );
		return;
	}

	const observer = new IntersectionObserver(
		( entries, obs ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					run( entry.target );
					obs.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.4 }
	);

	counters.forEach( ( el ) => observer.observe( el ) );
}
