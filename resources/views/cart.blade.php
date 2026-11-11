@extends('layout.app')

@section('content')
<section class="catalogue-hero cart-hero">
    <div class="max-w-7xl mx-auto px-4">
        <div class="cart-hero-line">
            <div class="cart-hero-title">
                <nav class="catalogue-crumb" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}">Home</a>
                    <span aria-hidden="true">/</span>
                    <span>Cart</span>
                </nav>
                <h1>Your cart</h1>
            </div>
            <p class="cart-hero-count">
                @if($items->isEmpty())
                    Empty
                @else
                    {{ $items->sum('qty') }} {{ \Illuminate\Support\Str::plural('item', $items->sum('qty')) }}
                @endif
            </p>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8">
    @if(session('success'))
        <p class="mb-4 rounded-xl bg-green-50 text-green-800 px-4 py-3">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <p class="mb-4 rounded-xl bg-red-50 text-red-800 px-4 py-3">{{ $errors->first() }}</p>
    @endif

    @if($items->isEmpty())
        <div class="shop-card max-w-xl mx-auto text-center px-6 py-12">
            <p class="text-lg font-semibold text-[#1b1613]">Your cart is empty.</p>
            <p class="mt-2 text-stone-600">Browse the catalogue and add a product when you are ready.</p>
            <a href="{{ url('/products') }}" class="site-gold-btn inline-flex mt-6">Continue shopping</a>
        </div>
    @else
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-6">
            <div class="cart-list">
                @foreach($items as $item)
                    @php
                        $image = null;
                        if (! empty($item->image_name) && is_file(public_path('uploads/products/'.$item->image_name))) {
                            $image = asset('uploads/products/'.$item->image_name);
                        }
                    @endphp
                    <article class="cart-item">
                        <a href="{{ \App\Support\CataloguePath::product($item) }}" class="block">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $item->product_title }}" class="cart-thumb">
                            @else
                                <span class="cart-thumb cart-thumb-empty">Image coming soon</span>
                            @endif
                        </a>
                        <div>
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <a href="{{ \App\Support\CataloguePath::product($item) }}" class="font-semibold text-[#1b1613]">{{ $item->product_title }}</a>
                                    <p class="text-sm text-stone-500 mt-1">
                                        ₹{{ number_format($item->price, 0) }} each
                                        @if($item->color_name) · {{ $item->color_name }} @endif
                                        @if(! empty($item->sku_id)) · SKU {{ $item->sku_id }} @endif
                                    </p>
                                </div>
                                <p class="font-semibold text-[#1b1613]">₹{{ number_format($item->line_total, 0) }}</p>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-4">
                                <form action="{{ route('cart.update') }}" method="post" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                                    <label class="text-sm text-stone-600" for="qty-{{ $item->id }}">Quantity</label>
                                    <input id="qty-{{ $item->id }}" name="quantity" type="number" min="1" max="10" value="{{ $item->qty }}" class="cart-qty">
                                    <button class="cart-update" type="submit">Update</button>
                                </form>
                                <form action="{{ route('cart.remove', $item->id) }}" method="post">
                                    @csrf
                                    <button class="cart-remove" type="submit">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <aside class="shop-card cart-summary p-5 h-fit lg:sticky lg:top-28">
                <h2 class="text-lg font-semibold text-[#1b1613]">Order summary</h2>
                <p class="mt-4 flex justify-between text-sm"><span>Subtotal</span><span>₹{{ number_format($quote['subtotal'], 0) }}</span></p>
                <p class="mt-2 flex justify-between text-sm"><span>Discount</span><span>₹{{ number_format($quote['discount'], 0) }}</span></p>
                <p class="mt-2 flex justify-between text-sm"><span>{{ $quote['tax_label'] }}</span><span>₹{{ number_format($quote['tax'], 0) }}</span></p>
                <p class="cart-total"><span>Total</span><strong>₹{{ number_format($quote['total'], 0) }}</strong></p>
                <p class="text-sm text-stone-500 mt-3">Cash on delivery is available. The payable total is confirmed at checkout.</p>
                {{-- Coupon entry is hidden until coupons are ready to offer.
                <form method="post" action="{{ route('cart.coupon') }}" class="mt-4 flex gap-2">
                    @csrf
                    <input name="code" value="{{ $quote['coupon'] }}" placeholder="Coupon" class="shop-field mt-0" maxlength="40">
                    <button class="cart-update" type="submit">Apply</button>
                </form>
                @if($quote['coupon'])
                    <form method="post" action="{{ route('cart.coupon.remove') }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="cart-remove" type="submit">Remove {{ $quote['coupon'] }}</button>
                    </form>
                @endif
                @if($quote['coupon_error'])
                    <p class="mt-2 text-sm text-red-700">{{ $quote['coupon_error'] }}</p>
                @endif
                --}}
                <a href="{{ route('checkout') }}" class="cart-checkout">Checkout</a>
                <a href="{{ url('/products') }}" class="catalogue-clear mt-3">Continue shopping</a>
            </aside>
        </div>
    @endif
</section>
@endsection
