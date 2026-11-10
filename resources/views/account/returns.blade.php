@extends('account.layout')
@section('account')
<h1 class="text-3xl font-semibold">Return requests</h1>
<div class="mt-6 bg-white border border-stone-200 rounded-xl divide-y">
    @forelse($requests as $request)
        <p class="p-4">Order {{ $request->order_id }} · product {{ $request->product_id }} · qty {{ $request->quantity }} · {{ $request->status }}</p>
    @empty
        <p class="p-4 text-stone-500">No return requests.</p>
    @endforelse
</div>
@endsection
