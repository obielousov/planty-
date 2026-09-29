# Planty — Flower Shop Landing Page

A landing page for a flower shop, built as a custom WordPress theme, created as a frontend portfolio project. The layout started as hand-coded static HTML, SCSS and vanilla JavaScript and was then rebuilt as a theme: the markup was split into templates, everything that repeats moved into custom post types, the fixed copy moved into the Customizer, and the shop section gained server-side rendering with AJAX pagination.

🌐 Live Demo: _coming soon_

## Features

- **Hero slider** — Swiper carousel with a fraction counter, autoplay that pauses on hover and on keyboard focus, and slides managed from the WordPress admin
- **Deal of the day** — a second slider where every slide carries its own countdown, driven by a `data-deadline` attribute and recomputed from the clock on each tick so it cannot drift
- **Shop with category tabs** — the full ARIA tab pattern (`tablist`, `tab`, `tabpanel`, roving tabindex, arrow, Home and End keys), with products pulled from a custom post type
- **AJAX pagination** — further pages of products load over a custom REST route (`planty/v1/products`), with focus moved to the updated panel and a visually hidden live region announcing the change
- **Entrance animations** — the hero and the header assemble on load through a `loaded` class on `<html>`, the pieces sliding out from behind the header edges instead of floating over them
- **Scroll reveal** — every other section fades in through a single `IntersectionObserver` and one `[data-reveal]` attribute, animating opacity only so nothing on the page shifts
- **Accessibility** — skip link, landmarks, labelled navigation, focus management in the mobile menu and after AJAX pagination, visible focus rings on slider bullets, and full `prefers-reduced-motion` support
- **Content in the admin** — custom post types for hero slides, deal slides, plant slides, products and reviews, a product category taxonomy, and Customizer panels for every fixed text and image
- **Social metadata** — Open Graph and Twitter Card tags with a preview image

## Tech Stack

- **WordPress** — custom theme, no page builder
- **PHP** — templates, custom post types, meta boxes, Customizer settings, a REST route
- **SCSS** — BEM, flat partials compiled into a single stylesheet outside the repository
- **Vanilla JavaScript** — framework-free, one file, one named init function per feature
- **Swiper** — hero, deal, plant and reviews sliders
- **WordPress REST API** — a custom `planty/v1/products` route for shop pagination
- **Assets** — self-hosted Montserrat fonts, SVG icons, JPG and PNG images

## Project Structure

```
.
├── inc/                # Customizer, post types and meta boxes, shop rendering and the REST route
├── template-parts/     # one file per home page section
├── scss/               # flat partials, no subfolders
├── css/                # compiled output — the theme loads style.css
├── js/
│   └── script.js       # sliders, tabs, AJAX pagination, countdowns, animations
├── img/                # images and SVG icons
├── fonts/              # self-hosted Montserrat (woff2)
├── header.php          # head, header and the opening wrapper
├── index.php           # every URL — this is a one page theme
├── footer.php          # footer and the closing markup
└── functions.php       # theme setup, assets, Open Graph
```

## Run Locally

Clone the repository into your WordPress themes folder:

```
cd wp-content/themes
git clone https://github.com/obielousov/planty.git
```

Activate the theme under **Appearance → Themes**, then add a menu under **Appearance → Menus** and assign it to the *Primary* and *Footer* locations.

The theme loads `css/style.css` as it is, and nothing compiles it inside the repository. If you edit anything in `scss/`, build it to `css/style.css` yourself — every partial that uses `toRem()` or `toEm()` needs its own `@use "mixin" as *`.
