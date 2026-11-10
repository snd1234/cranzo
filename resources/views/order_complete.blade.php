@extends('layout.app')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Order placed</h1>
    <p class="mt-3 text-stone-700">Your cash on delivery order <strong>{{ $order->order_number }}</strong> is pending confirmation.</p>
    <div class="mt-6 bg-white border border-stone-200 rounded-xl p-5">
        @foreach($details as $detail)
            <p class="flex justify-between gap-3 py-2 border-b border-stone-100">
                <span>{{ $detail->product_title }} × {{ $detail->order_quantity }}</span>
                <span>₹{{ number_format((float) $detail->amount_per_item * $detail->order_quantity, 0) }}</span>
            </p>
        @endforeach
        @if($payment)
            <p class="flex justify-between font-semibold pt-3"><span>Total</span><span>₹{{ number_format((float) $payment->total_amount, 0) }}</span></p>
        @endif
    </div>
    <a href="{{ url('/products') }}" class="inline-flex mt-6 bg-stone-950 text-white rounded-full px-5 py-3">Continue shopping</a>
</section>
@endsection
