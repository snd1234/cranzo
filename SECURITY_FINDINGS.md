# Security findings

| Severity | Finding | Where | Status |
| --- | --- | --- | --- |
| High | Admin deactivate and delete links still respond to GET. | Category, product, and user admin routes | Open |
| High | `admins.role` does not grant or deny modules. | Admin guard | Open |
| High | Production debug, HTTPS, and cookie secure flags depend on server `.env`. | Hosting | Open. Do not turn debug on in production. |
| Medium | Old admin order screen uses a relation named `User()` and can error. | `Orders` model | Open |
| Medium | Verification and reset mail stay in the log until SMTP is set. | Mail | Open |
| Low | No extra security-header middleware. | HTTP | Open |

Customer account pages now require the owning customer. Login is throttled. A saved checkout address is accepted only for that customer. No card data is stored, and no payment gateway was added.
