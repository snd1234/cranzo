# Ecommerce module audit

Inspected on 10 Nov 2026 against the Laravel 12 app and the live `cranzo_dev` database. No tables were wiped.

## Working today

- Catalogue: categories, subcategories, products, colours, images, slugs, SEO, sitemap.
- Storefront listing, search, sort, product page, session cart, guest cash-on-delivery checkout.
- Admin login, categories, products, colours, banners, CMS pages, FAQ, newsletter, contact form storage.
- Checkout writes `orders`, `order_details`, `address`, and `payments`, and reduces `product_details.product_quantity` once.

## Missing or not safe to call complete

Customer accounts, wishlist, reviews on the storefront, variant picking, coupons, GST, invoices, online payment, shipping rates, returns workflow, stock reservations, reports, abandoned carts, contact inbox, and staff permissions. The header account and wishlist links do not open those features. `reviews` has rows-ready columns but no primary key and no screens. `users` has 7 accounts and the matching login routes are commented out. `AuthController` still expects a `name` column that `users` does not have.

## This implementation pass

Customer registration, login, verification, password reset, dashboard, profile, saved addresses, order history, cancellation of current checkout orders only, reorder, and guest-cart cleanup on login. Later phases in `IMPLEMENTATION_PLAN.md` are not marked complete.
