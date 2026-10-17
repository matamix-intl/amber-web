# Amber — Premium Business & SaaS WordPress Theme

Amber is a fully custom, enterprise-grade WordPress theme for modern business and
SaaS websites. It is **Gutenberg-compatible**, **ACF-powered**, **mobile-first**,
**SEO-friendly**, **accessibility-conscious** and built with a **clean, modular PHP
architecture** — no Elementor, no page-builder dependency.

> **Design language:** premium SaaS, inspired by Stripe / Linear / Framer — spacious,
> confident typography, subtle motion, conversion-focused layouts.

---

## ✨ Highlights

- **Section Builder** — a single ACF Flexible-Content field powers every page. Stack
  19 reusable sections in any order, on any page:
  - *Amber Technologies set:* **Ecosystem Hero**, **Products Showcase** (multi-product
    cards), **Industries**, **Why-Choose Bento**, **Live Product Experience** (tabbed
    dashboards), **Statistics**.
  - *General set:* Hero, Trusted-By Logos, Features, Services, Why Choose Us, Process
    Timeline, Product Showcase, Testimonials, Pricing, FAQ, Team, Blog, CTA, Contact.
- **Everything editable** from the WordPress admin — no hardcoded content. A global
  **Theme Settings** options page controls branding, header, footer, contact & social.
- **Looks complete out of the box.** Every section ships premium default content, so
  the homepage looks finished the moment you activate the theme — even before you add
  a single field, and even on ACF Free.
- **Performance-first** — compressed CSS, native-module JS (deferred), critical CSS,
  font-display swap, lazy/responsive images, IntersectionObserver animations.
- **SEO built-in** — semantic HTML5, JSON-LD schema (Organization, WebSite, Breadcrumb,
  FAQ), Open Graph & Twitter cards (auto-disabled if Yoast/Rank Math is active).
- **Accessible** — skip links, focus states, ARIA-correct nav/accordion, reduced-motion
  support, keyboard navigation.
- **Secure** — output escaping, input sanitisation, nonce-verified AJAX, honeypot,
  header hardening.

---

## 📋 Requirements

| Software | Version |
|---|---|
| WordPress | 6.2+ (tested to 7.0) |
| PHP | 7.4+ (8.1+ recommended) |
| **Advanced Custom Fields PRO** | 6.x — **required for full editing** (Flexible Content, Repeater, Options Page) |
| Node.js (only to recompile SCSS) | 18+ |

> **ACF Free vs PRO.** The theme *runs* on ACF Free (or with no ACF at all) thanks to
> built-in defaults, but the **Section Builder, Repeaters and the Theme Settings page
> are ACF PRO features**. Install ACF PRO to edit everything from the admin. An admin
> notice will remind you if PRO is missing.

---

## 🚀 Installation (step by step)

1. **Upload the theme**
   - *Admin:* Appearance → Themes → Add New → Upload Theme → choose `amber.zip` → Install.
   - *Manual:* copy the `amber/` folder into `wp-content/themes/`.
2. **Activate** Amber under Appearance → Themes.
3. **Install Advanced Custom Fields PRO** (Plugins → Add New → upload the ACF PRO zip)
   and activate it. (Free ACF works, but PRO unlocks all editing.)
4. **Fields register automatically.** As soon as ACF PRO is active you'll see a
   **Theme Settings** menu item and a **Page Sections** box on the front page and all
   Pages. *No import is required* — the field groups are registered in code.
   - *Optional:* if you prefer database-managed fields, import the files in
     `/acf-export/` via Custom Fields → Tools → Import. See **ACF & the Section Builder**
     below before doing this.
5. **Set the homepage:** Settings → Reading → "Your homepage displays" → *A static page*
   → create/select a page (e.g. "Home"). The `front-page.php` template renders the
   Section Builder. (If you skip this, the latest-posts homepage also renders the demo.)
6. **Create your menus:** Appearance → Menus. Assign them to the locations:
   *Primary Menu (Header)*, *Footer — Quick Links*, *Footer — Company*, *Legal*.
7. **Fill in Theme Settings:** logo, header CTA, footer content, contact details, social
   links, copyright.
8. **Build pages** with the Section Builder (below). Done!

---

## 🧩 ACF & the Section Builder

### How fields are registered

The theme registers two ACF field groups **in code** (the readable, commented source
of truth):

- `inc/acf/fields-options.php` → the **Theme Settings** options page.
- `inc/acf/fields-page-builder.php` → the **Page Sections** flexible-content builder.

Helper factories in `inc/acf/field-helpers.php` keep those definitions short and
consistent. Because they're registered via `acf_add_local_field_group()`, the fields
appear automatically — there is nothing to import.

### Local JSON sync & the import files

`inc/acf/acf-init.php` points ACF Local JSON at the theme's `/acf-json` directory.
When you edit a field group in the admin (ACF PRO), your changes are mirrored there as
portable JSON — great for version control and migrating between environments.

For convenience we also ship pre-exported JSON in **`/acf-export/`**:

- `group_amber_options.json`
- `group_amber_builder.json`

These are provided as an **importable backup**. You normally don't need them (the code
already registers everything). **If you do import them**, the fields then live in the
database *and* in code — to avoid duplicate groups, comment out the two `require` lines
for `fields-options.php` / `fields-page-builder.php` in
`inc/acf/acf-init.php → amber_register_fields()`.

### Building a page

1. Edit any Page (or the front page).
2. In **Page Sections**, click **+ Add Section** and pick a layout.
3. Fill the fields (headings, text, images, repeater rows, buttons, background, spacing).
4. Drag to reorder. Publish. The matching template part in
   `template-parts/sections/section-{layout}.php` renders it.

Each layout exposes **Background** (White / Surface / Dark / Gradient), **Spacing**
(Compact / Normal / Large / None) and an optional **Anchor ID** for in-page links.

---

## 🎨 Customisation

- **Brand colour:** Theme Settings → Branding → *Brand Accent Color*, or edit the
  `--amber-brand*` / `--amber-navy*` tokens in `assets/scss/settings/_tokens.scss`.
  The default identity is **Amber Technologies**: sky-blue `#38BDF8` / `#0EA5E9` on a
  deep-navy `#071226`, with a `#38BDF8 → #0EA5E9 → #0284C7` gradient.
- **Design tokens** (colours, spacing, type scale, radius, shadows, motion) all live in
  `assets/scss/settings/_tokens.scss` as CSS custom properties.
- **Fonts:** Inter (body, Google Fonts) + Satoshi (display, Fontshare) are loaded in
  `inc/enqueue.php` (`amber_resource_hints`). Swap them there, then update the
  `--amber-font-*` tokens.
- **Self-hosting fonts:** replace the Google Fonts `<link>` in `amber_resource_hints`
  with `@font-face` rules and `wp_enqueue_style` your font CSS for best privacy/perf.

### Build tools (only needed if you edit SCSS/JS)

```bash
cd wp-content/themes/amber
npm install          # installs dart-sass
npm run build        # compile main.css + editor.css (minified)
npm run watch        # recompile on save
```

JavaScript ships as native ES modules (`assets/js/main.js` + `assets/js/modules/*`),
so **no bundler is required** — edit and refresh.

---

## 🗂️ File-by-file guide

### Root templates
| File | Purpose |
|---|---|
| `style.css` | Theme header (metadata) only. Compiled CSS lives in `assets/css/main.css`. |
| `functions.php` | Thin bootstrap: defines constants, loads `/inc` modules in order. |
| `header.php` | `<head>`, skip link, announcement bar, sticky/shrink header, nav, mobile drawer. |
| `footer.php` | Footer columns, contact, newsletter, social, bottom bar; closes `#main`. |
| `front-page.php` | Renders the Section Builder (or the curated demo) for the homepage. |
| `page.php` | Pages: Section Builder if used, else title + Gutenberg content. |
| `single.php` | Single post / CPT: reading column, sidebar, post nav, comments. |
| `archive.php`, `index.php`, `search.php` | Post grids with pagination / empty states. |
| `404.php` | Friendly not-found page with search. |
| `comments.php` | Accessible comment list + form. |
| `searchform.php` | Custom styled search form. |

### `/inc` — PHP modules
| File | Purpose |
|---|---|
| `helpers.php` | Safe ACF accessors (`amber_field`, `amber_option`), `amber_image`, `amber_button`, inline SVG icon maps, `amber_section_part`. |
| `theme-setup.php` | `add_theme_support`, menus, image sizes, editor palette/fonts, widgets, excerpt. |
| `enqueue.php` | Styles/scripts, ES-module loading, font preconnect, critical CSS, editor styles. |
| `security.php` | Head cleanup, version stripping, XML-RPC off, security headers, nonce-verified newsletter + contact AJAX handlers, honeypot. |
| `custom-post-types.php` | `service` (+ `service_cat` taxonomy) and `testimonial` CPTs; rewrite flush on activation. |
| `template-functions.php` | SEO meta (OG/Twitter), JSON-LD schema, breadcrumbs, body classes, reading time, pagination. |

### `/inc/acf` — fields & rendering
| File | Purpose |
|---|---|
| `acf-init.php` | ACF detection, Local JSON paths, Options page registration, field loading, "install PRO" admin notice. |
| `field-helpers.php` | Compact factory functions for building ACF field arrays. |
| `fields-options.php` | The global **Theme Settings** field group. |
| `fields-page-builder.php` | The **Page Sections** flexible-content group (15 layouts). |
| `page-builder.php` | `amber_render_sections()` engine, `amber_sub()` reader, `amber_section_attrs()`, demo-mode fallback. |

### `/template-parts`
- `sections/section-*.php` — one file per builder layout (15 total).
- `components/section-header.php` — shared eyebrow/heading/subheading block.
- `components/card-post.php` — blog post card.
- `components/content-none.php` — empty-state for archives/search.

### `/assets`
- `scss/` — source of truth (ITCSS-ish: `settings`, `base`, `components`, `layout`,
  `sections`, `utilities`). Entry: `main.scss`; editor: `editor.scss`.
- `css/` — compiled `main.css` (front-end) and `editor.css` (block editor).
- `js/main.js` + `js/modules/*` — navigation, scroll-animations, counters, accordion,
  logo-slider, forms. Each module is a no-op when its markup is absent.
- `images/placeholder.svg` — fallback used by `amber_image()`.

### Other
- `acf-json/` — live two-way sync target for ACF PRO edits.
- `acf-export/` — pre-built importable ACF JSON (optional).
- `languages/amber.pot` — translation template (regenerate with `wp i18n make-pot`).
- `page-templates/page-full-width.php` — "Full Width (Section Builder)" template.
- `package.json` — dart-sass build scripts.

---

## ⚡ Performance, SEO, Accessibility, Security

- **Performance:** minified CSS, deferred ES-module JS, inline critical CSS, font
  `display=swap` + preconnect, responsive `srcset` images with lazy-loading & async
  decode, animations via IntersectionObserver (one-shot, GPU-friendly).
- **SEO:** semantic landmarks, single H1 per view, descriptive headings, JSON-LD
  (Organization, WebSite, BreadcrumbList, FAQPage), OG/Twitter tags. Defers to
  Yoast/Rank Math automatically.
- **Accessibility:** WCAG-minded — skip link, visible focus, ARIA-correct dropdowns &
  accordion (keyboard + arrow keys), `prefers-reduced-motion` honoured, sufficient
  contrast.
- **Security:** `esc_html/attr/url`, `sanitize_*`, `wp_kses_post`, nonces on all AJAX,
  honeypot spam trap, `DISALLOW_FILE_EDIT`, baseline security headers.

---

## 🌐 Browser support

Modern evergreen browsers (Chrome, Edge, Firefox, Safari) and iOS/Android. Uses CSS
custom properties, `clamp()`, `aspect-ratio`, ES modules and IntersectionObserver.

---

## 📄 License

GNU GPL v2 or later. Bundled icons are hand-authored inline SVGs. Fonts (Inter, Sora)
are licensed under the SIL Open Font License.

See `CHANGELOG.md` for release notes.
