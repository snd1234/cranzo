@extends('layout.app')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <p class="text-xs font-medium uppercase tracking-wide text-blue-700">Cart · Details · Confirmation</p>
        <h1 class="text-3xl font-semibold mt-1">Checkout</h1>
        <p class="text-stone-600 mt-2">Cash on delivery. Prices, tax, and discounts are calculated on the server. UPI, cards, and net banking are shown but not available yet.</p>
        <form action="{{ route('checkout.place') }}" method="post" class="mt-6 grid sm:grid-cols-2 gap-4 bg-white border border-stone-200 rounded-xl p-5">
            @csrf
            @if($addresses->isNotEmpty())
                <label class="block sm:col-span-2">Saved address
                    <select name="address_id" class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
                        <option value="">Enter a new address below</option>
                        @foreach($addresses as $saved)
                            <option value="{{ $saved->id }}" @selected(old('address_id') == $saved->id)>{{ $saved->name }}, {{ $saved->city }} {{ $saved->pincode }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <label class="block sm:col-span-2">Name
                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block">Mobile
                <input name="mobile_number" value="{{ old('mobile_number') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block">Pincode
                <input name="pincode" value="{{ old('pincode') }}" required pattern="[0-9]{6}" class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block sm:col-span-2">Address
                <input name="address" value="{{ old('address') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block">Locality
                <input name="locality" value="{{ old('locality') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block">City
                <input name="city" value="{{ old('city') }}" required maxlength="30" class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
            </label>
            <label class="block sm:col-span-2">State
                <select name="state_id" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
                    <option value="">Select state</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>{{ $state->state_name }}</option>
                    @endforeach
                </select>
            </label>
            @if($errors->any())
                <div class="sm:col-span-2 text-red-700 text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif
            <fieldset class="sm:col-span-2 grid gap-2 text-sm">
                <legend class="font-medium">Payment</legend>
                <label><input type="radio" name="payment_method" value="cod" checked> Cash on delivery</label>
                <label class="text-stone-400"><input type="radio" disabled> UPI — coming soon</label>
                <label class="text-stone-400"><input type="radio" disabled> Card — coming soon</label>
                <label class="text-stone-400"><input type="radio" disabled> Net banking — coming soon</label>
            </fieldset>
            <button class="sm:col-span-2 bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium" type="submit">Place cash on delivery order</button>
        </form>
        <script>
        document.querySelector('[name=address_id]')?.addEventListener('change', function () {
            const need = this.value === '';
            this.form.querySelectorAll('[name=name],[name=mobile_number],[name=address],[name=locality],[name=city],[name=state_id],[name=pincode]').forEach(function (field) {
                field.required = need;
            });
        });
        </script>
    </div>
    <aside class="bg-white border border-stone-200 rounded-xl p-5 h-fit">
        <h2 class="font-semibold mb-3">Order summary</h2>
        @foreach($items as $item)
            <p class="flex justify-between gap-3 text-sm py-2 border-b border-stone-100">
                <span>{{ $item->product_title }} × {{ $item->qty }}</span>
                <span>₹{{ number_format($item->line_total, 0) }}</span>
            </p>
        @endforeach
        <p class="flex justify-between text-sm pt-3"><span>Subtotal</span><span>₹{{ number_format($quote['subtotal'], 0) }}</span></p>
        <p class="flex justify-between text-sm"><span>Discount</span><span>₹{{ number_format($quote['discount'], 0) }}</span></p>
        <p class="flex justify-between text-sm"><span>{{ $quote['tax_label'] }}</span><span>₹{{ number_format($quote['tax'], 0) }}</span></p>
        <p class="flex justify-between font-semibold pt-3"><span>Total</span><span>₹{{ number_format($quote['total'], 0) }}</span></p>
        <form method="post" action="{{ route('cart.coupon') }}" class="mt-4 flex gap-2">
            @csrf
            <input name="code" placeholder="Coupon" class="flex-1 border rounded-full px-3 py-2">
            <button class="text-sm underline" type="submit">Apply</button>
        </form>
        @if($quote['coupon'])
            <form method="post" action="{{ route('cart.coupon.remove') }}" class="mt-2">@csrf @method('DELETE')<button class="text-sm" type="submit">Remove {{ $quote['coupon'] }}</button></form>
        @endif
    </aside>
</section>
@endsection
