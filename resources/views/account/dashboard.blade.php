@extends('account.layout')

@section('account')
<h1 class="text-3xl font-semibold">Hello, {{ auth()->user()->first_name }}</h1>
<p class="text-stone-600 mt-1">Your Cranzo account. Member since {{ auth()->user()->created_at?->format('M Y') }}.</p>
<div class="grid sm:grid-cols-3 gap-4 mt-6">
    <div class="bg-white border border-stone-200 rounded-xl p-4"><p class="text-sm text-stone-500">Orders</p><p class="text-2xl font-semibold">{{ $orderCount }}</p></div>
    <div class="bg-white border border-stone-200 rounded-xl p-4"><p class="text-sm text-stone-500">Open orders</p><p class="text-2xl font-semibold">{{ $pendingCount }}</p></div>
    <div class="bg-white border border-stone-200 rounded-xl p-4"><p class="text-sm text-stone-500">Saved addresses</p><p class="text-2xl font-semibold">{{ $addressCount }}</p></div>
</div>
<h2 class="mt-8 font-semibold">Recent orders</h2>
<div class="mt-3 bg-white border border-stone-200 rounded-xl overflow-hidden">
    @forelse($recent as $order)
        <a href="{{ route('account.order', $order->id) }}" class="flex justify-between gap-3 px-4 py-3 border-b border-stone-100 last:border-0 hover:bg-stone-50">
            <span>{{ $order->order_number }}</span>
            <span class="text-sm text-stone-500">{{ $order->cancelled_at ? 'Cancelled' : 'Order '.$order->order_status }}</span>
        </a>
    @empty
        <p class="p-4 text-stone-500">No orders yet.</p>
    @endforelse
</div>
@endsection
