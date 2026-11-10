# Product module implementation

Add, edit, and view now use the live catalogue tables. The old laboratory product form was not wired to this database, so the admin screens were rebuilt around the columns that actually exist.

## Behaviour

- Add Product saves the product, one color/stock row, optional images, and SEO fields.
- Edit Product loads the current category, subcategory, ternary category, prices, highlights, description, stock rows, images, and SEO fields.
- Existing images and stock rows stay in place unless the admin checks Remove or changes a quantity.
- A new color is added only when that color is not already on the product. If it is already present, the quantity is updated.
- Delete marks `status` as inactive. It does not remove images, stock, or orders.
- View Product is read-only and links to Edit and, for an active product, the public product page.
- Subcategory options follow the selected category. Ternary options follow the subcategory. The server rejects a subcategory or ternary category from a different parent.
- Slug is generated from the title when left blank. A changed slug is stored in `product_slug_redirects`, and the old public URL returns a 301.
- Description and highlights keep only `p`, `br`, `ul`, `ol`, `li`, `strong`, `em`, `h2`, and `h3`. Script blocks are removed.
- Saving recalculates `discount_rate` as the percentage difference between regular and selling price.
- Images are stored as generated filenames in `public/uploads/products`, which is the directory the shop already uses. `UPLOADS_URL` is empty, so the Laravel public disk would not appear on the storefront.

## Schema change

`product_slug_redirects` was added so an old slug can redirect to the current product. No existing table was dropped or rebuilt.

## Not available in this database

Brand, parent/associated products, banner, PDF catalogs, GST, variants, specifications, and vehicle compatibility have no tables or columns. They are not shown as if they were saved.

## Verification

A temporary product was created with an image, updated, given a second image, had one image removed, and had its slug changed. The checks confirmed the new title, 20% discount, stock quantity 9, both images, one image after removal, the old slug redirect, and removal of script content. The temporary product, stock, images, redirect, and file were then deleted. The public product URL still returns 200, and a logged-out visit to Add Product now redirects to the admin login instead of returning an error.

## Files

- `app/Services/ProductCatalog.php`
- `app/Http/Requests/Admin/ProductRequest.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/HomeController.php`
- `app/Models/Product.php`, `ProductDetail.php`, `ProductImage.php`
- `resources/views/admin/add_product.blade.php`
- `resources/views/admin/edit_product.blade.php`
- `resources/views/admin/view_product.blade.php`
- `resources/views/admin/partials/product_fields.blade.php`
- `resources/views/admin/product.blade.php`
- `database/migrations/2026_11_10_133000_create_product_slug_redirects_table.php`
- `bootstrap/app.php` guest redirect for `/system-auth`
