# Cranzo project audit

Audit date: 9 November 2026. Scope is the repository at `C:\xampp\htdocs\cranzo`. No application code was changed for this audit. Findings come from source, migrations, Blade views, `composer.lock`, and the MariaDB dump `cranzo_dev.sql` (generated 29 March 2026, MariaDB 10.4.32, PHP 8.2.12).

## Verdict

This is a Laravel 12 application with a partial admin for the original Cranzo catalogue, sitting on a mixed database. The public website is still the Integrated Gulf Biosystems (IGB) corporate template. It is not a working car and bike accessories shop.

The original CodeIgniter application is not in this repository. The recoverable business data is the SQL dump: about 278 products, 24 categories, 42 subcategories, 60 historical orders, payments, addresses, Indian states, colours, and product images. Customer account rows are not in that dump.

Do not drop or rebuild these tables until a reviewed data migration exists. Several models do not match the live column names, so “fixing” them blindly would break the catalogue.

## Stack

| Item | Finding |
| --- | --- |
| Framework | `laravel/framework` v12.61.1 (`composer.lock`) |
| PHP constraint | `^8.2` |
| Database target | MySQL/MariaDB. Example config uses MySQL. Dump is MariaDB 10.4. |
| Frontend build | Vite + Tailwind-style utility classes on the public layout. Admin uses a Bootstrap-style layout. |
| HTTP client packages | None beyond Laravel. No Razorpay, Shiprocket, Excel, PDF, image, permission, or Scout packages. |
| Queue / cache / session | Database drivers in `.env.example`. Mail mailer is `log`. |
| Tests | PHPUnit 11. Two feature tests expect `/` and `/about-us` to return 200 against an in-memory SQLite database that does not contain the Cranzo tables. |
| API | No `routes/api.php`. Routing is web-only, plus `/up`. |

## What is actually in the repository

Laravel application code lives under `app/`, `routes/web.php`, `resources/views`, `database/migrations`, and `public/front`.

Unrelated or leftover trees that are not the Laravel shop:

- `NewShop/` — standalone PHP shop template (`index.php`, `cart.php`, `checkout.php`). Not wired to Laravel or to the Cranzo schema.
- `ADS/`, `eventm/`, `patient-portal/` — static HTML/CSS templates.
- `cranzo images/` — loose product photos.
- `cranzo_dev.sql` — full database dump, including personal data and password hashes.
- `cranzo details.php`, `envetm` — stray files.

There is no `application/` CodeIgniter tree, no `controllers` from CI, and no payment callback code from the old shop.

## Architecture today

```
Public browser
  -> routes/web.php
  -> HomeController / AjaxController (mostly DB::table)
  -> resources/views/layout/app.blade.php (IGB branding)

Admin browser
  -> /system-auth/*
  -> AdminController (auth:admin)
  -> resources/views/admin/* (Cranzo Admin)
  -> Eloquent models that often disagree with table columns
```

There are no service classes, form requests, policies, gates, jobs, notifications, or events. `AdminController` is the only place that writes catalogue data, and product create/update/delete are unfinished.

`BaseController` runs on every admin and home request. It loads every active category, every subcategory, and every product in those subcategories, then shares them with every view. That will not scale and it queries tables that tests and a fresh `migrate` do not create.

`CategoryServiceProvider` and `ViewServiceProvider` exist but are not registered in `bootstrap/providers.php`. Registering `CategoryServiceProvider` as written would fail: it references a missing `ProductCategory` model.

## Public site

Routes in `routes/web.php`:

| Route | Name | Status |
| --- | --- | --- |
| `GET /` | `home` | Renders IGB homepage from `blogs` and `category`. |
| `GET /about-us` | `about-us` | Looks for a `pages` row. That table is not in the dump. |
| `GET /our-partners` | `our-partners` | Looks for `partners`. Not in the dump. |
| `GET /events`, `/news-room` | — | Reads `blogs` by `type`. Content is Gulf Bio, not Cranzo. |
| `GET /contact-us` | `contact-us` | Form posts to Ajax. |
| `GET /products` | `products` | Lists `product`. Image query filters `product_images.status`, which does not exist. |
| `GET /product-detail/{slug}` | `product-detail` | `product` has no `slug` column. Lookup also matches `id` and title. |
| `GET /our-solutions/{slug}` | `our-solutions` | `category` has no `slug` column. |
| `GET /blog/{slug}`, `/news/{slug}` | `blog.detail`, `news.detail` | Works only if `blogs.slug` matches. Slugs in the dump are duplicated. |
| `POST /ajax/subscribe-newsletter` | `ajax.subscribe-newsletter` | Inserts into `enquiry`, which does not exist. |
| `POST /ajax/contact-us` | `ajax.contact-us` | Inserts into `contact_us`, which does exist. |
| `GET /home` | `home` | Second route with the same name. Customer login routes are commented out. |

`resources/views/layout/app.blade.php` shows the IGB logo and Gulf Biosystems copy. The homepage slider is static (“Beautiful Nature”, “City Lights”). Blog HTML is printed with `{!! $blog->content !!}`, so stored HTML is executed in the browser.

## Admin

Prefix: `/system-auth`. Guard: `admin` → `App\Models\Admin` → table `admins`. Passwords are hashed with bcrypt on create.

Working enough to keep, after bugfixes:

- Login and logout.
- Admin user list, add, edit, view. Delete marks `status = 0`.
- Category list, add, edit, deactivate.
- Subcategory list, add, edit, deactivate.
- Colour list, add, edit.
- Product list query (read).
- Order list and order detail queries (will fail until models match `orders` and `users`).

Not implemented inside `AdminController`:

- `addProduct()` validates and then redisplays the form. It does not insert a product. The unique rule checks `sub_category.name`, not the product title.
- `updateProduct()` and `deleteProduct()` are empty.
- Dashboard is a static view. No sales figures.
- Blog, page, and partner Blade screens exist. Their controller methods are not on the current route file. `PageController` is not routed. Several of those views call route names that do not exist (`admin.ckeditor.upload`, and similar).

Role values `1`, `2`, `3` are stored and never enforced. Every authenticated admin can manage users, categories, and orders.

## Authentication gaps

- Customer `AuthController` exists, but its routes are commented out. It has no registration, email verification, or password reset, despite views under `resources/views/auth/`.
- `User` fillable fields are `name`, `email`, `password`. The dump’s `users` table uses `username`, `first_name`, `middle_name`, `last_name`, `mobile_number`, and has no `name` column.
- Guest redirect in `bootstrap/app.php` sends `/admin/*` to `route('admin.login.form')` and everyone else to `route('login')`. Neither name exists. Admin URLs are `/system-auth/*`, so the `/admin/*` check never matches.
- `App\Http\Middleware\AdminAuth` is unused and redirects to `route('admin.login')`, which also does not exist.
- Login has no `throttle` middleware.
- `GET /system-auth/delete-category/{id}` and `GET /system-auth/delete-user/{id}` change data. Category delete is not even limited to POST/DELETE. A logged-in admin can be tricked into deactivating records through a link.
- `addUser()` redirects to `route('admin.users')`. The registered name is `users`.
- Two routes are both named `home`.
- Successful admin login does not call `session()->regenerate()` explicitly. Laravel’s `attempt()` regenerates the session; logout does not invalidate the session.

## Schema drift (code versus `cranzo_dev.sql`)

These mismatches will throw SQL errors or return the wrong row as soon as the screen is used against the dump:

| Code | Database |
| --- | --- |
| `Orders` primary key `id` | `orders.order_id` |
| `Orders` fillable `booking_date` only, relation `user` | Columns include `session_id`, `address_id`, `payment_status`, `delivery_status`, `booking_type`. Relation method is `User()`. |
| `Address` primary key `id`, fillable `state_id` | Primary key `address_id`. `state` is a varchar, not a foreign key. |
| `State` primary key `id` | `state_id` |
| `ProductImage` table `product_image`, key `id`, columns `image_path`, `status` | Table `product_images`, key `image_id`, columns `thumbnail`, `image`, `product_details_id`, `created_date`. No `status`. |
| `Product` fillable `updated_by` | Column is `update_by`. No `slug`. |
| `SubCategory::products()` | Points at class `Products`, which does not exist. |
| `Page` | Uses `SoftDeletes` and type-hints a missing `Content` class. No `pages` table in the dump. |
| `HomeController` image query `status = 1` | Column does not exist. |
| Migrations for `users` and `blogs` | Do not describe the live tables. Re-running them on a new database will not recreate Cranzo. |

`product.discount_rate` is not reliable. Some rows store a percentage (`30.00` on a 500 → 350 price). Others store a rupee difference (`151.00` on a 500 → 349 price). Checkout must recalculate discount from `actual_price` and `discounted_price`.

Money is `float` on products and `varchar` on `payments.total_amount`, `payments.amount_recieved`, and `order_details.amount_per_item`. New monetary columns should be `decimal(12,2)`.

## Migrations versus the live database

Recorded migrations:

1. `0001_01_01_000000_create_users_table` — Laravel default `users` (`name`, `email`). The dump’s `users` table was altered afterwards and no longer matches this migration. There is no `INSERT` into `users`. Historical `orders.user_id` values (1, 3, 4, 5, 9, 12, 17) have no matching account rows in the dump.
2. Cache tables.
3. Job tables.
4. `2025_11_05_064117_create_admins_table` — only `id`, `name`, `email`, `password`, timestamps. Live `admins` also has `mobile_number`, `Address`, `role`, `status`.
5. `2026_01_17_084955_create_blogs_table` — only `id` and timestamps. Live `blogs` has title, slug, content, image, type, SEO fields.

Catalogue, order, payment, address, colour, state, review, and contact tables have **no Laravel migrations**. They exist only in `cranzo_dev.sql`. A fresh `php artisan migrate` cannot build the shop.

## Security and privacy

Treat these as open issues before any deployment:

1. `cranzo_dev.sql` is in the working tree. It contains customer names, mobile numbers, street addresses, emails, a legacy admin password stored as MD5, and bcrypt hashes for the Laravel `admins` table. It also contains a serialized admin session payload. This file should not be committed, and the exposed passwords should be rotated.
2. `.env.example` contains a real-looking `APP_KEY` and a commented remote database username and password. Example files should have empty secrets. Rotate anything that was ever a live credential.
3. Legacy `admin` table is separate from `admins` and still holds an MD5 password. The Laravel app does not read it, but the hash is a known weak format.
4. `AdminSeeder` creates `admin@example.com` with password `password` if it is run.
5. No policies. Admin role is decorative.
6. No login rate limit.
7. State-changing GET routes.
8. Raw HTML from `blogs.content` and product descriptions.
9. `AjaxController` imports `Stevebauman\Location`, `DataTables`, and a global `Validator` alias. Those packages are not in `composer.json`. The newsletter and contact methods that are routed do not use them, but loading the class file depends on those imports resolving. `use DataTables;` and `use Validator;` will fail if those classes are not aliased.
10. File upload config `config/uploads.php` only reads `UPLOADS_URL`. There is no MIME allow-list, size limit, or private disk for invoices.
11. Session encryption is off in the example env (`SESSION_ENCRYPT=false`). Acceptable for local only.
12. No foreign keys, so order and image rows can point at missing users and products. Several operational tables have no primary key in the dump: `payments`, `product_details`, `product_images`, `reviews`, `state`, `ternary_category`.

CSRF middleware is still the Laravel default. Admin forms that were inspected use `@csrf`. Do not remove `ValidateCsrfToken`.

## Performance

- `BaseController` is an N+1 over the full catalogue on every request, including admin login.
- Product listing loads every product, then one image query per product.
- Admin lists use `->get()` with no pagination.
- No indexes on `product.category_id`, `product.sub_category_id`, `product.sku_id`, `orders.user_id`, `order_details.order_id`, or `product_images.product_id`.
- `blogs.slug` is not unique. Three rows share one slug, so `/blog/{slug}` is ambiguous.
- Homepage and category data are not cached. The unused category provider would cache a missing model.

## Payments, tax, and shipping

The old shop recorded:

- `orders.booking_type`: `1` online, `2` COD. Many rows are null.
- `orders.payment_status` and `payments.payment_status`: comments say 1 initiated, 2 success, 3 failed. Many order rows store `0`.
- `orders.order_status`: comment says 1 pending, 2 confirmed.
- `orders.delivery_status`: 1 pending, 2 in progress, 3 delivered. Dumped orders stay at 1.
- No GST columns, no invoice number, no courier, no AWB, no webhook id, no coupon table.

There is no Razorpay (or any gateway) code and no `.env` keys for one. COD is a flag, not a checkout flow.

## SEO and email

`SeoContent` model and blog meta columns exist. There is no sitemap, `robots.txt` route, canonical helper, Open Graph layout, or product JSON-LD. Public pages do not have unique titles wired from the database.

Mail is configured to the `log` driver. No Mailable classes exist. Password reset tokens table exists; customer reset flow does not.

## Tests and tooling

`tests/Feature/FrontendRoutesTest.php` will not pass against the current controllers: `HomeController` extends `BaseController`, which queries `category` on construct, and PHPUnit forces SQLite `:memory:`. `tests/Feature/ExampleTest.php` still expects the Laravel welcome page on `/`.

`php artisan route:list` and `migrate:status` were not executed against a live database in this audit. The route file was read directly. Do not run `migrate:fresh` against `cranzo_dev`.

## What to preserve

- All rows in `category`, `sub_category`, `product`, `product_details`, `product_images`, `colors`, `orders`, `order_details`, `payments`, `address`, `state`, `contact_us`.
- Image filenames such as `product-1586430984_0.jpg`. Storage must keep serving those paths.
- Admin accounts in `admins`, after a password rotation.
- The category and subcategory admin screens, once route names and validation are corrected.
- Historical order numbers. They are business records even though customer accounts are missing.

## What not to treat as Cranzo

- IGB homepage, logo, and Gulf Bio blog posts (`blogs` types 1–4).
- `NewShop`, `ADS`, `eventm`, and `patient-portal`.
- The legacy `admin` MD5 account. Do not authenticate against it.
- `discount_rate` as a percentage without checking the two price columns.
