/**
 * Navigation: sticky header shrink-on-scroll, mobile drawer, accessible
 * dropdown toggling on touch/keyboard.
 *
 * @package Amber
 */

export function initNavigation() {
	const header = document.getElementById( 'site-header' );
	const toggle = document.querySelector( '[data-nav-toggle]' );
	const drawer = document.querySelector( '[data-mobile-menu]' );

	/* ----------------------------------------------- Shrink on scroll ----- */
	if ( header && header.hasAttribute( 'data-shrink-on-scroll' ) ) {
		const SHRINK_AT = 24;
		let ticking = false;

		const onScroll = () => {
			if ( ! ticking ) {
				window.requestAnimationFrame( () => {
					header.classList.toggle( 'is-shrunk', window.scrollY > SHRINK_AT );
					ticking = false;
				} );
				ticking = true;
			}
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/* ----------------------------------------------- Mobile drawer -------- */
	if ( toggle && drawer ) {
		// Backdrop element (created once).
		const backdrop = document.createElement( 'div' );
		backdrop.className = 'mobile-menu-backdrop';
		document.body.appendChild( backdrop );

		const setOpen = ( open ) => {
			toggle.setAttribute( 'aria-expanded', String( open ) );
			toggle.setAttribute( 'aria-label', open ? 'Close menu' : 'Open menu' );
			drawer.classList.toggle( 'is-open', open );
			backdrop.classList.toggle( 'is-visible', open );
			document.body.classList.toggle( 'is-menu-open', open );
			if ( open ) {
				// Focus the first link for keyboard users.
				const firstLink = drawer.querySelector( 'a, button' );
				firstLink && firstLink.focus();
			} else {
				toggle.focus();
			}
		};

		toggle.addEventListener( 'click', () => {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		backdrop.addEventListener( 'click', () => setOpen( false ) );

		// Close on Escape, and on link click (so anchor nav feels native).
		document.addEventListener( 'keydown', ( e ) => {
			if ( 'Escape' === e.key && drawer.classList.contains( 'is-open' ) ) {
				setOpen( false );
			}
		} );
		drawer.addEventListener( 'click', ( e ) => {
			if ( e.target.closest( 'a' ) ) {
				setOpen( false );
			}
		} );

		// Close the drawer if the viewport grows to desktop.
		const mq = window.matchMedia( '(min-width: 1024px)' );
		mq.addEventListener( 'change', ( e ) => {
			if ( e.matches ) {
				setOpen( false );
			}
		} );
	}

	/* ----------------------------------------------- Products mega menu --- */
	// CSS handles hover-open; JS adds click-to-toggle + ARIA + Escape +
	// click-outside so it works for touch and keyboard users too.
	const mega = document.querySelector( '[data-mega]' );
	const megaTrigger = mega && mega.querySelector( '[data-mega-trigger]' );
	if ( mega && megaTrigger ) {
		const trigger = megaTrigger;

		const setMega = ( open ) => {
			mega.classList.toggle( 'is-open', open );
			trigger.setAttribute( 'aria-expanded', String( open ) );
		};

		trigger.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			setMega( trigger.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		// Close on outside click.
		document.addEventListener( 'click', ( e ) => {
			if ( ! mega.contains( e.target ) ) {
				setMega( false );
			}
		} );

		// Close on Escape and return focus to the trigger.
		mega.addEventListener( 'keydown', ( e ) => {
			if ( 'Escape' === e.key ) {
				setMega( false );
				trigger.focus();
			}
		} );

		// Keep ARIA in sync when the pointer leaves (CSS may have hover-opened it).
		mega.addEventListener( 'mouseleave', () => setMega( false ) );
		mega.addEventListener( 'mouseenter', () => trigger.setAttribute( 'aria-expanded', 'true' ) );
	}

	/* ------------------------------- Touch-friendly dropdowns (mobile) ---- */
	// On the mobile drawer, let parent items expand their submenu on tap.
	drawer &&
		drawer.querySelectorAll( '.menu-item-has-children > a, .menu-item-has-children > span' ).forEach( ( link ) => {
			const submenu = link.parentElement.querySelector( '.sub-menu' );
			if ( ! submenu ) {
				return;
			}
			const btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'submenu-toggle';
			btn.setAttribute( 'aria-expanded', 'false' );
			btn.setAttribute( 'aria-label', 'Toggle submenu' );
			btn.innerHTML = '<span aria-hidden="true">+</span>';
			link.after( btn );
			submenu.hidden = true;
			btn.addEventListener( 'click', () => {
				const open = btn.getAttribute( 'aria-expanded' ) === 'true';
				btn.setAttribute( 'aria-expanded', String( ! open ) );
				submenu.hidden = open;
			} );
		} );
}
