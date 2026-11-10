# Implementation plan

Work stays on the existing shop. Each phase is done only when the screen, the database write, and the ownership checks work.

1. **Security foundation.** Separate customer and admin login, rate limits, session regeneration, and ownership checks. Done for the customer account flow. Role permissions and GET admin deletes remain.
2. **Customer accounts.** Register, verify email, reset password, dashboard, profile, addresses, order history, reorder, and cart refresh on login. Done in this pass.
3. **Catalogue.** Variant selection, reviews, wishlist, related products, recently viewed, and richer filters. Not started. The listing already filters by category, subcategory, keyword, and sort.
4. **Checkout money.** Coupons, GST, payment-method placeholders, and PDF invoices. Not started. Checkout remains cash on delivery with server-side prices. No gateway was added.
5. **After sales.** Order emails, admin status workflow, returns workflow, stock reservations. Not started. The Returns page stays CMS text. Customers can cancel only unpaid-looking current checkout orders, as described in the schema audit.
6. **Reports and staff roles.** Not started. Contact messages stay in `contact_us` without an inbox.
7. **UI and tests.** Account and auth screens use the current storefront layout. A customer feature test covers registration, verification, ownership, and cart refresh.

Do not add Razorpay or another gateway until a later, separate task. Do not invent GST rates, refund windows, or courier credentials.
