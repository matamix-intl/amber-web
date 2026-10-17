/**
 * "Schedule a Demo" modal — opened by any [data-open-modal="demo-modal"]
 * trigger (product page "View Demo" buttons). Same open/close/focus pattern
 * as the mobile nav drawer for consistency.
 *
 * @package Amber
 */

export function initDemoModal() {
	const modal = document.querySelector( '[data-demo-modal]' );
	if ( ! modal ) {
		return;
	}

	const closeBtn = modal.querySelector( '[data-demo-modal-close]' );
	const panel = modal.querySelector( '.demo-modal__panel' );
	let lastFocused = null;

	// The CF7 shortcode writes required-field markers as plain "Label *" text;
	// wrap the trailing asterisk so it can be styled red without touching the
	// form definition.
	modal.querySelectorAll( 'label' ).forEach( ( label ) => {
		const text = label.textContent;
		if ( / \*$/.test( text ) && ! label.querySelector( '.req-mark' ) ) {
			label.innerHTML = `${ text.slice( 0, -1 ).trimEnd() } <span class="req-mark" aria-hidden="true">*</span>`;
		}
	} );

	const backdrop = document.createElement( 'div' );
	backdrop.className = 'demo-modal-backdrop';
	document.body.appendChild( backdrop );

	const setOpen = ( open ) => {
		modal.hidden = ! open;
		backdrop.classList.toggle( 'is-visible', open );
		document.body.classList.toggle( 'is-menu-open', open );

		if ( open ) {
			lastFocused = document.activeElement;
			const firstField = panel.querySelector( 'input, select, textarea, button, a' );
			firstField && firstField.focus();
		} else if ( lastFocused ) {
			lastFocused.focus();
		}
	};

	document.addEventListener( 'click', ( e ) => {
		const trigger = e.target.closest( '[data-open-modal="demo-modal"]' );
		if ( trigger ) {
			e.preventDefault();
			setOpen( true );
		}
	} );

	closeBtn && closeBtn.addEventListener( 'click', () => setOpen( false ) );
	backdrop.addEventListener( 'click', () => setOpen( false ) );

	document.addEventListener( 'keydown', ( e ) => {
		if ( 'Escape' === e.key && ! modal.hidden ) {
			setOpen( false );
		}
	} );

	// Basic focus trap: keep Tab cycling inside the panel while open.
	modal.addEventListener( 'keydown', ( e ) => {
		if ( 'Tab' !== e.key || modal.hidden ) {
			return;
		}
		const focusable = panel.querySelectorAll( 'input, select, textarea, button, a[href]' );
		if ( ! focusable.length ) {
			return;
		}
		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];
		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		}
	} );
}
