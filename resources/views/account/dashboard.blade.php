@extends('account.layout')

@section('account')
<div class="mb-6">
    <p class="text-sm font-medium text-blue-700">My account</p>
    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Hello, {{ auth()->user()->first_name }}</h1>
    <p class="mt-2 text-stone-600">Member since {{ auth()->user()->created_at?->format('M Y') }}.</p>
</div>
<div class="grid sm:grid-cols-3 gap-4">
    <a href="{{ route('account.orders') }}" class="bg-white border border-stone-200 rounded-2xl shadow-sm p-5 hover:border-blue-200">
        <p class="text-sm text-stone-500">Orders</p>
        <p class="mt-1 text-3xl font-semibold text-slate-900">{{ $orderCount }}</p>
    </a>
    <a href="{{ route('account.orders') }}" class="bg-white border border-stone-200 rounded-2xl shadow-sm p-5 hover:border-blue-200">
        <p class="text-sm text-stone-500">Open orders</p>
        <p class="mt-1 text-3xl font-semibold text-slate-900">{{ $pendingCount }}</p>
    </a>
    <a href="{{ route('account.addresses') }}" class="bg-white border border-stone-200 rounded-2xl shadow-sm p-5 hover:border-blue-200">
        <p class="text-sm text-stone-500">Saved addresses</p>
        <p class="mt-1 text-3xl font-semibold text-slate-900">{{ $addressCount }}</p>
    </a>
</div>
<div class="mt-8 flex items-end justify-between gap-3">
    <h2 class="text-lg font-semibold text-slate-900">Recent orders</h2>
    <a class="text-sm text-blue-700" href="{{ route('account.orders') }}">View all</a>
</div>
<div class="mt-3 bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @forelse($recent as $order)
        <a href="{{ route('account.order', $order->id) }}" class="flex items-center justify-between gap-3 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50">
            <span>
                <span class="block font-medium text-slate-900">{{ $order->order_number }}</span>
                <span class="block text-sm text-stone-500">{{ $order->booking_date ? \Illuminate\Support\Carbon::parse($order->booking_date)->format('d M Y') : '' }}</span>
            </span>
            <span class="text-xs font-medium rounded-full px-2.5 py-1 {{ \App\Support\OrderLabel::tone($order) }}">{{ \App\Support\OrderLabel::summary($order) }}</span>
        </a>
    @empty
        <p class="p-5 text-stone-500">No orders yet. <a class="text-blue-700" href="{{ url('/products') }}">Browse products</a></p>
    @endforelse
</div>
@endsection
