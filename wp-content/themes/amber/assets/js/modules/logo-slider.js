/**
 * Trusted-by logo marquee.
 *
 * Repeats the original set enough times to fill one "half" of the track wider
 * than the viewport, then mirrors that half so the CSS keyframe
 * (translateX(-50%)) loops seamlessly — guaranteeing visible motion even when
 * there are only a few logos. Animation speed stays constant by scaling the
 * duration with the number of repeats. Falls back to a static, centered row
 * only when the user prefers reduced motion.
 *
 * @package Amber
 */

// Seconds for one original run to travel its own width — keeps the scroll
// speed identical regardless of how many times the set is repeated.
const SECONDS_PER_RUN = 38;

export function initLogoSlider() {
	const track = document.querySelector( '[data-logos-track]' );
	if ( ! track ) {
		return;
	}

	const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const viewport = track.parentElement;

	const setup = () => {
		// Reset any previous duplication/inline styles before re-measuring.
		track.classList.remove( 'is-animated' );
		track.style.animationDuration = '';
		track.querySelectorAll( '[data-clone]' ).forEach( ( n ) => n.remove() );

		if ( prefersReduced ) {
			track.style.justifyContent = 'center';
			track.style.width = '100%';
			track.style.flexWrap = 'wrap';
			return;
		}

		track.style.justifyContent = '';
		track.style.width = '';
		track.style.flexWrap = '';

		// Width of one untouched run of the original logos.
		const singleWidth = track.scrollWidth;
		const originals = Array.from( track.children );
		if ( ! singleWidth || ! originals.length ) {
			return;
		}

		// Repeat the set so one "half" already overflows the viewport, then keep
		// the total even so the two halves are identical for the -50% loop.
		const runsPerHalf = Math.max( 1, Math.ceil( viewport.clientWidth / singleWidth ) );
		const totalRuns = runsPerHalf * 2;

		// We already have run #1; append the remaining runs as clones.
		for ( let run = 1; run < totalRuns; run++ ) {
			originals.forEach( ( item ) => {
				const clone = item.cloneNode( true );
				clone.setAttribute( 'data-clone', '' );
				clone.setAttribute( 'aria-hidden', 'true' );
				track.appendChild( clone );
			} );
		}

		// Scale duration with the repeat count so px/sec stays constant.
		track.style.animationDuration = `${ runsPerHalf * SECONDS_PER_RUN }s`;
		track.classList.add( 'is-animated' );
	};

	setup();

	// Re-evaluate on resize (debounced).
	let raf = null;
	window.addEventListener( 'resize', () => {
		if ( raf ) {
			cancelAnimationFrame( raf );
		}
		raf = requestAnimationFrame( setup );
	} );
}
