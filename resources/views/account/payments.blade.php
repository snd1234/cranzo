@extends('account.layout')

@section('account')
<div class="mb-6">
    <p class="text-sm font-medium text-blue-700">My account</p>
    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Payment history</h1>
    <p class="mt-2 text-stone-600">Payments for your orders. Card numbers are not stored.</p>
</div>
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @forelse($payments as $payment)
        <a href="{{ route('account.order', $payment['order_id']) }}" class="grid gap-1 sm:grid-cols-[1.2fr_1fr_auto] sm:items-center px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50">
            <span>
                <span class="block font-medium text-slate-900">{{ $payment['order_number'] }}</span>
                <span class="block text-sm text-stone-500">{{ $payment['when'] }}</span>
            </span>
            <span class="text-sm text-slate-700">
                {{ $payment['method'] }}
                @if($payment['reference'])
                    <span class="block text-stone-500">{{ $payment['reference'] }}</span>
                @endif
            </span>
            <span class="text-sm sm:text-right">
                <span class="block font-medium text-slate-900">{{ $payment['amount'] }}</span>
                <span class="block text-stone-500">{{ $payment['status'] }}</span>
            </span>
        </a>
    @empty
        <p class="p-5 text-stone-500">No payments yet. They appear here after you place an order.</p>
    @endforelse
</div>
@endsection