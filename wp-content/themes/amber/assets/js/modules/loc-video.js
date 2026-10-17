/**
 * Global Presence / Our Locations — background video.
 *
 * The clip is large, so the markup ships without a src: this attaches it only
 * once the section is near the viewport, and pauses playback again whenever it
 * scrolls away so an offscreen 1080p video isn't burning battery. Under
 * prefers-reduced-motion the file still loads far enough to paint its first
 * frame as a still, but never plays.
 *
 * @package Amber
 */

export function initLocationsVideo() {
	const video = document.querySelector( '[data-loc-video]' );
	if ( ! video || ! video.dataset.src ) {
		return;
	}

	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	const attach = () => {
		if ( video.src ) {
			return;
		}
		// Reduced motion wants the poster frame only — metadata is enough.
		video.preload = reduceMotion ? 'metadata' : 'auto';
		video.src = video.dataset.src;
		video.classList.add( 'is-loaded' );
	};

	const play = () => {
		if ( reduceMotion ) {
			return;
		}
		const attempt = video.play();
		// Autoplay can still be refused (power saving, iOS Low Power Mode);
		// the scrim and section background stand on their own, so ignore it.
		if ( attempt && typeof attempt.catch === 'function' ) {
			attempt.catch( () => {} );
		}
	};

	if ( ! ( 'IntersectionObserver' in window ) ) {
		attach();
		play();
		return;
	}

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					attach();
					play();
				} else if ( ! video.paused ) {
					video.pause();
				}
			} );
		},
		// Start fetching just before the section scrolls into view.
		{ rootMargin: '300px 0px' }
	);

	observer.observe( video.closest( '.section' ) || video );
}
