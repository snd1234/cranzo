@extends('account.layout')

@section('account')
<h1 class="text-3xl font-semibold">Orders</h1>
<div class="mt-6 bg-white border border-stone-200 rounded-xl overflow-hidden">
    @forelse($orders as $order)
        <a href="{{ route('account.order', $order->id) }}" class="flex justify-between gap-3 px-4 py-3 border-b border-stone-100 last:border-0">
            <span>{{ $order->order_number }}</span>
            <span class="text-sm text-stone-500">{{ $order->cancelled_at ? 'Cancelled' : $order->booking_date }}</span>
        </a>
    @empty
        <p class="p-4 text-stone-500">No orders yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
