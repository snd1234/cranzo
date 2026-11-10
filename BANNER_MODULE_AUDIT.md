# Banner module audit

Inspected on 10 Nov 2026 against `cranzo_dev` and the current storefront.

## What exists

The homepage hero is a single static file, `public/front/images/banner.jpg`, linked to `/products`. There is no banner model, controller, route, or admin screen.

`home_sliders` is not in the database. Neither is any other banner table. The tables present are the shop catalogue, orders, users, blogs, and sessions.

`public/front/js/swiper-bundle.min.js` is in the project, but the live storefront layout does not load it. The current homepage has no slider script. `NewShop` has an Owl Carousel, and that folder is not the Laravel shop.

`config/uploads.php` sets `UPLOADS_URL` to an empty value. Product images that the shop can display are stored under `public/uploads/products`.

## Decision

Add a `banners` table and admin CRUD. Do not invent a second slider library. The homepage slider is a small script in the existing Tailwind page. When no banner is active, the current `banner.jpg` remains so the homepage does not go blank.
