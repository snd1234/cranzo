# Security audit

Reviewed the current Laravel routes, auth guards, checkout, and uploads. This is not a claim that the site is fully secure.

## Fixed in this pass

- Customer and admin login now go to different screens. Account pages require a verified customer.
- Customer login is rate limited. The session id is regenerated after login. Logout invalidates the session.
- Profile and password changes require the current password.
- A customer can open only their own addresses and orders. A saved checkout address is accepted only when it belongs to the logged-in customer.
- New orders store the logged-in customer id. Guest orders from the same session are attached on login.
- Prices stay on the server. The cart stores product id and quantity only.

## Still open

| Severity | Issue |
| --- | --- |
| High | Several admin delete and deactivate actions are still available by GET. |
| High | Staff access is one admin guard. `admins.role` is stored and not enforced. |
| High | `APP_DEBUG` and the web server settings were not changed. Production must keep debug off and HTTPS on. |
| Medium | Historical order screens still use the `User()` relation name and can fail. |
| Medium | Uploaded images are checked by extension and size, not by a full image decode in every form. |
| Medium | Mail uses the log driver until SMTP is set. Reset and verification links are not delivered to a real inbox. |
| Low | No security-header middleware is installed. |
| Low | Checkout can be submitted twice if the shopper sends two fast requests. |

Card numbers are not collected. No payment gateway was added.
