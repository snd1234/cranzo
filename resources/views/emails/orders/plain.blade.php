{{ $order['store'] }}

@if($order['kind'] === 'failed')
We're sorry! Your payment could not be completed. Your order payment is currently unsuccessful. You can retry the payment using the available payment options.
@elseif($order['kind'] === 'paid')
Thank you for shopping with us! Your order has been successfully placed. We will keep you updated on your order status. Payment has been received.
@else
Thank you for shopping with us! Your order has been successfully placed. We will keep you updated on your order status.
@endif

Order number: {{ $order['order_number'] }}
Order date: {{ $order['placed_at'] }}
Customer: {{ $order['customer_name'] }}
@if($order['customer_email'])Email: {{ $order['customer_email'] }}
@endif
@if($order['customer_phone'])Phone: {{ $order['customer_phone'] }}
@endif
@if($order['address'])Billing and shipping: {{ $order['address'] }}
@endif
Order status: {{ $order['order_status'] }}
Payment method: {{ $order['payment_method'] }}
Payment status: {{ $order['payment_status'] }}
@if($order['transaction_id'])Transaction ID: {{ $order['transaction_id'] }}
@endif
@if($order['delivery_date'])Estimated delivery: {{ $order['delivery_date'] }}
@endif
@if($order['failure_reason'])Reason: {{ $order['failure_reason'] }}
@endif

@foreach($order['lines'] as $line)
- {{ $line['title'] }}@if($line['sku']) (SKU {{ $line['sku'] }})@endif × {{ $line['qty'] }} @ ₹{{ number_format($line['price'], 2) }} = ₹{{ number_format($line['total'], 2) }}
@endforeach

Subtotal: ₹{{ number_format($order['subtotal'], 2) }}
Discount: ₹{{ number_format($order['discount'], 2) }}
@if($order['coupon'])Coupon: {{ $order['coupon'] }}
@endif
Shipping: ₹{{ number_format($order['shipping'], 2) }}
{{ $order['tax_label'] }}: ₹{{ number_format($order['tax'], 2) }}
Amount payable: ₹{{ number_format($order['total'], 2) }}
@if($order['payment_method'] === 'Cash on Delivery')
Cash on delivery. Please keep ₹{{ number_format($order['total'], 2) }} ready to pay when the order arrives.
@endif
@if($order['view_url'])
View order: {{ $order['view_url'] }}
@endif
@if($order['retry_url'])
Retry payment: {{ $order['retry_url'] }}
@endif
@if($order['support_email'])
Support: {{ $order['support_email'] }}
@endif
