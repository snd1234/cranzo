# Product module audit

Inspected on 10 Nov 2026 against the live `cranzo_dev` database and the admin routes under `/system-auth`.

## Tables that exist

| Table | Rows | Role |
| --- | --- | --- |
| `product` | 277 | Catalogue item. Primary key `id` is auto-increment. |
| `product_details` | 279 rows for 274 products | Color and stock. `product_details_id` is not auto-increment and has no primary key. |
| `product_images` | 472 | Image filenames. `image_id` is not auto-increment and has no primary key. |
| `colors` | 13 | `color_id`, `color_title`, `color_code`, `color_status`. |
| `ternary_category` | 2 | Optional third level. Every current product has `ternary_category_id` NULL. |
| `category` | 3 | Parent of `sub_category`. |
| `sub_category` | 41 | `category_id`. Some rows point at category ids that no longer exist. |
| `reviews` | present | Not part of product create/edit. |

## Tables that do not exist

`products`, `product_category`, `product_sub_category`, `product_image`, `product_catalogs`, `brands`, `product_attributes`, `product_variants`, specification tables, vehicle-compatibility tables, and `seo_contents` are not in this database. There is no `parent_id` on `product`, so associated or child products are not part of the stored catalogue.

## `product` columns

`id`, `category_id`, `sub_category_id`, `ternary_category_id`, `product_title`, `slug` (unique, added for the shop), `description`, `product_highlights`, `sku_id`, `actual_price`, `discounted_price`, `discount_rate`, `length`, `width`, `height`, `m_unit`, `featured_item`, `cod_available`, `status`, `created_at`, `created_by`, `updated_at`, `update_by`, `meta_title`, `meta_keywords`, `meta_description`.

`description` and `product_highlights` are not nullable. `sku_id` is not unique: 277 products use 263 SKUs. `discount_rate` is a `decimal(5,2)` and is not reliable on older rows. There is no GST, banner, brand, sort-order, or short-description column. Highlights are the short content field. SEO is stored on the product row, not in a separate SEO table.

## Images and stock

Image rows store a filename in `image` and `thumbnail`, plus `product_id`, `product_details_id`, and `created_date`. The shop reads files from `public/uploads/products`. `config/uploads.php` has an empty `UPLOADS_URL`, so new files must use that same public directory or the storefront will not see them.

Most products have one `product_details` row. A few have two or three color/stock rows. Stock is `product_quantity`. There is no separate stock-status column.

## What the admin code was doing

`/system-auth` product routes are on `AdminController`. `ProductController` targets `product_category` and `product_sub_category`, which are not real tables here, and is not used by these routes.

- Add Product validated the wrong fields, did not insert a row, and returned the form again.
- The add and edit Blade files belong to the old laboratory template. They post to `/admin/...`, expect `title` or `name`, and include catalog and `seo_contents` fields that this database does not have.
- Edit was then reduced to slug and meta fields only, so price, category, stock, and images could not be changed.
- Delete was an empty method.
- `ProductImage` points at `product_image`, primary key `id`, and columns that do not exist.
- There is no admin view page.

## Schema change required for old slug redirects

Changing a product slug needs a place to remember the previous slug. No redirect table exists. A new `product_slug_redirects` table (`slug` unique, `product_id`) is the smallest addition that can issue a 301 without altering existing product rows. No catalogue tables are dropped or rebuilt.
