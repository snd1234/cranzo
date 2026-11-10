@extends('layout.app')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h1 class="text-3xl font-semibold">Checkout</h1>
        <p class="text-stone-600 mt-2">Cash on delivery. The total is calculated on the server from current product prices.</p>
        <form action="{{ route('checkout.place') }}" method="post" class="mt-6 grid sm:grid-cols-2 gap-4 bg-white border border-stone-200 rounded-xl p-5">
            @csrf
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
            <button class="sm:col-span-2 bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium" type="submit">Place cash on delivery order</button>
        </form>
    </div>
    <aside class="bg-white border border-stone-200 rounded-xl p-5 h-fit">
        <h2 class="font-semibold mb-3">Order summary</h2>
        @foreach($items as $item)
            <p class="flex justify-between gap-3 text-sm py-2 border-b border-stone-100">
                <span>{{ $item->product_title }} × {{ $item->qty }}</span>
                <span>₹{{ number_format($item->line_total, 0) }}</span>
            </p>
        @endforeach
        <p class="flex justify-between font-semibold pt-3"><span>Total</span><span>₹{{ number_format($total, 0) }}</span></p>
    </aside>
</section>
@endsection
