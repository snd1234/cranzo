# Newsletter implementation

## Storage

`newsletter_subscribers` stores one lowercase email, a subscribed or unsubscribed status, a consent flag, an unguessable token, and the subscribe and unsubscribe times. The email column is unique. Signing up again with an active email returns “already subscribed”. An unsubscribed email can be subscribed again and receives a new token.

`newsletter_campaigns` stores a subject, cleaned HTML, status (`draft`, `queued`, or `sent`), an optional send time, and the number of recipients. Creating a draft does not send mail.

## Public form

The footer form posts to `POST /newsletter` with the CSRF token, without reloading the page. Invalid email addresses are rejected. Five attempts per minute are allowed. A hidden honeypot field is discarded as a successful no-op. The consent checkbox is optional. Campaign mail is sent only when status is subscribed and consent is yes.

## Mail

A confirmation message is attempted through the existing Laravel mailer. This project’s mailer is `log` unless SMTP is set in `.env`. A failure is reported and the subscription is still saved. Set `NEWSLETTER_NOTIFY_EMAIL` to also notify an administrator. Set `CONTACT_NOTIFY_EMAIL` for contact-form copies. Do not put passwords in the database.

The confirmation includes a link to `/newsletter/unsubscribe/{token}`. That page asks the visitor to confirm. Confirming sets the row to unsubscribed, clears consent, and replaces the token. The public site never lists subscribers.

## Campaigns

Marketing → Newsletter Subscribers lists, searches, filters, views, activates, unsubscribes, and exports CSV for an authenticated admin. The CSV contains email and status only, not the token.

Marketing → Newsletter Campaigns can queue a draft. `SendNewsletterCampaign` runs on the database queue and emails only subscribed rows with consent. Each message includes that subscriber’s unsubscribe link. Unsubscribed addresses are not selected. A queue worker must be running (`php artisan queue:work`) because `QUEUE_CONNECTION` is `database`. A future `scheduled_at` delays the job.
