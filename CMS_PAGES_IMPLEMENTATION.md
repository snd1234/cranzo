# CMS pages implementation

## Tables

- Created `cms_pages` for title, slug, excerpt, HTML content, optional image, SEO fields, canonical URL, status, footer flag, footer group, and footer order.
- Created `faqs` for question, answer, display order, and status.
- Added nullable `contact_us.subject`. The existing enquiry columns are unchanged.
- Did not create a `pages` table and did not connect the unused `PageController`.

The migration inserts seven active pages and eight FAQ answers. Bracketed sentences mark details the owner still has to confirm. No address, registration number, refund window, warranty, or jurisdiction is invented.

## Admin

Website Management → CMS Pages and FAQ. Pages can be added, viewed, edited, deleted, and activated. Slugs are generated from the title and can be edited. Duplicate and reserved slugs are rejected. CKEditor supplies the content, which is stored after script, style, and attribute stripping. The same allow-list is applied again when the page is rendered.

## Public URLs

`/about-us`, `/contact-us`, `/faq`, `/privacy-policy`, `/terms-conditions`, `/returns-exchange`, and `/terms-of-use` load the active row for that slug. Any other active page is available at `/page/{slug}`. A fixed slug requested under `/page/` redirects to its short URL. Missing and inactive pages return 404. The document title, meta description, canonical URL, and Open Graph image come from the row.

The footer lists active pages with footer display enabled, split into Customer Support and Information, in footer order. Contact Us keeps its form and stores the message in `contact_us`. The form is limited to five posts a minute and ignores a filled honeypot field.

Active CMS pages are included in `/sitemap.xml`. Cart, checkout, and `/system-auth` stay out of the sitemap and are disallowed in `robots.txt`.
