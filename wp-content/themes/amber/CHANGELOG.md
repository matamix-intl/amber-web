# Changelog

All notable changes to the Amber theme are documented here.
This project adheres to [Semantic Versioning](https://semver.org/).

## [1.2.0] — 2026-07-10

### Changed
- **Hero redesigned as the centered aurora banner** (replaces the 4-slide
  product carousel). A white 100svh stage with teal/cyan aurora gradients
  glowing in from the top two corners (fading out by mid-height); a large
  centered headline (medium weight), muted subtitle and two pill CTAs
  (violet "Let's talk now" + white "View services"). Pure CSS — no images,
  no slider JS (`hero-slider.js` safely no-ops and stays for reuse).
- Hero ACF fields (Pro layout + Free flat group) simplified to match:
  heading, description and two buttons. The old per-slide `hero_s1–s4_*`
  fields are retired.
- **Header restyled to match the light hero**: the floating capsule is now
  white glass with dark links, dark brand lockup and a violet "Contact us"
  pill (default label changed from "Let's Talk"; phone/arrow icons removed).
  The Products mega menu and the mobile drawer moved to the same light
  surface, and the first-paint critical CSS no longer paints the header navy.

### Added
- **FAQ section redesigned + added to the homepage** (before Reviews): light
  violet band, centered white "FAQ" badge and "Got Questions? We've Got
  Answers" heading, white rounded accordion cards with the first item open.
  Keeps the accessible disclosure pattern (modules/accordion.js) and the
  FAQPage JSON-LD; 7 Amber-adapted Q&As.
- **Testimonials rewritten as the "Reviews" slider**
  (`modules/reviews-slider.js`): full-bleed auto-playing loop (3/2/1 cards
  per view), the centered card highlighted near-black with a pink/violet
  corner glow; pauses on hover/hidden tab, static row under reduced motion.
- **ERP showcase section** (`section-erp.php` + `sections/_erp-section.scss`),
  inserted after Case Studies in the homepage composition: a violet
  (`#864FFE`) two-row band. Row 1 pairs layered faux dashboards (Odoo-style
  sales dashboard behind a floating "Business Overview" finance card — pure
  CSS/SVG) with badge / heading / copy / a 2-column check list; row 2 mirrors
  it with a white pill CTA, a 3-up stats strip (320+ clients / 48 projects /
  1240 tasks) and three overlapping mini analytics cards. Copy editable via
  `amber_sub()` demo defaults.
- `template-parts/components/hero-ecosystem.php` — dashboard-ecosystem hero
  illustration built during this cycle; currently dormant (not rendered),
  kept for reuse.
- `users` inline icon.

## [1.1.0] — 2026-06-17

### Changed
- **Rebranded to the Amber Technologies identity:** sky-blue (`#38BDF8` / `#0EA5E9`)
  on deep navy (`#071226`), blue `#38BDF8 → #0284C7` gradient, all purple removed.
  Header is now dark-navy glass; footer and dark sections use the deep navy.
- Display font switched to **Satoshi** (Fontshare) + **Inter** body; updated the
  Gutenberg colour palette to match.
- Homepage demo composition now mirrors the multi-product reference design.

### Added
- **Ecosystem Hero** — navy hero with an animated product-ecosystem graphic
  (Amber logo core + ERP/POS/Zoomie/Gozzbe orbit, glow lines, float/pulse).
- **Products Showcase** — four product cards (Amber ERP, Amber POS, Zoomie, Gozzbe)
  with per-product accents, feature checklists and CSS dashboard previews.
- **Industries** grid, **Why-Choose Bento** grid, and an interactive **Live Product
  Experience** (ARIA tablist switching faux product dashboards — `live-demo.js`).
- Statistics restyled as glass cards on navy; CTA now an animated blue gradient mesh.
- New ACF flexible-content layouts + 10 new inline icons for the above.

## [1.0.0] — 2026-06-16

### Added
- Initial release.
- Modular PHP architecture bootstrapped from a thin `functions.php`.
- ACF Section Builder (Flexible Content) with 15 reusable layouts: Hero, Trusted-By
  Logos, Features, Services, Why Choose Us, Statistics, Process Timeline, Product
  Showcase, Testimonials, Pricing, FAQ, Team, Blog Preview, CTA, Contact.
- Global **Theme Settings** ACF options page (branding, header, footer, contact, social).
- Premium default content for every section (renders complete with zero data, and on
  ACF Free) via a demo-mode fallback engine.
- Sticky, shrink-on-scroll header with dropdowns, accessible mobile drawer.
- Fully editable footer with newsletter (AJAX), social icons and link columns.
- `service` (+ `service_cat`) and `testimonial` custom post types.
- Blog: index, archive, single (with sidebar), search, 404, comments, pagination.
- SEO: JSON-LD (Organization, WebSite, BreadcrumbList, FAQPage), Open Graph, Twitter
  cards, breadcrumbs; auto-defers to Yoast/Rank Math.
- Accessibility: skip link, ARIA nav/accordion, keyboard support, reduced-motion.
- Security: escaping, sanitisation, nonce-verified AJAX (newsletter + contact),
  honeypot, header hardening.
- SCSS design system (tokens, breakpoints mixins) compiled to minified CSS; block
  editor styles.
- Vanilla ES-module JavaScript: navigation, scroll reveal, counters, accordion, logo
  marquee, AJAX forms.
- Translation template (`languages/amber.pot`), importable ACF JSON (`acf-export/`),
  dart-sass build scripts.
