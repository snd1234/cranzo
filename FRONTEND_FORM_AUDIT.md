# Frontend form audit

| Form | View | Route | Method | Handler | CSRF | Auth | Validation |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Search | `layout/app.blade.php` | `/products` | GET | `HomeController@products` | Not required | Public | Query is escaped in Blade. Sort is allow-listed. |
| Filters | `products.blade.php` | `/products` | GET | `HomeController@products` | Not required | Public | Price, colour, stock, and sort are read on the server. |
| Add to cart / buy now | `product_detail.blade.php` | `cart.add` | POST | `CartController@add` | Yes | Public | Product must be active. Quantity is capped by stock. |
| Wishlist add | `product_detail.blade.php` | `wishlist.store` | POST | `WishlistController@store` | Yes | Public, throttled | Product must exist. |
| Review | `product_detail.blade.php` | `reviews.store` | POST | `ReviewController@store` | Yes | Signed in, throttled | Rating 1–5, title, comment, purchase, one review. |
| Cart quantity | `cart.blade.php` | `cart.update` | POST | `CartController@update` | Yes | Public | Quantity rechecked against stock. |
| Remove cart item | `cart.blade.php` | `cart.remove` | POST | `CartController@remove` | Yes | Public | Removes only the session line. |
| Coupon | `checkout.blade.php` | `cart.coupon` | POST | `CartController@applyCoupon` | Yes | Public, throttled | Eligibility is calculated in `CheckoutQuote`. |
| Remove coupon | `checkout.blade.php` | `cart.coupon.remove` | DELETE | `CartController@removeCoupon` | Yes | Public | Clears the session code. |
| Checkout | `checkout.blade.php` | `checkout.place` | POST | `CartController@place` | Yes | Public, throttled | Address, state, mobile, PIN, stock, totals. Only `cod` is accepted. |
| Login | `auth/customer_login.blade.php` | `customer.login.submit` | POST | `CustomerAuthController@login` | Yes | Guest, throttled | Email and password. |
| Register | `auth/customer_register.blade.php` | `customer.register.submit` | POST | `CustomerAuthController@register` | Yes | Guest, throttled | Name, unique email, Indian mobile, password, terms. |
| Forgot password | `auth/customer_forgot.blade.php` | `password.email` | POST | `CustomerAuthController@sendReset` | Yes | Guest, throttled | Email. |
| Reset password | `auth/customer_reset.blade.php` | `password.update` | POST | `CustomerAuthController@reset` | Yes | Guest, throttled | Token, email, confirmed password. |
| Contact | `contact_us.blade.php` | `contact.submit` | POST | `HomeController@submitContact` | Yes | Public, throttled | Name, email, phone, subject, message, honeypot. |
| Newsletter | `layout/app.blade.php` | `newsletter.subscribe` | POST | `NewsletterController@subscribe` | Yes, including AJAX header | Public, throttled | Email and consent. |
| Unsubscribe | `newsletter_unsubscribe.blade.php` | `newsletter.unsubscribe.confirm` | POST | `NewsletterController@confirmUnsubscribe` | Yes | Token in URL | Confirms before changing status. |
| Profile | `account/profile.blade.php` | `account.profile.update` | PUT | `AccountController@updateProfile` | Yes | Signed in, throttled | Name, unique email, Indian mobile. |
| Password | `account/profile.blade.php` | `account.password` | PUT | `AccountController@updatePassword` | Yes | Signed in, throttled | Current password and a different new password. |
| Address create/edit | `account/addresses.blade.php` | `account.addresses.store` / `update` | POST / PUT | `CustomerAddressController` | Yes | Signed in, throttled | Name, mobile, address, active state, PIN. Max 10. |
| Address delete | `account/addresses.blade.php` | `account.addresses.destroy` | DELETE | `CustomerAddressController@destroy` | Yes | Owner only | Blocked when an order uses the address. |
| Cancel / reorder | `account/order.blade.php` | `account.order.cancel` / `reorder` | POST | `AccountController` | Yes | Owner only | Cancel rules stay on the server. |
| Return request | `account/order.blade.php` | `account.returns.store` | POST | `ReturnController@store` | Yes | Owner only | Quantity cannot exceed the purchased amount. |
| Wishlist move/remove | `account/wishlist.blade.php` | `wishlist.move` / `destroy` | POST / DELETE | `WishlistController` | Yes | Session or customer | Acts on that product row only. |

GET is used for search, filters, and page views. Deletes and updates use POST, PUT, or DELETE with `@csrf`.
