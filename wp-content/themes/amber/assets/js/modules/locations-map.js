/**
 * Global Presence / Our Locations — connector lines.
 *
 * Card heights vary with content, so the lines from each card to the globe
 * can't be pre-computed in CSS; this measures real DOM rects and draws an
 * SVG line + arrowhead per card, re-run on resize.
 *
 * @package Amber
 */

export function initLocationsMap() {
	const stage = document.querySelector( '[data-locations-stage]' );
	const svg = stage?.querySelector( '[data-locations-lines]' );
	const globe = stage?.querySelector( '.loc-globe' );
	if ( ! stage || ! svg || ! globe ) {
		return;
	}

	const draw = () => {
		const cards = stage.querySelectorAll( '[data-locations-card]' );

		// The stacked mobile layout hides the globe — no lines to draw there.
		if ( ! cards.length || globe.offsetParent === null ) {
			svg.querySelectorAll( 'line' ).forEach( ( n ) => n.remove() );
			return;
		}

		const stageRect = stage.getBoundingClientRect();
		const globeRect = globe.getBoundingClientRect();
		const globeX = globeRect.left + globeRect.width / 2 - stageRect.left;
		const globeY = globeRect.top + globeRect.height / 2 - stageRect.top;
		const globeR = globeRect.width / 2;

		svg.setAttribute( 'viewBox', `0 0 ${ stageRect.width } ${ stageRect.height }` );
		svg.setAttribute( 'width', stageRect.width );
		svg.setAttribute( 'height', stageRect.height );

		const lines = [];
		cards.forEach( ( card ) => {
			const side = card.getAttribute( 'data-locations-card' );
			const rect = card.getBoundingClientRect();
			const startX = side === 'right' ? rect.right - stageRect.left : rect.left - stageRect.left;
			const startY = rect.top + rect.height / 2 - stageRect.top;

			// End the line on the globe's edge (not its centre) so the
			// arrowhead lands on the sphere instead of inside it.
			const angle = Math.atan2( globeY - startY, globeX - startX );
			const endX = globeX - Math.cos( angle ) * ( globeR + 6 );
			const endY = globeY - Math.sin( angle ) * ( globeR + 6 );

			lines.push(
				`<line x1="${ startX }" y1="${ startY }" x2="${ endX }" y2="${ endY }" marker-end="url(#loc-arrow)" />`
			);
		} );

		svg.querySelectorAll( 'line' ).forEach( ( n ) => n.remove() );
		svg.insertAdjacentHTML( 'beforeend', lines.join( '' ) );
	};

	draw();

	let raf = null;
	const scheduleDraw = () => {
		if ( raf ) {
			cancelAnimationFrame( raf );
		}
		raf = requestAnimationFrame( draw );
	};

	window.addEventListener( 'resize', scheduleDraw );

	// The section fades in via [data-animate]; sizes settle right after, and
	// web fonts loading can reflow card heights after first paint.
	window.addEventListener( 'load', scheduleDraw );
	if ( document.fonts?.ready ) {
		document.fonts.ready.then( scheduleDraw );
	}
}
