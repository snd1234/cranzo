# Security fixes

## Customer accounts

- Customer sign-in is `/login`. Admin sign-in stays at `/system-auth/login`.
- Login, registration, password reset, contact, newsletter, reviews, checkout, profile, password, and address changes are rate limited.
- The session id is regenerated after customer login and after a password change. Customer logout invalidates the session. Admin logout regenerates the session and CSRF token.
- Email verification is turned off until SMTP is configured. Account pages require a signed-in customer. They do not require a confirmed email.
- Profile updates do not ask for the current password. Password changes do, and the new password must differ from the current one and include a letter and a number.
- Order, invoice, address, wishlist, and return queries are limited to the logged-in customer. Another customer's order returns 404.
- Address updates and deletes include the customer id in the database query. A saved checkout address must belong to that customer.
- Profile and address text is trimmed and stripped of HTML before validation.

## Checkout and catalogue

- Checkout stores the logged-in customer on the order, or guest user id 0. Prices, tax, coupons, and stock are calculated on the server.
- Online payment options stay disabled. Only cash on delivery can be placed.
- Product listing sort values are limited to name, newest, and price.
- Product descriptions and CMS HTML are passed through `HtmlCleaner` before display.
- Reviews require a signed-in customer who bought the product, one review per product, and a rating from 1 to 5.

## This frontend pass

- Every storefront response sends `X-Content-Type-Options: nosniff`, `Referrer-Policy`, `X-Frame-Options: SAMEORIGIN`, and a restrictive Permissions-Policy.
- A strict Content-Security-Policy is not enforced, because the shop loads Tailwind and icons from CDNs and uses inline scripts.
- The layout includes a CSRF meta tag. Newsletter AJAX sends `X-CSRF-TOKEN`.
- An expired CSRF token returns the customer to the previous page with a message instead of a raw error page.
- Storefront forms ignore a second submit while the first request is in progress. The button is enabled again if the browser restores the page.
