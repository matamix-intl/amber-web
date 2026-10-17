/**
 * Products Showcase — one-at-a-time hero slider with endless autoplay.
 *
 * Each slide is a full-width product hero (text left, image + teal blob right).
 * The slider advances one product at a time and loops forever using cloned
 * slides ([clones][originals][clones]) so it never runs out or leaves a gap: it
 * slides onto a clone, then silently re-homes onto the matching real slide once
 * the transition settles. Includes autoplay (data-products-autoplay, paused on
 * hover / focus / hidden tab), prev/next buttons, dots, drag/swipe and keyboard.
 *
 * All motion is a single translateX on the track — GPU-friendly, no layout
 * shifts. No-op when the markup isn't present.
 *
 * @package Amber
 */

export function initProductsSlider() {
	const root = document.querySelector( '[data-products-slider]' );
	if ( ! root ) {
		return;
	}

	const viewport = root.querySelector( '[data-products-viewport]' );
	const track = root.querySelector( '[data-products-track]' );
	const dotsWrap = root.querySelector( '[data-products-dots]' );
	const prevBtn = root.querySelector( '[data-products-prev]' );
	const nextBtn = root.querySelector( '[data-products-next]' );
	if ( ! viewport || ! track ) {
		return;
	}

	const originals = Array.from( track.querySelectorAll( '[data-products-slide]' ) );
	if ( originals.length < 2 ) {
		return;
	}

	const count = originals.length;
	const autoplayMs = parseInt( root.dataset.productsAutoplay || '0', 10 );

	// Infinite loop: clone the whole set before and after the originals.
	const cloneSlide = ( s ) => {
		const c = s.cloneNode( true );
		c.setAttribute( 'aria-hidden', 'true' );
		c.dataset.clone = '1';
		c.querySelectorAll( 'a, button' ).forEach( ( el ) => { el.tabIndex = -1; } );
		return c;
	};
	const frag = document.createDocumentFragment();
	originals.forEach( ( s ) => frag.appendChild( cloneSlide( s ) ) );
	track.insertBefore( frag, track.firstChild );
	originals.forEach( ( s ) => track.appendChild( cloneSlide( s ) ) );

	const slides = Array.from( track.querySelectorAll( '[data-products-slide]' ) );
	const realStart = count;
	const realIndex = ( i ) => ( ( i - realStart ) % count + count ) % count;

	let active = realStart;          // open on the first real product
	let autoplayId = null;
	let paused = false;

	// --- layout ------------------------------------------------------------------

	// Slide width = the viewport width (one slide per view).
	const slideWidth = () => viewport.getBoundingClientRect().width;

	const render = () => {
		track.style.transform = `translateX(${ -active * slideWidth() }px)`;
		slides.forEach( ( slide, i ) => {
			const link = slide.querySelector( 'a' );
			if ( link && ! slide.dataset.clone ) {
				link.tabIndex = i === active ? 0 : -1;
			}
		} );
		syncDots();
	};

	const normaliseLoop = () => {
		if ( active >= realStart && active < realStart + count ) {
			return;
		}
		active = realStart + realIndex( active );
		track.classList.add( 'is-instant' );
		render();
		void track.offsetWidth;
		track.classList.remove( 'is-instant' );
	};

	let normaliseTimer = null;
	const step = ( dir ) => {
		active += dir;
		render();
		clearTimeout( normaliseTimer );
		normaliseTimer = setTimeout( normaliseLoop, 650 );
	};
	const next = () => step( 1 );
	const prev = () => step( -1 );
	const goToReal = ( realIdx ) => {
		active = realStart + realIdx;
		render();
	};

	// --- dots --------------------------------------------------------------------

	let dots = [];
	const buildDots = () => {
		if ( ! dotsWrap ) {
			return;
		}
		dotsWrap.innerHTML = '';
		dots = originals.map( ( _s, i ) => {
			const dot = document.createElement( 'button' );
			dot.type = 'button';
			dot.className = 'products-slider__dot';
			dot.setAttribute( 'aria-label', `Go to product ${ i + 1 }` );
			dot.addEventListener( 'click', () => {
				goToReal( i );
				restartAutoplay();
			} );
			dotsWrap.appendChild( dot );
			return dot;
		} );
	};
	const syncDots = () => {
		const current = realIndex( active );
		dots.forEach( ( dot, i ) => {
			const on = i === current;
			dot.classList.toggle( 'is-active', on );
			dot.setAttribute( 'aria-current', on ? 'true' : 'false' );
		} );
	};

	// --- autoplay ----------------------------------------------------------------

	const canAutoplay = () => autoplayMs > 0 && count > 1;
	const stopAutoplay = () => {
		if ( autoplayId ) {
			clearInterval( autoplayId );
			autoplayId = null;
		}
	};
	const startAutoplay = () => {
		stopAutoplay();
		if ( ! canAutoplay() || paused ) {
			return;
		}
		autoplayId = setInterval( next, autoplayMs );
	};
	const restartAutoplay = () => {
		if ( canAutoplay() ) {
			startAutoplay();
		}
	};
	const pause = () => { paused = true; stopAutoplay(); };
	const resume = () => { paused = false; startAutoplay(); };

	// --- drag / swipe ------------------------------------------------------------

	let dragging = false;
	let startX = 0;
	let movedX = 0;
	let baseShift = 0;

	const onPointerDown = ( e ) => {
		dragging = true;
		startX = e.clientX;
		movedX = 0;
		baseShift = -active * slideWidth();
		track.classList.add( 'is-instant' );
		viewport.classList.add( 'is-dragging' );
		pause();
		viewport.setPointerCapture?.( e.pointerId );
	};
	const onPointerMove = ( e ) => {
		if ( ! dragging ) {
			return;
		}
		movedX = e.clientX - startX;
		track.style.transform = `translateX(${ baseShift + movedX }px)`;
	};
	const onPointerUp = () => {
		if ( ! dragging ) {
			return;
		}
		dragging = false;
		track.classList.remove( 'is-instant' );
		viewport.classList.remove( 'is-dragging' );
		const threshold = Math.max( 50, slideWidth() * 0.15 );
		if ( movedX <= -threshold ) {
			next();
		} else if ( movedX >= threshold ) {
			prev();
		} else {
			render(); // spring back
		}
		resume();
	};

	// --- wire up -----------------------------------------------------------------

	buildDots();
	render();

	prevBtn?.addEventListener( 'click', () => { prev(); restartAutoplay(); } );
	nextBtn?.addEventListener( 'click', () => { next(); restartAutoplay(); } );

	viewport.addEventListener( 'pointerdown', onPointerDown );
	viewport.addEventListener( 'pointermove', onPointerMove );
	viewport.addEventListener( 'pointerup', onPointerUp );
	viewport.addEventListener( 'pointercancel', onPointerUp );

	root.addEventListener( 'mouseenter', pause );
	root.addEventListener( 'mouseleave', resume );
	root.addEventListener( 'focusin', pause );
	root.addEventListener( 'focusout', resume );

	root.addEventListener( 'keydown', ( e ) => {
		if ( e.key === 'ArrowLeft' ) {
			prev();
			restartAutoplay();
		} else if ( e.key === 'ArrowRight' ) {
			next();
			restartAutoplay();
		}
	} );

	document.addEventListener( 'visibilitychange', () => {
		if ( document.hidden ) {
			stopAutoplay();
		} else {
			startAutoplay();
		}
	} );

	let resizeRaf = null;
	window.addEventListener( 'resize', () => {
		if ( resizeRaf ) {
			cancelAnimationFrame( resizeRaf );
		}
		resizeRaf = requestAnimationFrame( render );
	} );

	startAutoplay();
}
