# Frontend UI audit

Reviewed the live storefront Blade views under `resources/views` and the shared layout `layout/app.blade.php`. Admin screens use Bootstrap and were left on the existing admin theme.

## Pages reviewed

| Page | View | Result |
| --- | --- | --- |
| Home | `index.blade.php` | Banner, categories, and featured products come from the database. Trust line added. No fake reviews or offers. |
| Shop / search / category | `products.blade.php` | Filters moved into a sidebar. Cards and pagination updated. |
| Product | `product_detail.blade.php` | Gallery thumbs, colour buttons, reviews, related and recent products. |
| Cart | `cart.blade.php` | Item cards kept. Checkout note clarified. |
| Checkout | `checkout.blade.php` | Progress label, server-calculated summary, disabled online payment options. |
| Order confirmation | `order_complete.blade.php` | Existing confirmation kept. |
| Login, register, forgot, reset | `auth/customer_*.blade.php` | Shared field styling. |
| Account dashboard, orders, order, addresses, profile, returns | `account/*` | Account layout from the previous pass kept. |
| Wishlist | `account/wishlist.blade.php` | Product cards plus move and remove actions. |
| CMS pages | `cms_page.blade.php` | Readable content card. Content stays editable in admin. |
| FAQ | `faq.blade.php` | Existing accordion kept. |
| Contact | `contact_us.blade.php` | Existing form kept, with server validation. |
| Newsletter unsubscribe | `newsletter_unsubscribe.blade.php` | Existing confirm step kept. |

## Design system

- Navy announcement bar, white sticky header, blue actions, amber newsletter button.
- Shared rules in `public/front/css/shop.css`: focus rings, form fields, cards, category dropdowns, CMS text.
- Font Awesome icons already used in the header and footer.
- Tailwind is still loaded from the CDN. No new frontend framework was added.

## Not redesigned

- Admin (`layout/admin.blade.php`) stays on Bootstrap.
- Legacy unused views such as `auth/login.blade.php`, `events.blade.php`, and `news_room.blade.php` are not linked from the current shop routes as primary pages. Routes that still render them were not given a second theme.
