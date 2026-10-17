/**
 * Hero — one-at-a-time product carousel with endless autoplay.
 *
 * Each slide is a full-width product hero on its own vivid background. The slider
 * advances one product at a time and loops forever using cloned slides
 * ([clones][originals][clones]) so it never runs out or leaves a gap. Includes
 * autoplay (data-hero-autoplay, paused on hover / focus / hidden tab), dots,
 * drag/swipe and keyboard. Motion is a single translateX on the track.
 *
 * @package Amber
 */

export function initHeroSlider() {
	const root = document.querySelector( '[data-hero-slider]' );
	if ( ! root ) {
		return;
	}

	const viewport = root.querySelector( '[data-hero-viewport]' );
	const track = root.querySelector( '[data-hero-track]' );
	const dotsWrap = root.querySelector( '[data-hero-dots]' );
	if ( ! viewport || ! track ) {
		return;
	}

	const originals = Array.from( track.querySelectorAll( '[data-hero-slide]' ) );
	if ( originals.length < 2 ) {
		return; // one slide → nothing to rotate
	}

	const count = originals.length;
	const autoplayMs = parseInt( root.dataset.heroAutoplay || '0', 10 );

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

	const slides = Array.from( track.querySelectorAll( '[data-hero-slide]' ) );
	const realStart = count;
	const realIndex = ( i ) => ( ( i - realStart ) % count + count ) % count;

	let active = realStart;
	let autoplayId = null;
	let paused = false;

	const slideWidth = () => viewport.getBoundingClientRect().width;

	const render = () => {
		track.style.transform = `translateX(${ -active * slideWidth() }px)`;
		slides.forEach( ( slide, i ) => {
			slide.classList.toggle( 'is-active', i === active );
			const focusable = slide.querySelector( 'a, button' );
			if ( focusable && ! slide.dataset.clone ) {
				focusable.tabIndex = i === active ? 0 : -1;
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
		normaliseTimer = setTimeout( normaliseLoop, 750 );
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
			dot.className = 'hero-slider__dot';
			dot.setAttribute( 'aria-label', `Go to product ${ i + 1 }` );
			dot.addEventListener( 'click', () => { goToReal( i ); restartAutoplay(); } );
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
			render();
		}
		resume();
	};

	// --- wire up -----------------------------------------------------------------

	buildDots();
	render();

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

	// --- mouse parallax ----------------------------------------------------------
	// Depth-shift the dashboard, blob and KPI cards toward/away from the cursor so
	// the right-side visual gains subtle 3D life. Each element's `data-hero-depth`
	// scales how far it moves. Skipped for reduced-motion and coarse (touch) pointers.
	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const finePointer = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

	if ( ! reduceMotion && finePointer ) {
		const depthEls = () =>
			Array.from(
				slides[ active ]?.querySelectorAll( '[data-hero-depth]' ) || []
			);
		let parallaxRaf = null;
		const onMove = ( e ) => {
			const r = viewport.getBoundingClientRect();
			// -0.5 … 0.5 offset from centre.
			const px = ( e.clientX - r.left ) / r.width - 0.5;
			const py = ( e.clientY - r.top ) / r.height - 0.5;
			if ( parallaxRaf ) {
				cancelAnimationFrame( parallaxRaf );
			}
			parallaxRaf = requestAnimationFrame( () => {
				depthEls().forEach( ( el ) => {
					const d = parseFloat( el.dataset.heroDepth ) || 1;
					const max = 14 * d; // px travel at the edges
					el.style.setProperty( '--px', `${ ( -px * max ).toFixed( 1 ) }px` );
					el.style.setProperty( '--py', `${ ( -py * max ).toFixed( 1 ) }px` );
				} );
			} );
		};
		const onLeave = () => {
			depthEls().forEach( ( el ) => {
				el.style.setProperty( '--px', '0px' );
				el.style.setProperty( '--py', '0px' );
			} );
		};
		viewport.addEventListener( 'mousemove', onMove );
		viewport.addEventListener( 'mouseleave', onLeave );
	}

	startAutoplay();
}
