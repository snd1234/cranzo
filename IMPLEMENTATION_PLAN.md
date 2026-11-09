# Implementation plan

This plan continues the existing Laravel 12 app. It does not create a second demo shop and it does not replace the 278-product catalogue.

Stage 1 (this audit) is done. The documents are `PROJECT_AUDIT.md`, `MODULE_STATUS.md`, `DATABASE_AUDIT.md`, and this file. No shop features were built during the audit.

## Decisions already made

These follow from the code and the dump. They can be changed before Stage 2 if you disagree.

1. **Keep legacy tables and ids.** New structure is added with migrations. `product.id`, `orders.order_id`, and image filenames stay. Eloquent models will be corrected to those names.
2. **One category tree.** `category`, `sub_category`, and `ternary_category` become one `categories` table with `parent_id`, after a copy of the existing rows. The old tables stay until the admin and storefront read the new tree and the row mapping is checked.
3. **Do not invent vehicle fitment.** Titles that mention Hyundai or Hero are search text only. A product is compatible only when a pivot row exists.
4. **Gateway and courier credentials stay empty.** Razorpay and Shiprocket get a driver interface and a `manual` / `log` driver so checkout and shipment screens work without live keys.
5. **Public theme.** Replace the IGB header, footer, and homepage with a Cranzo automotive layout. Reuse the current Bootstrap/admin shell and the existing `public/front` CSS where it still fits. Do not import `NewShop`, `eventm`, `patient-portal`, or `ADS` into Laravel.
6. **Blogs.** Keep the `blogs` table and admin, and stop publishing the Gulf Bio sample posts on the Cranzo homepage until you replace them.
7. **Money.** New calculations use `decimal`. Historical `payments` strings are read-only.
8. **Authorization.** Start with middleware plus policies on orders, products, and customers. Add `spatie/laravel-permission` only when the role matrix in Stage 3 needs more than a few policies. That is the one new dependency expected early. Excel export, PDF invoices, and image resizing wait until those modules are built, and only if Laravel cannot cover them cleanly.

## What will not be done without an explicit yes

- `migrate:fresh`, dropping tables, or deleting order and product rows.
- Authenticating the legacy MD5 `admin` account.
- Putting real Razorpay, Shiprocket, SMTP, or SMS secrets into the repo.
- Committing `cranzo_dev.sql` or rotating passwords on your behalf. You should rotate the Laravel admin passwords and remove the dump from any remote you have pushed.

## Stage 2 — Schema alignment

Goal: a migrated database that matches the models, without losing rows.

- Repair `.env.example` so secrets are blank placeholders. Point the documented database name at `cranzo_dev` for local XAMPP.
- Fix models: `Product`, `ProductImage` (`product_images`, `image_id`), `Orders` (`order_id`), `OrderDetail`, `Address` (`address_id`), `State` (`state_id`), `User` (real columns). Remove references to `Products` and `Content`.
- Add primary keys and indexes listed in `DATABASE_AUDIT.md` only after a duplicate check.
- Add nullable `slug`, `hsn_code`, `gst_rate`, `brand_id`, `meta_title`, `meta_description` on products. Backfill unique slugs from titles.
- Register form request classes and stop state changes on GET.
- Fix route names (`home` duplicated, `admin.users`, guest redirects). Throttle `/system-auth/login`.
- Remove the full-catalogue load from `BaseController`. Share only active categories and subcategories.
- Stop `AjaxController` from importing missing packages. Disable the newsletter insert until a `newsletter_subscribers` table exists.
- Tests: sqlite schema for category/product smoke tests, or skip with a clear message. `php artisan test` must pass before Stage 3.

Exit: product list and category admin read and write against the real tables. Public product page shows title, both prices, and images without a SQL error.

## Stage 3 — Accounts and permissions

- Customer register, login, logout, email verification, forgot password. Passwords hashed by Laravel. Throttle login and reset.
- Profile: name, mobile, email.
- Addresses: multiple rows, default flag, pincode as `char(6)`, state as `state_id`, optional GSTIN for invoices.
- Admin roles: Super Admin, Admin, Product Manager, Order Manager, Inventory Manager, Customer Support, Content Manager. Policies on product write, order status, customer view, and settings.
- Inactive admins cannot log in.
- Seed one local super admin from env vars `ADMIN_EMAIL` and `ADMIN_PASSWORD`, never from a hard-coded password in the seeder.

Exit: a customer cannot open `/system-auth/dashboard`. An order manager cannot edit products. Feature tests cover registration, login, and a forbidden admin action.

## Stage 4 — Catalogue management

- Finish product create, edit, archive. Validate SKU uniqueness, prices (`discounted_price <= actual_price`), and status.
- Multiple images on the `public` disk, MIME and extension allow-list (jpeg, png, webp), no svg or php. Generate a thumbnail. Keep legacy filenames readable.
- Brands CRUD.
- Category tree with unlimited depth in the admin, seeded with the car and bike groups from the brief. Map current 24 categories and 42 subcategories into that tree and show the mapping in `MODULE_STATUS.md`.
- Colour stock remains `product_details` until variants exist. Then variants (colour, and size where the product needs it) own SKU, price override, and quantity.
- Specifications as name/value rows. Highlights stay as sanitized HTML.
- CSV import and export of products using a queued job once the column map is stable. First version can be a synchronous admin upload if the file is small.

Exit: an admin can add a product with two images and see it on `/products`. A bad upload is rejected.

## Stage 5 — Vehicles

- Tables: `vehicle_makes`, `vehicle_models`, `vehicle_variants`, years, and `product_vehicle` pivot. `type` is `car` or `bike`.
- Admin CRUD and a screen to attach many vehicles to one product.
- Seed makes only: Maruti Suzuki, Hyundai, Tata, Mahindra, Toyota, Honda, Royal Enfield, Bajaj, TVS, Yamaha, KTM, Hero. Models stay empty until you supply them. No scraped catalogue.
- Storefront dependent selects. “Unknown fitment” banner when the pivot is empty. Never show “Fits your vehicle” without a row.

Exit: feature test that a product with no pivot is not listed as compatible.

## Stage 6 — Homepage and navigation

- Announcement bar, logo, account, wishlist count, cart count.
- Car and bike mega menus from the category tree.
- Admin-managed banners, featured categories, brand strip, and homepage section order.
- Sections for featured, new, bestsellers, car accessories, bike accessories, offers, testimonials, and guides. Each section queries real flags (`featured_item`, `created_at`, category, or an explicit admin flag). No hardcoded product arrays.
- Footer links from CMS pages created in Stage 13 can be stubbed with routes that 404 cleanly until then, then wired.
- Newsletter form writes to `newsletter_subscribers`.

Exit: homepage on a desktop and a 390px viewport shows live categories and products, with empty states when a section has no rows.

## Stage 7 — Listing, search, product page

- Category and search pages, grid/list, pagination.
- Filters that hit the database: price, brand, category, availability, discount (derived), rating, attributes, vehicle.
- Sort. Clear filters. AJAX that returns the same query the first paint used.
- Search on title, SKU, brand, part number, make, and model, plus a small suggestion endpoint.
- Product page: gallery, prices, GST label, stock, variants, specifications, compatibility, quantity, related products. Wishlist and cart buttons call Stage 8 routes.
- Recently viewed in the session.
- Compare can wait until listing and cart are correct if it risks delaying checkout. It is still required before the final delivery.

Exit: filters and search have feature tests. Listing does not load every product into memory.

## Stage 8 — Wishlist and cart

- Guest cart in `carts.session_id`. Customer cart in `carts.user_id`. Merge on login.
- Add, update, remove. Server checks variant stock inside a transaction.
- Coupon evaluation service, still without a full promotions UI if Stage 4 is done and coupons land here: percent, fixed, category, product, minimum order, max discount, usage limits, dates, free shipping, first order.
- Totals: subtotal, discount, GST by product rate, shipping (flat rules from settings), grand total. The browser never supplies the price.

Exit: tests for overselling, invalid coupons, and guest-cart merge.

## Stage 9 — Checkout and orders

- Guest and account checkout. Saved addresses. Billing same as shipping, or separate. GSTIN. Order note. Terms checkbox.
- Create order and lines in one transaction. Prices copied from the database at that moment. Stock reserved or decremented according to the payment method (COD decrements when the order is accepted; online decrements when payment is verified).
- Status machine: Pending, Confirmed, Processing, Packed, Shipped, Out for Delivery, Delivered, Cancelled, Return Requested, Returned, Refunded. Illegal jumps rejected. History row on every change.
- Customer can view own orders only.

Exit: tests for order ownership, duplicate submit, and negative stock.

## Stage 10 — Payments

- `PaymentGateway` interface. `CodGateway` marks the order confirmed and stores a transaction row.
- `RazorpayGateway` creates an order only when `RAZORPAY_KEY_ID` and `RAZORPAY_KEY_SECRET` are set. Verify the checkout signature and the webhook signature. Idempotency on payment id. Failed and retry states. Refund method that calls the API only when configured, and otherwise records “manual refund required”.
- No card data stored.
- Development mode shows a clear banner and can simulate success only when `PAYMENT_FAKE=true` and `APP_ENV=local`.

Exit: signature failure does not mark the order paid. A second webhook does not double-charge stock.

## Stage 11 — Admin orders and inventory

- Dashboard counts from orders and products: sales today, month, totals, order states, customers, low stock, out of stock, recent orders. Chart from paid orders.
- Order search, status change through the state machine, internal notes, COD accept/reject.
- Stock adjustment with a movement row. Low-stock threshold in settings.
- Restore stock when a cancellation or refund is eligible.

Exit: dashboard numbers match a seeded order fixture. Two concurrent checkouts cannot drive quantity below zero (transaction test).

## Stage 12 — Shipping, returns, refunds

- `ShippingProvider` interface. Manual provider stores courier name, AWB, and status.
- Shiprocket provider reads `SHIPROCKET_EMAIL` and `SHIPROCKET_PASSWORD` and refuses live calls when they are empty.
- Pincode serviceability and rate quote behind the same interface. Admin can set flat shipping and a free-shipping threshold without a courier.
- Customer return request. Admin approves, receives, and refunds. Refund ties to the payment transaction.

Exit: manual shipment is visible on the customer order page. Return of another customer’s order is forbidden.

## Stage 13 — CMS, SEO, mail, reports

- Banners, menus, pages (About, Contact, Privacy, Terms, Shipping, Returns), FAQs, testimonials, blog CRUD with unique slugs.
- Sanitize stored HTML.
- Per-page title, description, canonical, Open Graph. Product and breadcrumb JSON-LD. Sitemap and robots. Redirect table.
- Mailables for the events in the brief, queued on the database queue. Log driver remains the default until SMTP is set.
- Notification channel stubs for SMS and WhatsApp that no-op with a log line when credentials are missing.
- Reports: sales by day, month, year, product, category, brand, customer, stock, payments, returns, coupons. CSV download. Date filter.

Exit: invoice PDF uses the same tax figures as the order lines. Contact and order mail write to the log in tests.

## Stage 14 — Hardening

- Rate limits on login, contact, newsletter, search, and checkout.
- Mass-assignment review. Upload tests. Production `APP_DEBUG=false` documented.
- Eager-load audit on homepage, listing, and admin orders.
- Cache only global catalogue fragments, not carts or orders.
- `php artisan test`, `route:list`, and `migrate:status`.
- Browser pass on home, listing, product, cart, checkout, and admin product/order at desktop and mobile widths.
- Update `MODULE_STATUS.md` after each stage, not only at the end.
- Rewrite `README.md` (the current file is not plain text) with install steps, queue worker, scheduler, admin bootstrap, payment and courier env vars, and a warning not to commit secrets.

## Order of work inside each stage

1. Read the current code path.
2. Change only what that stage needs.
3. Migrate forward, never fresh, against a copy of `cranzo_dev` when the change touches data.
4. Run the tests for that stage.
5. Update `MODULE_STATUS.md`.
6. Stop if a migration would delete or rewrite historical orders or products, and ask first.

## Credentials you will need later

Not required to build Stages 2–9 or COD.

| Variable | Used for |
| --- | --- |
| `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`, `RAZORPAY_WEBHOOK_SECRET` | Online payments |
| `SHIPROCKET_EMAIL`, `SHIPROCKET_PASSWORD` | Live courier booking |
| `MAIL_*` | Real email instead of the log |
| `ADMIN_EMAIL`, `ADMIN_PASSWORD` | First super admin on a new database |
| Store GSTIN, legal name, state code | Invoices, stored in settings, not in git |

## First coding step after this plan

Stage 2, starting with model and route repairs that make the existing catalogue and category admin safe to use. No new payment or theme work before those queries succeed.
