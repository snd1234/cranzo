@php
    $heading = match ($order['kind']) {
        'failed' => 'Payment failed',
        'paid' => 'Payment received',
        default => 'New order',
    };
@endphp
@include('emails.orders._layout', [
    'heading' => $heading,
    'intro' => 'A Cranzo order needs attention. The customer copy uses the same order details.',
])
