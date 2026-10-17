/**
 * Section cover — the "upper section recedes inward" scroll effect on the
 * Amber ERP page: .section--erp-features is pinned (CSS position: sticky)
 * while the black .section--erp-services panel scrolls up over it.
 *
 * This module does two things:
 *
 * 1. Fixes the sticky offset. A section taller than the viewport pinned at
 *    top: 0 could never be read to the bottom, so the offset is set to
 *    min(0, viewportHeight - sectionHeight): short sections pin at the top,
 *    tall ones scroll through normally and pin bottom-aligned just before
 *    the cover arrives.
 *
 * 2. Drives the recede animation. As the cover section crosses the viewport,
 *    the pinned section scales down (1 → 0.94) and fades slightly, so it
 *    appears to sink away beneath the incoming panel. Transform/opacity only,
 *    updated inside requestAnimationFrame.
 *
 * No-op when the sections aren't on the page; honours prefers-reduced-motion.
 */
export function initSectionCover() {
	const cover = document.querySelector( '.section--erp-services' );
	const pinned = document.querySelector( '.section--erp-features' );
	if ( ! cover || ! pinned ) {
		return;
	}

	// 1) Sticky offset: bottom-align tall sections so they stay fully readable.
	const setStickyTop = () => {
		pinned.style.top = Math.min( 0, window.innerHeight - pinned.offsetHeight ) + 'px';
	};
	setStickyTop();
	window.addEventListener( 'resize', setStickyTop );
	if ( 'ResizeObserver' in window ) {
		// Tab switches inside the section change its height.
		new ResizeObserver( setStickyTop ).observe( pinned );
	}

	if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	// 2) Scroll-driven recede: progress 0 → 1 as the cover's top edge travels
	// from the bottom of the viewport to the top.
	let ticking = false;

	const update = () => {
		ticking = false;
		const vh = window.innerHeight;
		const progress = Math.min( 1, Math.max( 0, ( vh - cover.getBoundingClientRect().top ) / vh ) );
		pinned.style.transform = 'scale(' + ( 1 - 0.06 * progress ) + ')';
		pinned.style.opacity = String( 1 - 0.3 * progress );
	};

	const onScroll = () => {
		if ( ! ticking ) {
			ticking = true;
			requestAnimationFrame( update );
		}
	};

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	update();
}
