<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 16px;font-size:14px;line-height:1.5;">
    <tr><td style="padding:4px 0;color:#5c534c;">Order number</td><td style="padding:4px 0;text-align:right;font-weight:700;">{{ $order['order_number'] }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">Order date</td><td style="padding:4px 0;text-align:right;">{{ $order['placed_at'] }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">Order status</td><td style="padding:4px 0;text-align:right;">{{ $order['order_status'] }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">Payment method</td><td style="padding:4px 0;text-align:right;">{{ $order['payment_method'] }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">Payment status</td><td style="padding:4px 0;text-align:right;">{{ $order['payment_status'] }}</td></tr>
    @if($order['transaction_id'])
        <tr><td style="padding:4px 0;color:#5c534c;">Transaction ID</td><td style="padding:4px 0;text-align:right;">{{ $order['transaction_id'] }}</td></tr>
    @endif
    @if($order['delivery_date'])
        <tr><td style="padding:4px 0;color:#5c534c;">Estimated delivery</td><td style="padding:4px 0;text-align:right;">{{ $order['delivery_date'] }}</td></tr>
    @endif
</table>
@if($order['failure_reason'])
    <p style="margin:0 0 16px;font-size:14px;line-height:1.5;color:#3f3832;"><strong>Reason:</strong> {{ $order['failure_reason'] }}</p>
@endif
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
    @foreach($order['lines'] as $line)
        <tr>
            <td style="padding:10px 0;border-top:1px solid #eadfce;vertical-align:top;">
                @if($line['image'])
                    <img src="{{ $line['image'] }}" alt="" width="56" height="56" style="border-radius:8px;display:block;margin:0 0 8px;">
                @endif
                <strong>{{ $line['title'] }}</strong>
                @if($line['sku'])<br><span style="color:#5c534c;">SKU {{ $line['sku'] }}</span>@endif
                <br><span style="color:#5c534c;">Qty {{ $line['qty'] }} × ₹{{ number_format($line['price'], 2) }}</span>
            </td>
            <td style="padding:10px 0;border-top:1px solid #eadfce;text-align:right;vertical-align:top;white-space:nowrap;">₹{{ number_format($line['total'], 2) }}</td>
        </tr>
    @endforeach
</table>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:8px;font-size:14px;">
    <tr><td style="padding:4px 0;color:#5c534c;">Subtotal</td><td style="padding:4px 0;text-align:right;">₹{{ number_format($order['subtotal'], 2) }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">Discount</td><td style="padding:4px 0;text-align:right;">₹{{ number_format($order['discount'], 2) }}</td></tr>
    @if($order['coupon'])
        <tr><td style="padding:4px 0;color:#5c534c;">Coupon</td><td style="padding:4px 0;text-align:right;">{{ $order['coupon'] }}</td></tr>
    @endif
    <tr><td style="padding:4px 0;color:#5c534c;">Shipping</td><td style="padding:4px 0;text-align:right;">₹{{ number_format($order['shipping'], 2) }}</td></tr>
    <tr><td style="padding:4px 0;color:#5c534c;">{{ $order['tax_label'] }}</td><td style="padding:4px 0;text-align:right;">₹{{ number_format($order['tax'], 2) }}</td></tr>
    <tr><td style="padding:8px 0;font-weight:700;border-top:1px solid #1b1613;">Amount payable</td><td style="padding:8px 0;text-align:right;font-weight:700;border-top:1px solid #1b1613;">₹{{ number_format($order['total'], 2) }}</td></tr>
</table>
@if($order['payment_method'] === 'Cash on Delivery')
    <p style="margin:14px 0 0;font-size:14px;line-height:1.6;color:#3f3832;">Cash on delivery. Please keep ₹{{ number_format($order['total'], 2) }} ready to pay when the order arrives.</p>
@endif
@if($order['address'])
    <p style="margin:16px 0 0;font-size:14px;line-height:1.6;color:#3f3832;"><strong>Billing and shipping address</strong><br>{{ $order['address'] }}@if($order['customer_phone'])<br>{{ $order['customer_phone'] }}@endif @if($order['customer_email'])<br>{{ $order['customer_email'] }}@endif</p>
@endif
