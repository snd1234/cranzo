@extends('layout.app')

@section('content')
@php
    $image = $productImages->first();
    $imagePath = $image ? ($image->image ?: $image->thumbnail) : null;
    $selling = (float) $product->discounted_price;
    $regular = (float) $product->actual_price;
    $off = $regular > $selling && $regular > 0 ? (int) round((($regular - $selling) / $regular) * 100) : null;
@endphp
<section class="max-w-7xl mx-auto px-4 py-6 md:py-10 grid lg:grid-cols-2 gap-8">
    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden min-h-64 flex items-center justify-center">
        @if($imagePath)
            <img src="{{ asset('uploads/products/' . $imagePath) }}" alt="{{ $product->product_title }}" class="w-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
            <p class="hidden text-stone-400 p-10">Image coming soon</p>
        @else
            <p class="text-stone-400 p-10">Image coming soon</p>
        @endif
    </div>
    <div>
        <p class="text-sm text-stone-500">SKU {{ $product->sku_id }}</p>
        <h1 class="text-2xl md:text-3xl font-semibold mt-2">{{ $product->product_title }}</h1>
        <div class="mt-4 flex flex-wrap items-baseline gap-3">
            <span class="text-3xl font-semibold">₹{{ number_format($selling, 0) }}</span>
            @if($regular > $selling)
                <span class="text-stone-400 line-through">₹{{ number_format($regular, 0) }}</span>
                @if($off)<span class="text-amber-700 font-medium">{{ $off }}% off</span>@endif
            @endif
        </div>
        <p class="mt-3 text-sm {{ $stock > 0 ? 'text-green-700' : 'text-red-700' }}">{{ $stock > 0 ? $stock.' in stock' : 'Out of stock' }}</p>
        <p class="mt-1 text-sm text-stone-600">{{ $product->cod_available ? 'Cash on delivery is available.' : 'Cash on delivery can still be selected at checkout.' }}</p>

        @if($stock > 0)
            <form action="{{ route('cart.add') }}" method="post" class="mt-6 flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label class="sr-only" for="quantity">Quantity</label>
                <input id="quantity" name="quantity" type="number" min="1" max="{{ min(10, $stock) }}" value="1" class="w-full sm:w-24 border border-stone-300 rounded-full px-4 py-3">
                <button class="flex-1 bg-stone-950 text-white rounded-full px-6 py-3 font-medium" type="submit">Add to cart</button>
                <button class="flex-1 bg-amber-500 text-stone-950 rounded-full px-6 py-3 font-medium" type="submit" name="buy_now" value="1">Buy now</button>
            </form>
        @endif
        @if($errors->any())
            <p class="text-red-700 text-sm mt-3">{{ $errors->first() }}</p>
        @endif

        <div class="mt-6 text-stone-700 leading-7">
            {!! strip_tags($product->description, '<p><br><ul><ol><li><strong><em>') !!}
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pb-12">
    <h2 class="text-xl font-semibold mb-4">Related products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($related as $item)
            @include('partials.product_card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->product_title,
    'sku' => $product->sku_id,
    'description' => \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 300, ''),
    'offers' => [
        '@type' => 'Offer',
        'priceCurrency' => 'INR',
        'price' => number_format((float) $product->discounted_price, 2, '.', ''),
        'availability' => $stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'url' => \App\Support\CataloguePath::product($product),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection
