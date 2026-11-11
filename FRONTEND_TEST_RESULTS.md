# Frontend test results

Checked on 11 Nov 2026 against `http://127.0.0.1:8000`.

| Check | Result |
| --- | --- |
| `GET /` | 200, security headers present |
| `GET /products` | 200 |
| `GET /login` | 200 |
| `GET /register` | 200 |
| `GET /cart` | 200 |
| `GET /contact-us` | 200 |
| `GET /faq` | 200 |
| `CustomerAccountTest` | 5 passed |

Browser checks covered the desktop header, shop filter column, and product cards. A full tablet and phone pass of every CMS page was not repeated in a device lab. The layout uses the existing Tailwind breakpoints and does not set a fixed page width.

Online payment was not exercised. Those options remain disabled.
