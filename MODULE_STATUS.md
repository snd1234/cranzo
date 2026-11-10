# Module status

Status is against the Cranzo car and bike accessories shop described in the migration brief, not against the IGB pages that happen to render. “Partially implemented” means some schema or screen exists and is not safe to call complete.

Evidence is the Laravel source plus `cranzo_dev.sql`. The CodeIgniter application source is not in this repository, so old checkout behaviour is inferred from tables only.

## Summary

| Area | Status |
| --- | --- |
| Laravel 12 shell, admin guard, category and subcategory CRUD | Partially implemented |
| Public Cranzo storefront | Not implemented (IGB template is what renders) |
| Catalogue data (products, images, colours, stock qty) | Present in SQL, admin write path broken |
| Cart, checkout, GST, payments, shipping, returns | Not implemented |
| Customer accounts | Schema only, routes disabled, no rows in the dump |
| Roles beyond a stored integer | Not implemented |
| Automated coverage of shop flows | Not implemented |

## Platform

| Module | Status | Notes |
| --- | --- | --- |
| Laravel 12 / PHP 8.2 app | Completed and working | v12.61.1, PHP constraint `^8.2`. |
| Migrations for framework tables | Implemented but requires correction | `users`, `admins`, and `blogs` migrations do not match the live columns. Catalogue tables have no migrations. |
| Seeders | Implemented but requires correction | `AdminSeeder` uses `admin@example.com` / `password` and omits `role` and `status`, which the live table expects. |
| Config, `.env.example` | Security issue | Example file contains an application key and a commented remote database password. Mail, queue, cache, and session are local-safe defaults only. |
| SQL dump in the repo | Security issue | Personal data and password hashes. Remove from version control after a private backup. |
| Automated tests | Implemented but requires correction | Existing tests do not boot the real schema and do not cover commerce. |

## Identity and access

| Module | Status | Notes |
| --- | --- | --- |
| Admin login / logout | Partially implemented | `auth:admin` on `/system-auth`. No throttle. Logout does not invalidate the session. Redirect targets in `bootstrap/app.php` use route names that are not registered. |
| Admin user CRUD | Partially implemented | Create, edit, deactivate work if the `admins` extra columns exist. Success redirect uses `admin.users`, which is not a route name. Role is saved and ignored. |
| Customer registration, login, verification, password reset | Not implemented | Controller and Blade files exist. Routes are commented out. `User` model does not match `users` columns. Dump has no customer rows. |
| Roles and permissions | Not implemented | Integer `admins.role` only. No policies, gates, or permission package. |
| Activity log | Not implemented | |
| Legacy `admin` table | Security issue | MD5 password. Do not use it for login. |

## Catalogue

| Module | Status | Notes |
| --- | --- | --- |
| Categories | Partially implemented | 24 live rows, including Car Accessories and Bike Accessories. Admin CRUD deactivates instead of hard-deleting. No slug, image, parent tree, or menu flag. Flat list, not the Carorbis hierarchy. Duplicate concepts (a category named “Car Cover” and a subcategory named “Car Cover”). |
| Subcategories | Partially implemented | 42 rows. Admin CRUD works and encrypts the edit id. Unique rule is on `name` globally, so the same subcategory name cannot exist under two parents. `products()` relationship points at a missing `Products` class. |
| Third-level categories | Not implemented | `ternary_category` has two rows and no primary key, model, or screen. |
| Brands | Not implemented | Brand is implied by the “Cranzo” title prefix only. |
| Products (data) | Partially implemented | 277 products with SKU, two prices, dimensions, featured flag, COD flag, HTML description, and unique slugs. No weight, HSN, GST, banner, brand, or parent product columns. |
| Product admin create / edit / delete | Implemented | Add, edit, and read-only view save the live `product`, `product_details`, and `product_images` rows. Delete marks the product inactive and keeps its media. |
| Product images | Partially implemented | Admin uploads go to `public/uploads/products`. Historical filenames are still missing from disk. The image model now uses `product_images`. |
| Homepage banners | Implemented | Admin CRUD for `banners`. The homepage shows up to 5 active banners by display order and falls back to `banner.jpg` when none are active. |
| Colours and per-colour stock | Partially implemented | `colors` plus `product_details.product_quantity`. Admin can edit colours. No stock movement ledger. Quantities in the dump are mostly 100. No reservation or locking. |
| Variants (size and other options) | Not implemented | Colour is the only option, and it is not selectable on the storefront. |
| Attributes and specifications | Not implemented | Highlights are HTML blobs. |
| Vehicle compatibility | Not implemented | Fitment is written into the product title (“For Hyundai Creta”). There are no make, model, year, or variant tables. |
| Import / export | Not implemented | |
| Reviews | Not implemented | Empty `reviews` table, no primary key, no UI. |

## Storefront

| Module | Status | Notes |
| --- | --- | --- |
| Homepage | Implemented but requires correction | Renders, but it is an IGB events/news page with a static slider. Catalogue sections requested for Cranzo are absent. |
| Navigation mega menu | Not implemented | Header is the IGB menu. `BaseController` loads the whole catalogue into `headerCategories` on every request, which is a performance issue. |
| Search and suggestions | Not implemented | |
| Category and search listing, filters, sort | Not implemented | `/products` dumps every active product. `/our-solutions/{slug}` expects a category slug column that does not exist. |
| Product detail | Implemented but requires correction | View exists. Query cannot use `slug`. Images query is invalid. No price, stock, variant, or compatibility logic on the page that matches the brief. |
| Wishlist | Not implemented | |
| Compare | Not implemented | |
| Recently viewed | Not implemented | |
| Cart | Not implemented | No table. `NewShop/cart.php` is a separate template. |
| Checkout | Not implemented | No address book UI, GSTIN, shipping method, or server-side totals. |
| Guest cart merge | Not implemented | |
| Coupons | Not implemented | `payments.discount_percent` is unused in the dump. |

## Orders and money

| Module | Status | Notes |
| --- | --- | --- |
| Historical orders | Partially implemented | 60 orders (`order_id` 7–66) and matching `order_details` and `payments`. Status model is three small integers, not the lifecycle in the brief. Customer users are missing, so admin `with('user')` cannot resolve buyers. Model primary key is wrong (`id` vs `order_id`). |
| Admin order screens | Implemented but requires correction | Routes exist. They will error on the current models and on `user:id,first_name,...` because the relation method is `User()` and the key is `order_id`. |
| Customer order history, cancel, reorder | Not implemented | |
| Invoices and GST | Not implemented | Amounts are float or varchar. No CGST/SGST/IGST, GSTIN, or invoice number. |
| Online payments | Not implemented | No gateway client, signature check, webhook, or idempotency key. |
| COD | Not implemented | `booking_type = 2` and `cod_available` exist as data flags only. |
| Refunds | Not implemented | |
| Returns | Not implemented | |
| Shipping and tracking | Not implemented | `delivery_status` never progresses in the dump. No Shiprocket or AWB columns. |
| Stock deduction on order | Not implemented | Historical flow did not decrement `product_details`. |

## Content, SEO, notifications

| Module | Status | Notes |
| --- | --- | --- |
| Blogs / news / events | Partially implemented | Table and public pages work for Gulf Bio sample content. Admin blog routes are not registered. Slugs are not unique. `{!! !!}` output is an XSS risk if editors are not fully trusted. |
| CMS pages | Implemented | `cms_pages` drives the seven public pages, footer links, and sitemap entries. The old unused `Page` model is not wired to these routes. |
| Partners | Not implemented | View and model exist. Table is not in the dump. Route is guarded with `Schema::hasTable`. |
| Banners and homepage sections | Implemented | Admin CRUD for `banners`. Homepage shows up to 5 active banners. |
| Testimonials | Not implemented | |
| Contact form | Implemented | Saves name, email, phone, subject, message, and IP into `contact_us`. The form is rate limited. Mail is sent only when `CONTACT_NOTIFY_EMAIL` is set. |
| Newsletter | Implemented | Footer AJAX saves a unique email in `newsletter_subscribers`. Campaigns queue only for consented, subscribed addresses. |
| SEO metadata, sitemap, redirects, structured data | Not implemented | `SeoContent` model is unused. Blog meta columns are not rendered as unique document titles. |
| Email templates | Not implemented | `MAIL_MAILER=log`. No mailables. |
| SMS / WhatsApp | Not implemented | |

## Cross-cutting defects already visible

| Issue | Class |
| --- | --- |
| Duplicate route name `home` | Implemented but requires correction |
| GET handlers that deactivate categories and users | Security issue |
| Full-catalogue query in `BaseController` | Performance issue |
| No foreign keys or secondary indexes on catalogue and orders | Performance and data-integrity issue |
| `discount_rate` mixed meaning | Implemented but requires correction |
| Tables without a primary key: `payments`, `product_details`, `product_images`, `reviews`, `state`, `ternary_category` | Implemented but requires correction |
| `AjaxController` imports packages that are not installed | Implemented but requires correction |
| Public product and blog HTML rendered unescaped | Security issue |

## Explicitly out of scope of “already converted”

These were requested and have no table, route, or class to preserve:

Vehicle master data, compatibility, wishlist, cart, coupons, GST engine, Razorpay, Shiprocket, returns, refunds, reports, sitemap, role permissions, queues for mail, image pipeline, and customer notifications.
