/**
 * Products Showcase — 3D scroll sequence.
 *
 * One image at a time in a pinned window. Reaching the section shows the first.
 * Scrolling down zooms that image out and inward — scaled down and pushed back
 * along Z — and as it retreats the next rises up from below the window, at full
 * size, in front of it. Scrolling up runs the identical values backwards.
 *
 * The section around the window is dark, so an image going inward recedes into
 * the page rather than leaving a lit gap behind it.
 *
 * Nothing here is an animation with a direction: --out is a pure function of
 * scroll position, so reversing the scroll reverses the motion for free and
 * stopping mid-way leaves the sequence wherever the scroll left it.
 *
 * Per card it writes:
 *   --out  0 in place → 1 zoomed fully away inward.
 *   --in   1 waiting below the window → 0 risen into place.
 *   --dim  a light darkening as the image recedes.
 *
 * @package Amber
 */

// How one image-to-image scroll divides between the two moves. The receding
// image travels the WHOLE step: nothing fades any more, so if it finished
// early it would sit frozen mid-air in plain sight until the next image
// covered it. The next starts rising at ARRIVE_STARTS, once the one in front
// of it is well into its retreat.
const LEAVE_ENDS = 1;
const ARRIVE_STARTS = 0.3;

/**
 * Smoothstep — eases both ends of a 0→1 ramp.
 *
 * Scroll position is linear, so mapping it straight onto a transform makes the
 * move start and stop abruptly at the ends of its range. This rounds both ends
 * off, so each image accelerates away and settles into place instead of
 * snapping into motion.
 *
 * @param {number} t Linear progress, 0→1.
 * @return {number} Eased progress, 0→1.
 */
const ease = ( t ) => t * t * ( 3 - 2 * t );

export function initProductsStack() {
	const root = document.querySelector( '[data-products-stack]' );
	const stage = root?.querySelector( '[data-products-stack-stage]' );
	if ( ! root || ! stage ) {
		return;
	}

	const cards = Array.from( root.querySelectorAll( '[data-products-stack-card]' ) );
	if ( cards.length < 2 ) {
		return;
	}

	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	// Matches the breakpoint where the stylesheet flattens this to a list.
	const isStacked = window.matchMedia( '(min-width: 768px)' );

	const clamp = ( n ) => ( n < 0 ? 0 : n > 1 ? 1 : n );

	let queued = false;

	const clear = () => {
		cards.forEach( ( card ) => {
			card.style.removeProperty( '--out' );
			card.style.removeProperty( '--in' );
			card.style.removeProperty( '--dim' );
			card.style.removeProperty( 'visibility' );
			card.classList.remove( 'is-active' );
		} );
	};

	const update = () => {
		queued = false;

		if ( reduceMotion.matches || ! isStacked.matches ) {
			clear();
			return;
		}

		// How far through the sequence we are, 0 → 1. The stage is sticky
		// inside root, so as the page scrolls it slides down root's box: the
		// distance between their two tops IS the travel, with no need to know
		// the sticky offset or the scroll position itself.
		const travel = root.offsetHeight - stage.offsetHeight;
		if ( travel <= 0 ) {
			clear();
			return;
		}

		const travelled = stage.getBoundingClientRect().top - root.getBoundingClientRect().top;
		const progress = clamp( travelled / travel );

		// Which image is in front, as a fraction: image i is the one on show
		// while this sits at i.
		const focus = progress * ( cards.length - 1 );

		cards.forEach( ( card, i ) => {
			// How far the sequence has run past this image. Negative means it
			// is still waiting below the window; positive means it is on its
			// way inward.
			const q = focus - i;

			// Going inward: begins the moment the sequence moves past this
			// image, and is complete by LEAVE_ENDS.
			const out = q > 0 ? ease( clamp( q / LEAVE_ENDS ) ) : 0;

			// Rising: held below the window until the image in front is well
			// into its retreat, then comes the rest of the way up. `1 + q` is
			// how far this image has travelled since the previous one was in
			// place.
			const arrived = 1 + q;
			const inv = q < 0
				? 1 - ease( clamp( ( arrived - ARRIVE_STARTS ) / ( 1 - ARRIVE_STARTS ) ) )
				: 0;

			card.style.setProperty( '--out', out.toFixed( 3 ) );
			card.style.setProperty( '--in', inv.toFixed( 3 ) );
			card.style.setProperty( '--dim', ( out * 0.3 ).toFixed( 3 ) );

			// Nothing is transparent any more, so an image is either taking
			// part in the sequence or genuinely out of the page — no half-lit
			// overlays. Out of the page also keeps it off the tab order and out
			// of the compositor's way.
			//
			// `out >= 1` has finished receding and is covered by the image in
			// front; `inv >= 1` is still parked below the window. Everything
			// between the two is on screen and opaque.
			const involved = out < 1 && inv < 1;
			card.style.visibility = involved ? 'visible' : 'hidden';
			card.classList.toggle( 'is-active', out < 0.5 && inv < 0.5 );
		} );
	};

	// Scroll fires far more often than the screen repaints; coalesce to one
	// measurement per frame so this never becomes the reason scrolling stutters.
	const onScroll = () => {
		if ( ! queued ) {
			queued = true;
			window.requestAnimationFrame( update );
		}
	};

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll, { passive: true } );

	// Older Safari exposes MediaQueryList without addEventListener.
	if ( typeof reduceMotion.addEventListener === 'function' ) {
		reduceMotion.addEventListener( 'change', onScroll );
		isStacked.addEventListener( 'change', onScroll );
	}

	update();
}
