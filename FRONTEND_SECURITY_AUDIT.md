# Frontend security audit

## Fixed in this pass

- Storefront responses now send nosniff, referrer policy, frame blocking, and a locked-down permissions policy.
- Expired CSRF tokens redirect back with a readable message and do not refill password fields.
- Checkout placement is rate limited.
- Product descriptions render through `HtmlCleaner` instead of a raw HTML echo.
- Repeated form submits are ignored until the browser loads the next page.

## Already in place

- Blade `{{ }}` escaping on catalogue, cart, account, and form values.
- CMS and FAQ HTML cleaned before `{!! !!}` output.
- Customer orders, addresses, invoices, and returns are scoped by user id.
- Cart prices are not trusted from the browser.
- Review inserts do not accept a published status from the form.
- Registration and login are throttled. Sessions regenerate on login.

## Left open

- A strict Content-Security-Policy is not enabled. Tailwind, Font Awesome, and inline banner and newsletter scripts would break under a tight policy.
- HTTPS, secure cookies, and `APP_DEBUG=false` are hosting settings. Local XAMPP stays on HTTP with debug available.
- Admin catalogue routes still allow any signed-in admin, including older GET deactivate links. Those are not storefront pages.
- Checkout can still be submitted twice if the customer opens two tabs. Stock deduction rolls the second order back when stock is insufficient.
- Upload checks on returns and admin images use extension and size. They do not decode every image byte.
