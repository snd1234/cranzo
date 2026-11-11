@extends('layout.app')

@section('content')
@php
    $postedAddress = old('address_id', '__none__');
    if ($postedAddress === '__none__') {
        $defaultAddress = $addresses->first(fn ($address) => (int) $address->default_type === 1) ?: $addresses->first();
        $selectedId = $defaultAddress ? (string) $defaultAddress->id : '';
    } else {
        $selectedId = (string) $postedAddress;
    }
    $showNew = $addresses->isEmpty() || $selectedId === '';
@endphp
<section class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#8a6a24]">Cart · Details · Confirmation</p>
        <h1 class="text-3xl font-semibold mt-1 text-slate-950">Checkout</h1>
        <p class="text-stone-600 mt-2">Cash on delivery. Prices, tax, and discounts are calculated on the server. UPI, cards, and net banking are not available yet.</p>
        <form action="{{ route('checkout.place') }}" method="post" class="mt-6 grid gap-5">
            @csrf
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 text-red-800 text-sm p-4 grid gap-1" role="alert">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif
            @if($addresses->isNotEmpty())
                <fieldset class="grid gap-3">
                    <legend class="font-semibold text-slate-950 mb-1">Saved address</legend>
                    @foreach($addresses as $saved)
                        <label class="address-choice {{ (string) $selectedId === (string) $saved->id ? 'is-selected' : '' }}">
                            <span class="flex gap-3">
                                <input type="radio" name="address_id" value="{{ $saved->id }}" class="mt-1" @checked((string) $selectedId === (string) $saved->id)>
                                <span>
                                    <span class="flex flex-wrap items-center gap-2">
                                        <span class="font-semibold text-slate-950">{{ $saved->name }}</span>
                                        @if((int) $saved->default_type === 1)
                                            <span class="rounded-full bg-[#f3ead8] px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-[#8a6a24]">Default</span>
                                        @endif
                                    </span>
                                    <span class="block text-sm text-stone-600 mt-1">{{ $saved->address }}, {{ $saved->locality }}</span>
                                    <span class="block text-sm text-stone-600">{{ $saved->city }}{{ $saved->state_name ? ', '.$saved->state_name : '' }} {{ $saved->pincode }}</span>
                                    <span class="block text-sm text-stone-500 mt-1">{{ $saved->mobile_number }}</span>
                                </span>
                            </span>
                        </label>
                    @endforeach
                    <label class="address-choice">
                        <span class="flex gap-3 items-center">
                            <input type="radio" name="address_id" value="" @checked($showNew)>
                            <span class="font-medium text-slate-950">Enter a new address</span>
                        </span>
                    </label>
                </fieldset>
            @endif
            <div id="new-address" class="grid sm:grid-cols-2 gap-4 shop-card p-5 {{ $showNew ? '' : 'hidden' }}">
                <p class="sm:col-span-2 font-semibold text-slate-950">Delivery address</p>
                <label class="block sm:col-span-2 text-sm font-medium">Name
                    <input name="name" value="{{ old('name') }}" class="shop-field" autocomplete="name" maxlength="128">
                </label>
                <label class="block text-sm font-medium">Mobile
                    <input name="mobile_number" value="{{ old('mobile_number') }}" class="shop-field" inputmode="numeric" maxlength="10" autocomplete="tel" placeholder="10-digit mobile">
                </label>
                <label class="block text-sm font-medium">Pincode
                    <input name="pincode" value="{{ old('pincode') }}" class="shop-field" inputmode="numeric" maxlength="6" autocomplete="postal-code">
                </label>
                <label class="block sm:col-span-2 text-sm font-medium">Address
                    <input name="address" value="{{ old('address') }}" class="shop-field" autocomplete="street-address" maxlength="256">
                </label>
                <label class="block text-sm font-medium">Locality
                    <input name="locality" value="{{ old('locality') }}" class="shop-field" maxlength="256">
                </label>
                <label class="block text-sm font-medium">City
                    <input name="city" value="{{ old('city') }}" class="shop-field" maxlength="30" autocomplete="address-level2">
                </label>
                <label class="block sm:col-span-2 text-sm font-medium">State
                    <select name="state_id" class="shop-field">
                        <option value="">Select state</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <fieldset class="shop-card p-5 grid gap-2 text-sm">
                <legend class="font-semibold text-slate-950 px-1">Payment</legend>
                <label class="address-choice"><input type="radio" name="payment_method" value="cod" checked> Cash on delivery</label>
                <label class="text-stone-400"><input type="radio" disabled> UPI — not available yet</label>
                <label class="text-stone-400"><input type="radio" disabled> Card — not available yet</label>
                <label class="text-stone-400"><input type="radio" disabled> Net banking — not available yet</label>
            </fieldset>
            <button class="bg-[#c8a04e] text-[#1b1613] rounded-full px-5 py-3 font-semibold" type="submit">Place cash on delivery order</button>
        </form>
    </div>
    <aside class="shop-card p-5 h-fit lg:sticky lg:top-28">
        <h2 class="font-semibold text-slate-950 mb-3">Order summary</h2>
        @foreach($items as $item)
            <p class="flex justify-between gap-3 text-sm py-2 border-b border-stone-100">
                <span>{{ $item->product_title }} × {{ $item->qty }}</span>
                <span>₹{{ number_format($item->line_total, 0) }}</span>
            </p>
        @endforeach
        <p class="flex justify-between text-sm pt-3"><span>Subtotal</span><span>₹{{ number_format($quote['subtotal'], 0) }}</span></p>
        <p class="flex justify-between text-sm"><span>Discount</span><span>₹{{ number_format($quote['discount'], 0) }}</span></p>
        <p class="flex justify-between text-sm"><span>{{ $quote['tax_label'] }}</span><span>₹{{ number_format($quote['tax'], 0) }}</span></p>
        <p class="mt-3 flex justify-between font-semibold rounded-xl bg-[#1b1613] text-white px-3 py-3"><span>Total</span><span class="text-[#c8a04e]">₹{{ number_format($quote['total'], 0) }}</span></p>
        <form method="post" action="{{ route('cart.coupon') }}" class="mt-4 flex gap-2">
            @csrf
            <input name="code" placeholder="Coupon" class="shop-field mt-0">
            <button class="text-sm font-semibold text-[#8a6a24]" type="submit">Apply</button>
        </form>
        @if($quote['coupon'])
            <form method="post" action="{{ route('cart.coupon.remove') }}" class="mt-2">@csrf @method('DELETE')<button class="text-sm" type="submit">Remove {{ $quote['coupon'] }}</button></form>
        @endif
    </aside>
</section>
<script>
document.querySelectorAll('[name=address_id]').forEach(function (choice) {
    choice.addEventListener('change', function () {
        const panel = document.getElementById('new-address');
        if (!panel) return;
        const show = choice.value === '';
        panel.classList.toggle('hidden', !show);
        panel.querySelectorAll('input, select').forEach(function (field) {
            field.required = show;
        });
    });
});
document.getElementById('new-address')?.querySelectorAll('input, select').forEach(function (field) {
    field.required = !document.getElementById('new-address').classList.contains('hidden');
});
</script>
@endsection
