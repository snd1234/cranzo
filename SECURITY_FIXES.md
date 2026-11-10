# Security fixes

- Customer sign-in is `/login`. Admin sign-in stays at `/system-auth/login`.
- Login and registration are limited to five attempts a minute.
- The session id is regenerated after customer login. Customer logout invalidates the session. Admin logout regenerates the session and CSRF token.
- Account pages require a verified email. Changing the email clears verification until the new address is confirmed. Profile and password changes require the current password.
- Order and address queries include the logged-in customer id. Another customer's order returns 404.
- Checkout stores the logged-in customer on the order. A chosen saved address must belong to that customer.
- The cart keeps only active products and quantities the stock can cover when the customer signs in.
