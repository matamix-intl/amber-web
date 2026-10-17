/**
 * AJAX form handling for the newsletter and contact forms.
 *
 * Both submit to admin-ajax with the nonce localised in amberData. The server
 * (inc/security.php) verifies the nonce, sanitises and validates, then returns
 * JSON which we surface in an aria-live feedback region.
 *
 * @package Amber
 */

const { ajaxUrl, nonce } = window.amberData || {};

/**
 * Submit a form as URL-encoded data to a given WP action.
 *
 * @param {HTMLFormElement} form     The form.
 * @param {string}          action   The wp_ajax action name.
 * @param {HTMLElement}     feedback The aria-live element to write to.
 */
async function submitForm( form, action, feedback ) {
	const button = form.querySelector( '[type="submit"]' );
	const data = new URLSearchParams( new FormData( form ) );
	data.append( 'action', action );
	data.append( 'nonce', nonce || '' );

	button && button.classList.add( 'is-loading' );
	feedback.textContent = '';
	feedback.className = feedback.className.replace( /\bis-(success|error)\b/g, '' ).trim();

	try {
		const res = await fetch( ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: data.toString(),
		} );
		const json = await res.json();

		feedback.textContent = ( json.data && json.data.message ) || ( json.success ? 'Done.' : 'Something went wrong.' );
		feedback.classList.add( json.success ? 'is-success' : 'is-error' );

		if ( json.success ) {
			form.reset();
		}
	} catch ( err ) {
		feedback.textContent = 'Network error. Please try again.';
		feedback.classList.add( 'is-error' );
	} finally {
		button && button.classList.remove( 'is-loading' );
	}
}

export function initForms() {
	if ( ! ajaxUrl ) {
		return;
	}

	// Newsletter (footer).
	document.querySelectorAll( '[data-newsletter]' ).forEach( ( form ) => {
		const feedback = form.querySelector( '.newsletter__feedback' );
		form.addEventListener( 'submit', ( e ) => {
			e.preventDefault();
			submitForm( form, 'amber_newsletter', feedback );
		} );
	} );

	// Contact section.
	document.querySelectorAll( '[data-contact-form]' ).forEach( ( form ) => {
		const feedback = form.querySelector( '.form-feedback' );
		form.addEventListener( 'submit', ( e ) => {
			e.preventDefault();
			submitForm( form, 'amber_contact', feedback );
		} );
	} );
}
