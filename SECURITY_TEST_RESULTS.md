# Security test results

`php artisan test --filter=CustomerAccountTest` passed four tests:

- A second registration with the same email is rejected.
- An unverified customer is sent to the verification page instead of the account.
- A customer cannot open another customer's order. The owner can.
- Signing in drops a cart line for a product that is not for sale.

These tests use an in-memory database so they do not change `cranzo_dev`.

Not covered yet: admin GET deletes, role permissions, file-upload content checks, and a full checkout duplicate-submit test. Those remain open in `SECURITY_FINDINGS.md`.
