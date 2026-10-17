/**
 * ERP Services — horizontal 10-point timeline (glassmorphism redesign).
 *
 * A single line with one numbered stop per service; a small marker slides
 * along the line to whichever point is active, and that point's title/text
 * shows in the glass info card below. Autoplay steps through the points on a
 * timer once the section scrolls into view — same self-running-demo pattern
 * as modules/gozzbe-features.js — and any click, Enter/Space, or arrow-key
 * press stops it and hands control to the visitor permanently for that
 * instance.
 *
 * Beyond the marker's own `left`, positionMarker() also writes two CSS custom
 * properties the redesigned styling reads: `--svc-progress` (the rail's
 * animated fill) and `--svc-connector-x` (the thread connecting the active
 * node down to the info card) — see sections/_erp.scss for both.
 *
 * No-JS: every point's dot still shows and the FIRST panel is already
 * server-rendered visible (no [hidden]), so the section reads fine without
 * JS, just without the marker/progress-fill/connector animating.
 *
 * @package Amber
 */

const AUTOPLAY_INTERVAL_MS = 3200;

export function initErpServicesTimeline() {
	document.querySelectorAll( '[data-svc-timeline]' ).forEach( initOneTimeline );
}

function initOneTimeline( root ) {
	const points = Array.from( root.querySelectorAll( '[data-svc-point]' ) );
	const panels = Array.from( root.querySelectorAll( '[data-svc-panel]' ) );
	const marker = root.querySelector( '[data-svc-marker]' );
	const line = root.querySelector( '.erp-svc__line' );
	if ( ! points.length || ! panels.length ) {
		return;
	}

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	// Position the marker at the horizontal centre of the active point
	// (measured in px against the line's own box, not derived from
	// index/count, so it lands correctly even though the 10 points don't all
	// render the exact same width). Alongside that px position, also set two
	// CSS custom properties the redesigned styling reads:
	//   --svc-progress        the rail's fill width, as a % of the line —
	//                         drives .erp-svc__line-track::after
	//   --svc-connector-x     the active node's centre, as a % of .erp-svc__timeline
	//                         (not the line, which can be wider than the
	//                         viewport on the mobile scroll-strip) — drives
	//                         .erp-svc__connector, which visually threads the
	//                         node down to the info card below
	const positionMarker = () => {
		if ( ! marker || ! line ) {
			return;
		}
		const active = points.find( ( p ) => p.classList.contains( 'is-active' ) );
		if ( ! active ) {
			return;
		}
		const lineBox = line.getBoundingClientRect();
		const dot = active.querySelector( '.erp-svc__point-dot' );
		const target = dot || active;
		const targetBox = target.getBoundingClientRect();
		const centerX = targetBox.left + targetBox.width / 2 - lineBox.left;
		marker.style.left = `${ centerX }px`;

		const progressPct = lineBox.width > 0 ? ( centerX / lineBox.width ) * 100 : 0;
		root.style.setProperty( '--svc-progress', `${ progressPct }%` );

		const timelineBox = root.getBoundingClientRect();
		const connectorX = targetBox.left + targetBox.width / 2 - timelineBox.left;
		const connectorPct = timelineBox.width > 0 ? ( connectorX / timelineBox.width ) * 100 : 50;
		root.style.setProperty( '--svc-connector-x', `${ connectorPct }%` );
	};

	const activate = ( index, focusPoint ) => {
		points.forEach( ( point, i ) => {
			const on = i === index;
			point.classList.toggle( 'is-active', on );
			point.setAttribute( 'aria-selected', String( on ) );
			point.setAttribute( 'tabindex', on ? '0' : '-1' );
			if ( on && focusPoint ) {
				point.focus();
				point.scrollIntoView( { block: 'nearest', inline: 'center', behavior: 'smooth' } );
			}
		} );

		panels.forEach( ( panel, i ) => {
			const on = i === index;
			if ( on ) {
				panel.hidden = false;
				window.requestAnimationFrame( () => panel.classList.add( 'is-active' ) );
			} else {
				panel.classList.remove( 'is-active' );
				panel.hidden = true;
			}
		} );

		positionMarker();
	};

	/* ------------------------------------------------------------ Auto-play */
	let autoplayTimer = null;

	const stopAutoplay = () => {
		if ( autoplayTimer ) {
			window.clearInterval( autoplayTimer );
			autoplayTimer = null;
		}
	};

	const startAutoplay = () => {
		if ( autoplayTimer || reduce.matches || points.length < 2 ) {
			return;
		}
		autoplayTimer = window.setInterval( () => {
			const current = points.findIndex( ( p ) => p.classList.contains( 'is-active' ) );
			activate( ( current + 1 ) % points.length, false );
		}, AUTOPLAY_INTERVAL_MS );
	};

	const handOffToVisitor = () => stopAutoplay();

	if ( 'IntersectionObserver' in window ) {
		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					startAutoplay();
				} else {
					stopAutoplay();
				}
			} );
		}, { threshold: 0.4 } );
		observer.observe( root );
	} else {
		startAutoplay();
	}

	reduce.addEventListener( 'change', () => {
		if ( reduce.matches ) {
			stopAutoplay();
		}
	} );

	/* -------------------------------------------------------- Interaction --*/
	points.forEach( ( point, i ) => {
		point.addEventListener( 'click', () => {
			handOffToVisitor();
			activate( i, false );
		} );
	} );

	root.querySelector( '.erp-svc__line' ).addEventListener( 'keydown', ( e ) => {
		const current = points.findIndex( ( p ) => p.getAttribute( 'aria-selected' ) === 'true' );
		if ( current < 0 ) {
			return;
		}

		let next = -1;
		if ( 'ArrowRight' === e.key ) {
			next = ( current + 1 ) % points.length;
		} else if ( 'ArrowLeft' === e.key ) {
			next = ( current - 1 + points.length ) % points.length;
		} else if ( 'Home' === e.key ) {
			next = 0;
		} else if ( 'End' === e.key ) {
			next = points.length - 1;
		}

		if ( next >= 0 ) {
			e.preventDefault();
			handOffToVisitor();
			activate( next, true );
		}
	} );

	// The line's own width changes with viewport resize (and the marker's %
	// position with it) — keep it glued to the active dot.
	window.addEventListener( 'resize', positionMarker );

	// Initial placement once layout has settled.
	window.requestAnimationFrame( positionMarker );
}
