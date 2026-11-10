# CMS, footer and newsletter audit

Inspected on 10 Nov 2026 against `cranzo_dev`.

## Already present

- `/about-us` and `/contact-us` are fixed routes. About does not read a CMS table. Contact saves `name`, `email`, `phone`, `message`, and `ip_address` into `contact_us`. That table has no subject column.
- `PageController` and the `Page` model expect a `pages` table, soft deletes, and a `SeoContent` model. None of those exist, and `PageController` is not registered on a route.
- `AjaxController::subscribeNewsletter` writes to an `enquiry` table that is not in this database. The route is not registered.
- The footer is three columns: store text, categories, and latest products. It has no CMS links, social icons, or payment icons.
- Mail is configured through `.env`. This project uses the log mailer unless SMTP is set. No newsletter subscriber table exists.

## Not present

`home_sliders` is unrelated and also absent. There is no FAQ table and no subscriber table.

## Decision

Create `cms_pages`, `faqs`, and `newsletter_subscribers`. Add a nullable `subject` column to `contact_us`. Leave the unused `Page` model and `PageController` in place so the broken laboratory CMS is not revived against the wrong schema. Store SEO fields on `cms_pages`, which matches the product and banner approach. Seed the seven public pages with editable placeholder copy that does not invent an address, registration number, refund window, or warranty.
