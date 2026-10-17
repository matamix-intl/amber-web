/**
 * Gozzbe hero — first-load intro animation + tabbed product showcase.
 *
 * Intro: a laundry-basket video plays full-screen over the hero on the first
 * visit of a session and always plays to completion — there is no click/key
 * skip — the reveal is driven by the video's own `ended` event (falls back to
 * a fixed timer if there's no <video>, e.g. the bundled SVG fallback, or if
 * playback stalls) — then the headline "pops up" into place. Subsequent
 * navigations within the session skip straight to the finished hero (tracked
 * in sessionStorage) so a returning visitor isn't forced to re-watch it.
 *
 * Tabs: click / arrow-key switching between screenshot panels, with full
 * ARIA tab semantics. No-JS and reduced-motion both render a static, complete
 * hero — see the reduced-motion block in sections/_gozzbe-hero.scss.
 *
 * @package Amber
 */

const SEEN_KEY = 'amberGozzbeHeroSeen';
// Safety-net duration if the video never fires `ended`/`error` (e.g. the file
// fails to load silently, or there's no <video> at all and we're on the SVG
// fallback, which has no natural "done" event of its own).
const INTRO_FALLBACK_MS = 4000;

export function initGozzbeHero() {
	const hero = document.querySelector( '[data-gz-hero]' );
	if ( ! hero ) {
		return;
	}

	initIntro( hero );
	initTabs( hero );
}

/* --------------------------------------------------------------- Intro ---- */
function initIntro( hero ) {
	const intro = hero.querySelector( '[data-gz-intro]' );
	if ( ! intro ) {
		// No overlay in the markup — the CSS still hides the hero until
		// .is-revealed exists, so reveal it rather than leaving it blank.
		hero.classList.add( 'is-revealed' );
		return;
	}

	const reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	// Already seen this session, or the visitor prefers reduced motion → show
	// the finished hero immediately and drop the overlay.
	let seen = false;
	try {
		seen = window.sessionStorage.getItem( SEEN_KEY ) === '1';
	} catch ( e ) {
		// sessionStorage can throw in private mode — treat as "not seen".
		seen = false;
	}

	if ( seen || reduce.matches ) {
		intro.remove();
		hero.classList.add( 'is-revealed' );
		return;
	}

	// The collapsed state comes from CSS (`.js .gz-hero:not(.is-revealed)`) so
	// it is already painted; this module's only job is to release it on time.
	// The overlay is position:fixed, so freeze scrolling while it is up.
	document.body.classList.add( 'gz-intro-lock' );

	const finish = () => {
		if ( hero.dataset.gzDone === '1' ) {
			return;
		}
		hero.dataset.gzDone = '1';

		document.body.classList.remove( 'gz-intro-lock' );
		intro.classList.add( 'is-done' );
		hero.classList.add( 'is-revealed' );

		try {
			window.sessionStorage.setItem( SEEN_KEY, '1' );
		} catch ( e ) {
			// Non-fatal: the intro simply replays next load.
		}

		// Remove the overlay once its fade-out transition has finished.
		window.setTimeout( () => intro.remove(), 700 );
	};

	const video = intro.querySelector( '[data-gz-intro-video]' );

	if ( video ) {
		// The ONLY thing that ends the intro under normal conditions is the
		// video genuinely finishing. Earlier versions also raced it against a
		// timer (started as soon as this module ran, i.e. effectively at page
		// load, then "re-armed" once playback began) — if the video was at all
		// slow to buffer/start/decode, or `playing` fired late, that timer
		// could fire before the clip actually finished, revealing the heading
		// mid-animation. `ended` firing is the single source of truth now.
		video.addEventListener( 'ended', finish );

		// If the file 404s, is the wrong codec, or otherwise can't play at
		// all, don't strand the hero behind a frozen overlay forever.
		video.addEventListener( 'error', finish );

		// Last-resort safety net only — NOT a normal-path timer. Set far
		// longer than any reasonable intro clip so it can never race a video
		// that is actually playing; it only fires if something has gone
		// silently wrong (e.g. autoplay blocked with no error event, or a
		// file that hangs without ever reaching `ended`).
		window.setTimeout( finish, 20000 );
	} else {
		// No video (SVG fallback path) — timer is the only "done" signal there is.
		window.setTimeout( finish, INTRO_FALLBACK_MS );
	}

	// No click/key skip: the animation is meant to play to completion every
	// time before the heading appears. (A stray click anywhere on the page —
	// even just focusing the browser tab — used to land on this full-viewport
	// overlay and cut the intro short via the old skip handler.)
}

/* ---------------------------------------------------------------- Tabs ---- */
function initTabs( hero ) {
	const nav = hero.querySelector( '[data-gz-tabs]' );
	if ( ! nav ) {
		return;
	}

	const buttons = Array.from( nav.querySelectorAll( '[data-gz-tab]' ) );
	const panels = Array.from( nav.querySelectorAll( '[data-gz-panel]' ) );
	if ( ! buttons.length || ! panels.length ) {
		return;
	}

	const activate = ( id, focusBtn ) => {
		buttons.forEach( ( btn ) => {
			const on = btn.dataset.gzTab === id;
			btn.classList.toggle( 'is-active', on );
			btn.setAttribute( 'aria-selected', String( on ) );
			btn.setAttribute( 'tabindex', on ? '0' : '-1' );
			if ( on && focusBtn ) {
				btn.focus();
				// Keep the active pill in view in the scrollable nav.
				btn.scrollIntoView( { block: 'nearest', inline: 'nearest', behavior: 'smooth' } );
			}
		} );

		panels.forEach( ( panel ) => {
			const on = panel.dataset.gzPanel === id;
			if ( on ) {
				panel.hidden = false;
				// Next frame so the transition runs from the hidden state.
				window.requestAnimationFrame( () => panel.classList.add( 'is-active' ) );
			} else {
				panel.classList.remove( 'is-active' );
				panel.hidden = true;
			}
		} );
	};

	buttons.forEach( ( btn ) => {
		btn.addEventListener( 'click', () => activate( btn.dataset.gzTab, false ) );
	} );

	// Roving-tabindex keyboard support, per the ARIA tabs pattern.
	nav.addEventListener( 'keydown', ( e ) => {
		const current = buttons.findIndex( ( b ) => b.getAttribute( 'aria-selected' ) === 'true' );
		if ( current < 0 ) {
			return;
		}

		let next = -1;
		if ( 'ArrowRight' === e.key ) {
			next = ( current + 1 ) % buttons.length;
		} else if ( 'ArrowLeft' === e.key ) {
			next = ( current - 1 + buttons.length ) % buttons.length;
		} else if ( 'Home' === e.key ) {
			next = 0;
		} else if ( 'End' === e.key ) {
			next = buttons.length - 1;
		}

		if ( next >= 0 ) {
			e.preventDefault();
			activate( buttons[ next ].dataset.gzTab, true );
		}
	} );

	// Mark the initially-active panel so its transition starts from rest.
	const initial = buttons.find( ( b ) => b.classList.contains( 'is-active' ) );
	if ( initial ) {
		const panel = panels.find( ( p ) => p.dataset.gzPanel === initial.dataset.gzTab );
		panel && panel.classList.add( 'is-active' );
	}
}
