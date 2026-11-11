@extends('account.layout')

@section('account')
<div class="mb-6">
    <p class="text-sm font-medium text-blue-700">My account</p>
    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Orders</h1>
    <p class="mt-2 text-stone-600">Open an order to see the items, invoice, and delivery address.</p>
</div>
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @forelse($orders as $order)
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
<div class="mt-4">{{ $orders->links('pagination::tailwind') }}</div>
@endsection
