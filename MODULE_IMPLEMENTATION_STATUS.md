# Module implementation status

Updated after the customer-account pass. “Complete” means the screen, database, and ownership checks work on the live schema.

| Module | Status |
| --- | --- |
| Customer registration, login, logout, remember me, throttling | Complete |
| Email verification and password reset | Complete. Delivery depends on real SMTP. Local mail is logged. |
| My Account dashboard, profile, password change | Complete |
| Saved addresses | Complete, using `address` |
| Order history, reorder, limited cancellation | Complete for orders owned by the customer |
| Guest cart kept and rechecked on login | Complete. Cart remains in the session. |
| Wishlist, reviews, colour options, price and stock filters | Working. There is no brand or size column to filter. |
| Coupons, GST when a product rate is set, invoices, payment choice | Working. Online, UPI, card, and net banking stay disabled. No gateway was added. |
| Returns workflow separate from the CMS page | Working. The CMS page is still editable text. No refund is sent automatically. |
| Order email, admin order updates, stock movement | Working. Email sends only when mail is configured. Stock is reduced once at checkout and restored on cancel. |
| Reports, abandoned carts, contact inbox, staff roles | Working for the current admin role. Roles 3 to 8 limit the new screens. |
| Carorbis-wide visual redesign | Not started. Account pages follow the current storefront. |

## Configuration

Set `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, and `MAIL_FROM_NAME` before verification or reset mail can reach customers. No new secret was added.

Run `php artisan migrate` for the nullable `orders.cancelled_at` column.
