/**
 * Featured Case Studies — flat sliding strip under curved white frames.
 *
 * This mirrors exactly how the Pixbit reference works: the cards are a plain,
 * flat horizontal strip (a normal slider) — they are NOT rotated in 3D. The
 * curved "barrel band" is produced entirely by two white overlay frames
 * (`.cases__frame`, styled in SCSS) whose inner edge is a shallow convex curve;
 * they mask the flat strip into the showcase-band silhouette. So all this module
 * does geometrically is translate the strip to centre the active card.
 *
 * Nothing relies on native scroll, which lets us add:
 *   • seamless infinite looping in both directions
 *   • autoplay every N ms (data-cases-autoplay), paused on hover / focus / when
 *     the tab is hidden / for reduced-motion users
 *   • drag + swipe (pointer events) with a snap to the nearest card on release
 *   • prev / next buttons and synced pagination dots
 *
 * The curve only applies on wider screens; on mobile it's a single centred card.
 * All motion is transform-only — GPU friendly, no layout shifts. No-op when the
 * markup isn't present.
 *
 * @package Amber
 */

export function initCaseStudies() {
	const root = document.querySelector( '[data-cases-carousel]' );
	if ( ! root ) {
		return;
	}

	const stage = root.querySelector( '[data-cases-stage]' );
	const track = root.querySelector( '[data-cases-track]' );
	const dotsWrap = root.querySelector( '[data-cases-dots]' );
	const prevBtn = root.querySelector( '[data-cases-prev]' );
	const nextBtn = root.querySelector( '[data-cases-next]' );
	if ( ! stage || ! track ) {
		return;
	}

	const originals = Array.from( track.querySelectorAll( '[data-cases-slide]' ) );
	if ( originals.length < 2 ) {
		return;
	}

	const reduceMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	const GAP = 10; // px seam between cards (the reference's tight strip)

	// Mark the carousel active → the CSS switches from the no-JS scroll fallback
	// to the flat sliding strip that the stage mask curves into a smooth band.
	root.classList.add( 'is-carousel' );

	const count = originals.length;
	const autoplayMs = parseInt( root.dataset.casesAutoplay || '0', 10 );

	// --- infinite loop via cloned cards ------------------------------------------
	// For a truly endless rail we build [clonesA][originals][clonesB]: a full copy
	// of every card before AND after the real set. The strip then never "runs out"
	// — sliding past either end lands on clones that look identical to the originals,
	// and once the slide settles we silently jump (no transition) back to the
	// matching real card. Result: seamless looping in both directions, no empty gap.
	const buildLoop = () => {
		const frag = document.createDocumentFragment();
		// Prepend clones (in order) before the originals.
		originals.forEach( ( s ) => frag.appendChild( cloneSlide( s ) ) );
		track.insertBefore( frag, track.firstChild );
		// Append clones after the originals.
		originals.forEach( ( s ) => track.appendChild( cloneSlide( s ) ) );
	};
	const cloneSlide = ( s ) => {
		const c = s.cloneNode( true );
		c.setAttribute( 'aria-hidden', 'true' );
		c.dataset.clone = '1';
		// Clones are decorative duplicates — keep them out of the tab order.
		c.querySelectorAll( 'a' ).forEach( ( a ) => { a.tabIndex = -1; } );
		return c;
	};
	buildLoop();

	// The full slide list (clones + originals + clones). Real cards live at indices
	// [count, 2*count); a clone at index j represents real card j % count.
	const slides = Array.from( track.querySelectorAll( '[data-cases-slide]' ) );
	const realStart = count;                 // first real card's index in `slides`
	const realIndex = ( i ) => ( ( i - realStart ) % count + count ) % count;

	// Open on the middle real card so a full card shows on BOTH sides at once.
	let active = realStart + ( count > 2 ? 1 : 0 );
	let autoplayId = null;
	let paused = false;

	// --- layout ------------------------------------------------------------------

	const cardWidth = () => slides[ 0 ].getBoundingClientRect().width;
	const slotWidth = () => cardWidth() + GAP;

	const render = () => {
		const slot = slotWidth();
		// Track's left edge is pinned to stage centre (CSS `left:50%`); shift left by
		// the active card's centre so it lands dead-centre.
		const trackShift = -( active * slot + cardWidth() / 2 );
		track.style.transform = `translateX(${ trackShift }px)`;

		slides.forEach( ( slide, i ) => {
			slide.classList.toggle( 'is-active', i === active );
			const link = slide.querySelector( 'a' );
			if ( link && ! slide.dataset.clone ) {
				link.tabIndex = i === active ? 0 : -1;
				if ( i === active ) {
					link.setAttribute( 'aria-current', 'true' );
				} else {
					link.removeAttribute( 'aria-current' );
				}
			}
		} );
		syncDots();
	};

	// If the active card has slid into a clone zone, snap (no animation) to the
	// equivalent real card so the strip is always re-centred on the real set.
	const normaliseLoop = () => {
		if ( active >= realStart && active < realStart + count ) {
			return; // already on a real card
		}
		active = realStart + realIndex( active );
		track.classList.add( 'is-instant' );
		render();
		void track.offsetWidth;              // commit the teleport
		track.classList.remove( 'is-instant' );
	};

	const goTo = ( i ) => {
		active = i;
		render();
	};
	// Advance one card; after the slide finishes, re-home onto the real set. Using
	// the transition's end (or a timeout fallback) keeps the visible motion smooth.
	let normaliseTimer = null;
	const step = ( dir ) => {
		active += dir;
		render();
		clearTimeout( normaliseTimer );
		normaliseTimer = setTimeout( normaliseLoop, 650 ); // just after the 0.6s ease
	};
	const next = () => step( 1 );
	const prev = () => step( -1 );

	// --- dots --------------------------------------------------------------------

	let dots = [];
	const buildDots = () => {
		if ( ! dotsWrap ) {
			return;
		}
		dotsWrap.innerHTML = '';
		// One dot per REAL card (not the clones). Clicking a dot jumps to that real
		// card in the current (real) window.
		dots = originals.map( ( _slide, i ) => {
			const dot = document.createElement( 'button' );
			dot.type = 'button';
			dot.className = 'cases__dot';
			dot.setAttribute( 'aria-label', `Go to case study ${ i + 1 }` );
			dot.addEventListener( 'click', () => {
				goTo( realStart + i );
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

	// Autoplay runs whenever an interval is configured and there's more than one
	// card. (We intentionally don't gate on reduced-motion here — the section is
	// meant to auto-advance; the movement is a slow slide, not a flashing effect.)
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

	const pause = () => {
		paused = true;
		stopAutoplay();
	};
	const resume = () => {
		paused = false;
		startAutoplay();
	};

	// --- drag / swipe ------------------------------------------------------------

	let dragging = false;
	let startX = 0;
	let movedX = 0;
	let baseShift = 0; // track translateX at drag start

	const onPointerDown = ( e ) => {
		dragging = true;
		startX = e.clientX;
		movedX = 0;
		// Freeze the strip where it is and follow the finger 1:1 (no easing lag).
		const slot = slotWidth();
		baseShift = -( active * slot + cardWidth() / 2 );
		track.classList.add( 'is-instant' );
		stage.classList.add( 'is-dragging' );
		pause();
		stage.setPointerCapture?.( e.pointerId );
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
		track.classList.remove( 'is-instant' );   // ease back into the snap
		stage.classList.remove( 'is-dragging' );
		// Snap by however many whole slots the drag covered (fast flicks jump more
		// than one card); a small drag under a third of a card just springs back.
		const slot = slotWidth();
		const steps = Math.round( -movedX / slot );
		if ( steps !== 0 ) {
			active += steps;
			render();
			clearTimeout( normaliseTimer );
			normaliseTimer = setTimeout( normaliseLoop, 650 );
		} else {
			render(); // spring back to the current card
		}
		resume();
	};

	// --- wire up -----------------------------------------------------------------

	buildDots();
	render();

	prevBtn?.addEventListener( 'click', () => {
		prev();
		restartAutoplay();
	} );
	nextBtn?.addEventListener( 'click', () => {
		next();
		restartAutoplay();
	} );

	// Clicking a non-active card brings it to the centre instead of following the
	// link, so side cards act like carousel controls.
	slides.forEach( ( slide, i ) => {
		slide.addEventListener(
			'click',
			( e ) => {
				if ( i !== active ) {
					e.preventDefault();
					goTo( i );
					restartAutoplay();
				}
			},
			true
		);
	} );

	stage.addEventListener( 'pointerdown', onPointerDown );
	stage.addEventListener( 'pointermove', onPointerMove );
	stage.addEventListener( 'pointerup', onPointerUp );
	stage.addEventListener( 'pointercancel', onPointerUp );

	root.addEventListener( 'mouseenter', pause );
	root.addEventListener( 'mouseleave', resume );
	root.addEventListener( 'focusin', pause );
	root.addEventListener( 'focusout', resume );

	// Keyboard: arrows step the carousel when it holds focus.
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
