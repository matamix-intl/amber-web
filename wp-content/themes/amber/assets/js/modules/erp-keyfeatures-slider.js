/**
 * ERP "Key Features" — single-slide crossfade carousel, autoplay only.
 *
 * Unlike erp-why-slider.js (3-up scrolling strip), this shows exactly one
 * image+text slide at a time and auto-advances on a timer — no manual
 * prev/next controls. Autoplay pauses on hover/focus and while the tab is
 * hidden, and is skipped entirely for prefers-reduced-motion.
 *
 * @package Amber
 */

export function initErpKeyFeaturesSlider() {
	const root = document.querySelector( '[data-erp-kf]' );
	if ( ! root ) {
		return;
	}
	const track = root.querySelector( '[data-erp-kf-track]' );
	if ( ! track ) {
		return;
	}

	const slides = Array.from( track.querySelectorAll( '[data-erp-kf-slide]' ) );
	const count = slides.length;
	if ( count < 2 ) {
		return;
	}

	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const intervalMs = parseInt( root.dataset.erpKfAutoplay || '5000', 10 );

	let active = slides.findIndex( ( s ) => s.classList.contains( 'is-active' ) );
	if ( active < 0 ) {
		active = 0;
	}

	const show = ( index ) => {
		active = ( index + count ) % count;
		slides.forEach( ( slide, i ) => {
			const isActive = i === active;
			slide.classList.toggle( 'is-active', isActive );
			slide.hidden = ! isActive;
		} );
	};
	const next = () => show( active + 1 );

	show( active );

	if ( reduceMotion || ! ( intervalMs > 0 ) ) {
		return;
	}

	let timerId = null;
	const stop = () => {
		if ( timerId ) {
			clearInterval( timerId );
			timerId = null;
		}
	};
	const start = () => {
		stop();
		timerId = setInterval( next, intervalMs );
	};

	root.addEventListener( 'mouseenter', stop );
	root.addEventListener( 'mouseleave', start );
	root.addEventListener( 'focusin', stop );
	root.addEventListener( 'focusout', start );
	document.addEventListener( 'visibilitychange', () => {
		if ( document.hidden ) {
			stop();
		} else {
			start();
		}
	} );

	start();
}
