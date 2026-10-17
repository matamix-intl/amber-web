/**
 * Reviews — centered auto-playing testimonial slider.
 *
 * The slide set is cloned once on each side ([clones][originals][clones]) so
 * the rail can loop seamlessly. Every tick the track slides one card; the
 * card in the middle of the viewport carries `.is-active` (the dark
 * highlighted style). Slides per view: 3 desktop / 2 tablet / 1 mobile.
 * Autoplay pauses on hover and on hidden tabs; under prefers-reduced-motion
 * the rail is static (no clones, no autoplay) with the middle card active.
 *
 * @package Amber
 */

const GAP = 32;
const INTERVAL = 3000;

export function initReviewsSlider() {
	const root = document.querySelector( '[data-reviews]' );
	if ( ! root ) {
		return;
	}
	const viewport = root.querySelector( '[data-reviews-viewport]' );
	const track = root.querySelector( '[data-reviews-track]' );
	if ( ! viewport || ! track ) {
		return;
	}

	const originals = Array.from( track.children );
	const count = originals.length;
	if ( count < 2 ) {
		return;
	}

	const perView = () => ( window.innerWidth >= 1024 ? 3 : ( window.innerWidth >= 768 ? 2 : 1 ) );
	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Static fallback: no clones, no motion — middle card highlighted.
	if ( reduce ) {
		const mid = Math.min( count - 1, Math.floor( perView() / 2 ) );
		originals[ mid ].classList.add( 'is-active' );
		return;
	}

	// Clone the set on both sides for a seamless loop.
	const before = document.createDocumentFragment();
	const after = document.createDocumentFragment();
	originals.forEach( ( slide ) => {
		const b = slide.cloneNode( true );
		const a = slide.cloneNode( true );
		b.setAttribute( 'aria-hidden', 'true' );
		a.setAttribute( 'aria-hidden', 'true' );
		before.appendChild( b );
		after.appendChild( a );
	} );
	track.insertBefore( before, track.firstChild );
	track.appendChild( after );

	const slides = Array.from( track.children );
	let index = count; // first original, centered

	const setActive = () => {
		slides.forEach( ( slide, i ) => slide.classList.toggle( 'is-active', i === index ) );
	};

	const position = ( instant ) => {
		const vw = viewport.clientWidth;
		const n = perView();
		const sw = ( vw - GAP * ( n - 1 ) ) / n;
		slides.forEach( ( slide ) => { slide.style.width = sw + 'px'; } );
		const x = vw / 2 - ( index * ( sw + GAP ) + sw / 2 );
		if ( instant ) {
			track.classList.add( 'is-instant' );
		}
		track.style.transform = 'translateX(' + x + 'px)';
		if ( instant ) {
			void track.offsetWidth; // flush so the jump isn't animated
			track.classList.remove( 'is-instant' );
		}
	};

	const next = () => {
		index++;
		position( false );
		setActive();
		if ( index >= count * 2 ) {
			// Snap back one full set once the slide animation lands.
			track.addEventListener(
				'transitionend',
				() => {
					index -= count;
					position( true );
					setActive();
				},
				{ once: true }
			);
		}
	};

	position( true );
	setActive();
	window.addEventListener( 'resize', () => position( true ) );

	let timer = window.setInterval( next, INTERVAL );
	const stop = () => window.clearInterval( timer );
	const start = () => { stop(); timer = window.setInterval( next, INTERVAL ); };

	root.addEventListener( 'mouseenter', stop );
	root.addEventListener( 'mouseleave', start );
	document.addEventListener( 'visibilitychange', () => ( document.hidden ? stop() : start() ) );
}
