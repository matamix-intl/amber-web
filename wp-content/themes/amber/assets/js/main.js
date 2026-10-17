/**
 * Amber — main entry (loaded as a native ES module, so it's deferred).
 *
 * Each concern lives in its own module under /modules and exports an init()
 * that is a no-op when its markup isn't present, so the bundle is safe on
 * every template.
 *
 * @package Amber
 */

import { initNavigation } from './modules/navigation.js';
import { initScrollAnimations } from './modules/scroll-animations.js';
import { initCounters } from './modules/counters.js';
import { initAccordion } from './modules/accordion.js';
import { initLogoSlider } from './modules/logo-slider.js';
import { initForms } from './modules/forms.js';
import { initLiveDemo } from './modules/live-demo.js';
import { initRevealZoom } from './modules/reveal-zoom.js';
import { initTextReveal } from './modules/text-reveal.js';
import { initCaseStudies } from './modules/case-studies.js';
import { initProductsStack } from './modules/products-stack.js';
import { initHeroSlider } from './modules/hero-slider.js';
import { initSectionCover } from './modules/section-cover.js';
import { initReviewsSlider } from './modules/reviews-slider.js';
import { initLocationsVideo } from './modules/loc-video.js';
import { initDemoModal } from './modules/demo-modal.js';
import { initGozzbeHero } from './modules/gozzbe-hero.js';
import { initGozzbeFeatures } from './modules/gozzbe-features.js';
import { initPosKeyFeatures } from './modules/pos-key-features.js';
import { initErpServicesTimeline } from './modules/erp-services-timeline.js';
import { initErpWhySlider } from './modules/erp-why-slider.js';
import { initErpKeyFeaturesSlider } from './modules/erp-keyfeatures-slider.js';
import { initZmModules } from './modules/zm-modules.js';

// Swap the no-js flag as early as possible so CSS can react.
document.documentElement.classList.remove( 'no-js' );
document.documentElement.classList.add( 'js' );

const ready = ( fn ) => {
	if ( document.readyState !== 'loading' ) {
		fn();
	} else {
		document.addEventListener( 'DOMContentLoaded', fn, { once: true } );
	}
};

// Run an init in isolation: a throw in one module must never prevent the
// others from running. This matters because the .js class above already hid
// every [data-animate] element (opacity:0); if a module threw before the
// scroll-reveal ran, all that content would stay blank forever.
const run = ( label, fn ) => {
	try {
		fn();
	} catch ( err ) {
		// eslint-disable-next-line no-console
		console.error( `[amber] ${ label } init failed:`, err );
	}
};

ready( () => {
	run( 'navigation', initNavigation );

	// Before the scroll-reveal: this collapses the Gozzbe hero for its intro
	// animation, so it has to settle the hero's state ahead of anything that
	// might paint it in its final position.
	run( 'gozzbe-hero', initGozzbeHero );

	// The scroll-reveal is special: it's the only thing that un-hides
	// [data-animate] content. If it ever throws, fail open and show everything
	// so the page can never be left blank below the fold.
	try {
		initScrollAnimations();
	} catch ( err ) {
		// eslint-disable-next-line no-console
		console.error( '[amber] scroll-animations init failed — revealing all:', err );
		document
			.querySelectorAll( '[data-animate]' )
			.forEach( ( el ) => el.classList.add( 'is-visible' ) );
	}

	run( 'counters', initCounters );
	run( 'accordion', initAccordion );
	run( 'logo-slider', initLogoSlider );
	run( 'forms', initForms );
	run( 'live-demo', initLiveDemo );
	run( 'reveal-zoom', initRevealZoom );
	run( 'text-reveal', initTextReveal );
	run( 'case-studies', initCaseStudies );
	run( 'products-stack', initProductsStack );
	run( 'hero-slider', initHeroSlider );
	run( 'section-cover', initSectionCover );
	run( 'reviews-slider', initReviewsSlider );
	run( 'locations-video', initLocationsVideo );
	run( 'demo-modal', initDemoModal );
	run( 'gozzbe-features', initGozzbeFeatures );
	run( 'pos-key-features', initPosKeyFeatures );
	run( 'erp-services-timeline', initErpServicesTimeline );
	run( 'erp-why-slider', initErpWhySlider );
	run( 'erp-keyfeatures-slider', initErpKeyFeaturesSlider );
	run( 'zm-modules', initZmModules );
} );
