# Database schema audit

Live MariaDB database `cranzo_dev`. Catalogue and order tables were created outside Laravel migrations. New work must add columns, not rebuild tables.

## Customers

`users`: `id`, `username`, `email` (unique), `mobile_number`, `first_name`, `middle_name`, `last_name`, `email_verified_at`, `password`, `remember_token`, timestamps. There is no `name` column. One mobile number is already shared by more than one row, so a unique mobile index was not added. Registration still rejects a new duplicate.

`password_reset_tokens` exists.

`address.id` is not auto-increment. Columns are `user_id`, `name`, `mobile_number`, `address`, `pincode`, `locality`, `city`, `state_id`, `address_type`, `default_type`, `created_date`. There is no country, street, or landmark column. `address_type` 1 is used as shipping and 2 as billing. `state.id` is the state key. `state` has no primary key.

## Orders

`orders.id` is auto-increment. Status columns are tiny integers. Every current row has `delivery_status` 1. Historical rows use `payment_status` 0. The current checkout writes `payment_status` 1 for cash on delivery and `user_id` 0 for guests. `order_details.id` and `payments.payment_id` are not auto-increment. Amount column is spelled `amount_recieved`.

`cancelled_at` is added as a nullable datetime so cancellation does not reuse an unknown status number.

## Catalogue stock

`product_details.product_quantity` is the only stock figure. There is no reserved-quantity column, no movement ledger, and no brand, GST, or variant-price columns. Colour rows exist. The product page does not ask the shopper to pick one.

## Contact and content

`contact_us` stores enquiries, including `subject`. `cms_pages`, `faqs`, `newsletter_subscribers`, and `newsletter_campaigns` exist. `reviews` has no primary key and is not used by the shop.
