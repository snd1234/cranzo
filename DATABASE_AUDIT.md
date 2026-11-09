# Database audit

Source: `cranzo_dev.sql`, database name `cranzo_dev`, MariaDB 10.4.32, dumped 29 March 2026. Laravel migrations under `database/migrations` do not create the shop tables. This document describes the dump, which is the only complete schema in the repo.

Do not run destructive migrations against this database. Several fixes below are additive (add primary keys, indexes, decimal columns) and need a backup first because existing rows violate a clean model.

## Row counts observed in the dump

| Table | Approx. rows | Business value |
| --- | --- | --- |
| `product` | 278 (auto increment 279) | Keep. Real Cranzo catalogue. |
| `product_details` | 279 | Keep. Colour and quantity per product. |
| `product_images` | hundreds (last `image_id` 480) | Keep. Filenames only; files may live outside this repo. |
| `category` | 24 (auto increment 30) | Keep. |
| `sub_category` | 42 (auto increment 43) | Keep. |
| `colors` | 14 | Keep, then clean invalid hex codes. |
| `orders` | 60 (`order_id` 7–66, auto increment 67) | Keep as history. |
| `order_details` | 67 lines | Keep. |
| `payments` | 60 | Keep. Amounts are strings. |
| `address` | 13 | Personal data. Keep only in a private database. |
| `state` | 36 | Keep as Indian state list. Names are uppercase and include old spellings (ORISSA, PONDICHERRY). |
| `ternary_category` | 2 | Low value. No key. |
| `reviews` | 0 | Empty shell. |
| `contact_us` | 2 | Keep structure. Contains personal data. |
| `blogs` | 12 | Gulf Bio sample content, not Cranzo buying guides. |
| `admins` | 2 | Laravel admins. Rotate passwords. |
| `admin` | 1 | Legacy MD5 login. Do not use. |
| `users` | 0 inserts | Accounts that orders point at are gone. |

## Engine and character set

Shop tables (`category`, `product`, `orders`, `address`, and the rest of the 2019–2020 catalogue) are `InnoDB`, `latin1_swedish_ci`. Laravel tables (`admins`, `users`, `blogs`, `jobs`, `sessions`) are `utf8mb4`. New tables should be `utf8mb4` / `utf8mb4_unicode_ci`. Converting legacy tables is a separate, backed-up change because product HTML may contain bytes that are not valid UTF-8.

There are **no foreign keys** anywhere in the dump.

## Table catalogue

### `users`

Laravel-shaped, then altered. Columns: `id`, `username`, `email` (unique), `mobile_number`, `first_name`, `middle_name`, `last_name`, `email_verified_at`, `password`, `remember_token`, timestamps.

Not in the table: `name` (what `App\Models\User` expects), GSTIN, status, last login.

The migration that created this table used `name` only. The extra profile columns were added outside migrations. Orders still reference user ids that have no row.

### `admins` and `admin`

`admins`: `id`, `name`, `email` unique, `password` (bcrypt), `mobile_number`, `Address`, `role` (comment: 1 Admin, 2 Editor, 3 Viewer), `status`, timestamps. Migration does not create `mobile_number`, `Address`, `role`, or `status`.

`admin`: legacy CodeIgniter login. `email_id`, MD5 `password`, `contact_number`, `status`, `created_date`. Leave unused. Drop only after confirming nothing else reads it.

### `category`

`id`, `name`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`.

No slug, description, image, sort order, parent id, or vehicle type (car vs bike). Top-level names mix departments (“Car Accessories”, “Bike Accessories”) with product types (“Car Horn”, “Bike lock”). A later design should add `parent_id` and a `kind` column rather than inventing a second category table.

### `sub_category`

`id`, `category_id`, `name`, `status`, audit columns. No slug. Duplicate names exist (“Bike Cover” twice, “Jumper Cable” twice). Global unique validation in the new admin would reject those historical duplicates if they were re-saved under the same name.

### `ternary_category`

`ternary_id`, `ternary_title`, `sub_category_id`, `category_id`, `status`. No primary key and no auto increment in the dump. Two sample rows. Fold this into `parent_id` on categories instead of keeping a third table.

### `product`

`id`, `category_id`, `sub_category_id`, `ternary_category_id`, `product_title`, `description` (HTML), `product_highlights` (HTML), `sku_id`, `actual_price` float(10,2), `discounted_price` float(10,2), `discount_rate` decimal(5,2), `length`, `width`, `height` varchar, `m_unit`, `featured_item`, `cod_available`, `status`, `created_at`, `created_by`, `updated_at`, `update_by`.

Gaps versus the target shop: slug, brand, HSN, GST rate, stock at SKU level (stock is on `product_details`), weight for shipping, video, warranty, part number, meta title, soft delete. `sku_id` is not unique in the schema. `update_by` is misspelled relative to `updated_by` on other tables. Prices must move to decimal; do not trust `discount_rate` (see project audit).

No index on `category_id`, `sub_category_id`, `sku_id`, or `status`.

### `colors`

`color_id`, `color_title`, `color_code`, `color_status`. Several codes are not hex (`###ferreerer`, `1456`, `############`).

### `product_details`

`product_details_id`, `product_id`, `color_id`, `product_quantity`. No primary key in the dump. This is the only stock record. There is no warehouse, reservation, or movement table. One row per product in the sample, quantity 100.

### `product_images`

`image_id`, `product_details_id`, `product_id`, `thumbnail`, `image`, `created_date`. No primary key in the dump. No alt text, sort order, or `status`. The public controller filters `status = 1` and will fail. The Eloquent model points at `product_image.image_path`.

### `orders`

Primary key `order_id`. Columns: `session_id`, `order_number`, `user_id`, `address_id`, `order_status` (1 pending, 2 confirmed), `payment_status`, `delivery_status` (1 pending, 2 in progress, 3 delivered), `booking_date`, `booking_type` (1 online, 2 COD), `delivery_date`.

No totals on the order header (totals live only on `payments`). No GST split, shipping amount, coupon, invoice number, or status history. `order_number` is not unique in the schema. Eloquent expects `id`.

### `order_details`

`id`, `order_id`, `user_id`, `product_id`, `product_title`, `image`, `order_quantity`, `amount_per_item` varchar(20). Snapshot of title and image is useful. Price is not decimal. No tax, SKU, or variant id.

### `payments`

`payment_id`, `order_id`, `total_amount` varchar, `amount_recieved` varchar (spelling), `discount_percent`, `payment_status`, `created_date`. No primary key in the dump. No gateway payment id, signature, method, or currency. Cannot represent partial refunds or retries.

### `address`

Primary key `address_id`. `user_id`, `name`, `mobile_number`, `address`, `pincode` int(6), `locality`, `city`, `state` varchar, `address_type`, `default_type`, `created_date`.

`pincode` as INT drops leading zeros and cannot store a 6-digit code that must stay text. `state` sometimes stores a state name and sometimes a numeric id (`'9'`, `'15'`). Eloquent expects `id` and `state_id`. No GSTIN, email, or landmark. No `updated_at`.

### `state`

`state_id`, `state_name`, `status`. No primary key in the dump. No GST state code column, which is required for CGST/SGST versus IGST.

### `reviews`

`review_id`, `product_id`, `user_id`, `rating`, `review_title`, `comments`, `status`, `created_date`. Empty. No primary key. No verified-purchase flag.

### `contact_us`

Matches the Ajax insert: `name`, `email`, `phone`, `message`, `ip_address`, timestamps. No admin UI.

### `blogs`

Live columns far exceed the migration (`id`, timestamps only): `title`, `slug`, `short_description`, `content`, `image`, `type` (1 blog, 2 news, 3 webinar, 4 events), `status`, `meta_title`, `meta_description`, `start_date`, `seo_id`. Slug is not unique.

### Framework tables

`cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `password_reset_tokens`, `migrations`. These match a normal Laravel 12 install. Sessions in the dump include a live admin login payload; treat the file as secret.

### Tables the code expects that are absent

`pages`, `partners`, `enquiry`, `product_image` (singular), and any SEO table backing `SeoContent`.

## Relationship map (logical, not enforced)

```
category 1—* sub_category 1—* product
product 1—* product_details *—1 colors
product 1—* product_images
product_details 1—* product_images
users 1—* address
users 1—* orders *—1 address
orders 1—* order_details *—1 product
orders 1—* payments
users 1—* reviews *—1 product
state (intended parent of address, but address.state is a string)
```

Broken links already in the data:

- Every `orders.user_id` points at a missing `users` row.
- `address.state` is not a reliable foreign key to `state.state_id`.
- `product.ternary_category_id` is null on the sampled products while `ternary_category` has only two rows.
- `product_images.product_details_id` is not constrained.

## Index and key gaps

Present: primary keys on `address`, `admin`, `admins.email`, `blogs`, `category`, `colors`, `contact_us`, `orders.order_id`, `order_details.id`, `product.id`, `sub_category.id`, `users.id` + unique email, plus Laravel job/session indexes.

Missing primary keys: `payments`, `product_details`, `product_images`, `reviews`, `state`, `ternary_category`.

Missing secondary indexes for the shop: category and subcategory on `product`, `sku_id`, `orders.user_id`, `orders.order_number`, `order_details.order_id`, `order_details.product_id`, `payments.order_id`, `product_images.product_id`, `address.user_id`, `sub_category.category_id`.

## Target entities that do not exist yet

Add these with new migrations after the legacy keys are mapped. Do not clone a parallel `products` table and abandon 278 rows.

- Brands.
- Category closure or `parent_id` (replaces a growing set of category tables).
- Product slug and SEO columns on `product`.
- Vehicle makes, models, variants, years, and a product-vehicle pivot. Empty until an admin enters fitment. Never infer compatibility from the title.
- `stock_movements`.
- `carts`, `cart_items`.
- `wishlists`.
- Coupons and redemptions.
- Normalized `orders` money columns and `order_status_histories`.
- `payment_transactions` (gateway ids, status, payload hash, idempotency). Keep old `payments` as legacy.
- Shipments, tracking events.
- Returns and refunds.
- Banners, menus, CMS pages, FAQs, testimonials.
- Newsletter subscribers (do not reuse the missing `enquiry` shape).
- GST rates, tax lines on order items, invoice sequences.
- Settings (shipping threshold, store GSTIN, gateway mode).
- Notifications and admin activity logs.
- Roles and permissions tables if a package is introduced. The current `admins.role` integer can be migrated into that.

## Monetary and tax rules for the new columns

- Store money as `decimal(12,2)`, currency INR.
- Store GST rate on the product (percent, `decimal(5,2)`), not a single shop-wide rate.
- Store CGST, SGST, and IGST amounts on each order line at the time of checkout so later rate changes do not rewrite history.
- Compare billing state GST code with the store state to choose IGST versus CGST+SGST.
- Keep `actual_price` and `discounted_price` as the source of the selling price. Backfill a real percentage only where `actual_price > 0` and the stored `discount_rate` is consistent with `(actual - discounted) / actual * 100` within one rupee. Otherwise leave the derived percent null and keep both prices.

## Safe migration order

1. Backup `cranzo_dev`.
2. Add primary keys and indexes to legacy tables with a migration that checks existing indexes first. Repair auto increment where the column is already unique.
3. Add nullable columns the application needs (`product.slug`, GST, HSN, `updated_by` aligned with `update_by`) and backfill slugs without changing ids.
4. Point Eloquent at the real table and key names before any new write path runs.
5. Create the missing commerce tables (cart onward).
6. Do not `migrate:fresh`. Do not drop `product`, `orders`, or `order_details`.

## Personal data

`address`, `contact_us`, `orders`, and the admin session rows identify real people. The dump must stay out of git and out of any public web root. This audit intentionally does not repeat names, phone numbers, emails, or password hashes.
