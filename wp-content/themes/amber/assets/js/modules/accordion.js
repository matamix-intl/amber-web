/**
 * Accessible FAQ accordion.
 *
 * Each trigger is a real <button aria-expanded> controlling a panel region.
 * Opening one closes the others (classic single-open accordion). Height is
 * animated for a smooth reveal; the panel's [hidden] is toggled for a11y.
 *
 * @package Amber
 */

export function initAccordion() {
	const accordions = document.querySelectorAll( '[data-accordion]' );
	if ( ! accordions.length ) {
		return;
	}

	accordions.forEach( ( accordion ) => {
		const items = Array.from( accordion.querySelectorAll( '.accordion__item' ) );
		const triggers = Array.from( accordion.querySelectorAll( '.accordion__trigger' ) );

		const close = ( item ) => {
			const trigger = item.querySelector( '.accordion__trigger' );
			const panel = item.querySelector( '.accordion__panel' );
			trigger.setAttribute( 'aria-expanded', 'false' );
			item.classList.remove( 'is-open' );
			panel.style.maxHeight = panel.scrollHeight + 'px'; // from current
			requestAnimationFrame( () => { panel.style.maxHeight = '0px'; } );
			panel.addEventListener(
				'transitionend',
				() => { if ( trigger.getAttribute( 'aria-expanded' ) === 'false' ) { panel.hidden = true; panel.style.maxHeight = ''; } },
				{ once: true }
			);
		};

		const open = ( item ) => {
			const trigger = item.querySelector( '.accordion__trigger' );
			const panel = item.querySelector( '.accordion__panel' );
			trigger.setAttribute( 'aria-expanded', 'true' );
			item.classList.add( 'is-open' );
			panel.hidden = false;
			panel.style.maxHeight = '0px';
			requestAnimationFrame( () => { panel.style.maxHeight = panel.scrollHeight + 'px'; } );
			panel.addEventListener(
				'transitionend',
				() => { if ( trigger.getAttribute( 'aria-expanded' ) === 'true' ) { panel.style.maxHeight = 'none'; } },
				{ once: true }
			);
		};

		// Smooth height needs a transition on max-height; add it inline so the
		// stylesheet stays declarative.
		accordion.querySelectorAll( '.accordion__panel' ).forEach( ( panel ) => {
			panel.style.transition = 'max-height 0.4s cubic-bezier(0.22, 1, 0.36, 1)';
			panel.style.overflow = 'hidden';
			// Sync initial state with the server-rendered markup.
			const expanded = panel.previousElementSibling.querySelector( '.accordion__trigger' ).getAttribute( 'aria-expanded' ) === 'true';
			if ( expanded ) {
				panel.hidden = false;
				panel.style.maxHeight = 'none';
				panel.closest( '.accordion__item' ).classList.add( 'is-open' );
			} else {
				panel.hidden = true;
			}
		} );

		triggers.forEach( ( trigger, index ) => {
			const item = trigger.closest( '.accordion__item' );

			trigger.addEventListener( 'click', () => {
				const isOpen = trigger.getAttribute( 'aria-expanded' ) === 'true';
				items.forEach( ( other ) => { if ( other !== item ) { close( other ); } } );
				isOpen ? close( item ) : open( item );
			} );

			// Roving arrow-key navigation between questions.
			trigger.addEventListener( 'keydown', ( e ) => {
				let target = null;
				if ( 'ArrowDown' === e.key ) { target = triggers[ index + 1 ]; }
				if ( 'ArrowUp' === e.key ) { target = triggers[ index - 1 ]; }
				if ( 'Home' === e.key ) { target = triggers[ 0 ]; }
				if ( 'End' === e.key ) { target = triggers[ triggers.length - 1 ]; }
				if ( target ) { e.preventDefault(); target.focus(); }
			} );
		} );
	} );
}
