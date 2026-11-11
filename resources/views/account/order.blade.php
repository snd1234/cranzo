@extends('account.layout')

@section('account')
<div class="mb-6">
    <a class="text-sm text-blue-700" href="{{ route('account.orders') }}">Back to orders</a>
    <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Order {{ $row->order_number }}</h1>
    <p class="mt-2"><span class="text-xs font-medium rounded-full px-2.5 py-1 {{ \App\Support\OrderLabel::tone($row) }}">{{ \App\Support\OrderLabel::summary($row) }}</span>@if($row->booking_date)<span class="text-stone-600 text-sm"> · {{ \Illuminate\Support\Carbon::parse($row->booking_date)->format('d M Y') }}</span>@endif</p>
</div>
<div class="mt-6 bg-white border border-stone-200 rounded-xl p-5">
    @foreach($details as $line)
        <p class="flex justify-between gap-3 py-2 border-b border-stone-100">
            <span>{{ $line->product_title }} × {{ $line->order_quantity }}</span>
            <span>₹{{ $line->amount_per_item }}</span>
        </p>
    @endforeach
    @if($address)
        <p class="mt-4 text-sm text-stone-600">{{ $address->name }}, {{ $address->address }}, {{ $address->locality }}, {{ $address->city }} {{ $address->pincode }} {{ $state }}</p>
    @endif
    <div class="flex flex-wrap gap-3 mt-5">
        <a class="border border-stone-300 rounded-full px-4 py-2" href="{{ route('account.invoice', $row->id) }}">Download invoice</a>
        <form method="post" action="{{ route('account.order.reorder', $row->id) }}">@csrf<button class="bg-stone-900 text-white rounded-full px-4 py-2" type="submit">Reorder</button></form>
        @if(! $row->cancelled_at && (int) $row->order_status === 1 && (int) $row->payment_status === 1 && (int) $row->delivery_status === 1)
            <form method="post" action="{{ route('account.order.cancel', $row->id) }}">@csrf<button class="border border-stone-300 rounded-full px-4 py-2" type="submit">Cancel order</button></form>
        @endif
    </div>
    @if(! $row->cancelled_at)
        <form method="post" action="{{ route('account.returns.store', $row->id) }}" enctype="multipart/form-data" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-3">
            @csrf
            <h2 class="font-semibold">Request a return</h2>
            <p class="text-sm text-stone-500">This does not refund the payment. The shop reviews the request.</p>
            <label>Product
                <select name="product_id" class="mt-1 w-full border rounded-lg px-3 py-2">
                    @foreach($details as $line)<option value="{{ $line->product_id }}">{{ $line->product_title }}</option>@endforeach
                </select>
            </label>
            <label>Quantity<input type="number" name="quantity" min="1" value="1" class="mt-1 w-full border rounded-lg px-3 py-2"></label>
            <label>Reason<input name="reason" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
            <label>Details<textarea name="details" class="mt-1 w-full border rounded-lg px-3 py-2"></textarea></label>
            <label>Photo<input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
            <button class="bg-stone-900 text-white rounded-full px-4 py-2 w-fit" type="submit">Send request</button>
        </form>
    @endif
</div>
@endsection
