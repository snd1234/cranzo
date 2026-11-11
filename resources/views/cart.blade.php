@extends('layout.app')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold">Your cart</h1>
    @if(session('success'))
        <p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>
    @endif
    @if($items->isEmpty())
        <p class="mt-8 text-stone-600">Your cart is empty.</p>
        <a href="{{ url('/products') }}" class="inline-flex mt-4 bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium">Continue shopping</a>
    @else
        <div class="mt-6 grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                    <article class="bg-white border border-stone-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1">
                            <a href="{{ \App\Support\CataloguePath::product($item) }}" class="font-semibold">{{ $item->product_title }}</a>
                            <p class="text-sm text-stone-500 mt-1">₹{{ number_format($item->price, 0) }} each @if($item->color_name) · {{ $item->color_name }} @endif</p>
                        </div>
                        <form action="{{ route('cart.update') }}" method="post" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <input name="quantity" type="number" min="1" max="10" value="{{ $item->qty }}" class="w-20 border border-stone-300 rounded-full px-3 py-2">
                            <button class="text-sm underline" type="submit">Update</button>
                        </form>
                        <p class="font-semibold sm:w-24 sm:text-right">₹{{ number_format($item->line_total, 0) }}</p>
                        <form action="{{ route('cart.remove', $item->id) }}" method="post">
                            @csrf
                            <button class="text-sm text-red-700" type="submit">Remove</button>
                        </form>
                    </article>
                @endforeach
            </div>
            <aside class="bg-white border border-stone-200 rounded-xl p-5 h-fit">
                <p class="flex justify-between text-lg"><span>Total</span><span>₹{{ number_format($total, 0) }}</span></p>
                <p class="text-sm text-stone-500 mt-2">Tax, coupons, and the payable total are calculated at checkout. Cash on delivery is available.</p>
                <a href="{{ route('checkout') }}" class="mt-5 block text-center bg-stone-950 text-white rounded-full px-5 py-3 font-medium">Checkout</a>
            </aside>
        </div>
    @endif
</section>
@endsection
