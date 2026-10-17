/**
 * ERP "Why Amber" — 3-up card carousel with looping prev/next arrows.
 *
 * Same clone-and-loop technique as the reviews slider: the card set is
 * cloned once on each side ([clones][originals][clones]) so the track can
 * slide past either end and silently snap back into the middle set once the
 * transition lands, giving the illusion of an infinite loop. Cards per view:
 * 3 desktop / 2 tablet / 1 mobile. Auto-advances on a timer (same
 * pause-on-hover/focus/hidden-tab convention as erp-keyfeatures-slider.js),
 * on top of the manual prev/next arrows — no drag.
 *
 * @package Amber
 */

export function initErpWhySlider() {
	const root = document.querySelector( '[data-erp-why]' );
	if ( ! root ) {
		return;
	}
	const viewport = root.querySelector( '[data-erp-why-viewport]' );
	const track = root.querySelector( '[data-erp-why-track]' );
	const prevBtn = root.querySelector( '[data-erp-why-prev]' );
	const nextBtn = root.querySelector( '[data-erp-why-next]' );
	if ( ! viewport || ! track || ! prevBtn || ! nextBtn ) {
		return;
	}

	const originals = Array.from( track.children );
	const count = originals.length;
	if ( count < 2 ) {
		prevBtn.hidden = true;
		nextBtn.hidden = true;
		return;
	}

	const perView = () => ( window.innerWidth >= 1024 ? 3 : ( window.innerWidth >= 768 ? 2 : 1 ) );

	// Read the track's actual CSS gap instead of assuming a fixed value — it's
	// a responsive clamp() (24-32px), not a constant. A hardcoded 32px here
	// previously mismatched the real ~24px gap at narrow viewports; since the
	// slide-shift math below multiplies this per card, that few-pixel-per-gap
	// error compounded across every card in the set, landing the "current"
	// card visibly off-centre within the clipped viewport instead of exactly
	// filling it.
	const getGap = () => parseFloat( getComputedStyle( track ).columnGap ) || 0;

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Clone the set on both sides for a seamless loop.
	const before = document.createDocumentFragment();
	const after = document.createDocumentFragment();
	originals.forEach( ( card ) => {
		const b = card.cloneNode( true );
		const a = card.cloneNode( true );
		b.setAttribute( 'aria-hidden', 'true' );
		a.setAttribute( 'aria-hidden', 'true' );
		before.appendChild( b );
		after.appendChild( a );
	} );
	track.insertBefore( before, track.firstChild );
	track.appendChild( after );

	const slides = Array.from( track.children );
	let index = count; // first original card

	let lastVw = 0;

	const position = ( instant ) => {
		const vw = viewport.clientWidth;
		lastVw = vw;
		const n = perView();
		const gap = getGap();
		const sw = ( vw - gap * ( n - 1 ) ) / n;
		slides.forEach( ( slide ) => { slide.style.width = sw + 'px'; } );
		const x = -( index * ( sw + gap ) );
		if ( instant || reduce ) {
			track.classList.add( 'is-instant' );
		}
		track.style.transform = 'translateX(' + x + 'px)';
		if ( instant || reduce ) {
			void track.offsetWidth; // flush so the jump isn't animated
			track.classList.remove( 'is-instant' );
		}
	};

	const settle = () => {
		if ( index >= count * 2 ) {
			index -= count;
			position( true );
		} else if ( index < count ) {
			index += count;
			position( true );
		}
	};

	const go = ( dir ) => {
		index += dir;
		position( reduce );
		if ( reduce ) {
			settle();
		} else {
			track.addEventListener( 'transitionend', settle, { once: true } );
		}
	};

	prevBtn.addEventListener( 'click', () => go( -1 ) );
	nextBtn.addEventListener( 'click', () => go( 1 ) );

	position( true );

	// Re-measure whenever the viewport's own rendered width actually changes,
	// not only on a window `resize` event. A single measurement taken right
	// at DOMContentLoaded can land a few pixels off if the layout hasn't
	// fully settled yet (e.g. a web-font swap reflowing a sibling, or the
	// flex-wrap breakpoint applying a beat later than expected) — with only
	// `resize` listening, that stale width then sticks until the visitor
	// happens to resize the window. ResizeObserver instead catches the
	// correction itself, self-healing regardless of why the first
	// measurement was off, and still fires on genuine window/viewport
	// resizes exactly like the old listener did.
	if ( 'ResizeObserver' in window ) {
		let pending = false;
		const ro = new ResizeObserver( () => {
			if ( pending || Math.abs( viewport.clientWidth - lastVw ) < 1 ) {
				return;
			}
			pending = true;
			requestAnimationFrame( () => {
				pending = false;
				position( true );
			} );
		} );
		ro.observe( viewport );
	} else {
		window.addEventListener( 'resize', () => position( true ) );
	}

	// Autoplay: advance one card at a time on a timer, reusing the same
	// go()/settle() loop logic the arrow buttons use. Skipped entirely for
	// prefers-reduced-motion; paused on hover/focus so a reader stopped on a
	// card isn't interrupted, and while the tab is hidden so it doesn't rack
	// up missed intervals in the background.
	if ( reduce ) {
		return;
	}

	const intervalMs = parseInt( root.dataset.erpWhyAutoplay || '4000', 10 );
	if ( ! ( intervalMs > 0 ) ) {
		return;
	}

	let timerId = null;
	const stopAutoplay = () => {
		if ( timerId ) {
			clearInterval( timerId );
			timerId = null;
		}
	};
	const startAutoplay = () => {
		stopAutoplay();
		timerId = setInterval( () => go( 1 ), intervalMs );
	};

	root.addEventListener( 'mouseenter', stopAutoplay );
	root.addEventListener( 'mouseleave', startAutoplay );
	root.addEventListener( 'focusin', stopAutoplay );
	root.addEventListener( 'focusout', startAutoplay );
	document.addEventListener( 'visibilitychange', () => {
		if ( document.hidden ) {
			stopAutoplay();
		} else {
			startAutoplay();
		}
	} );

	startAutoplay();
}
