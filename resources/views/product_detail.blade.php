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
        @if($colors->count() > 1)
            <div class="mt-4 flex flex-wrap gap-2" id="color-options">
                @foreach($colors as $color)
                    <button type="button" class="border rounded-full px-3 py-1 text-sm" data-color="{{ $color->color_id }}" data-stock="{{ (int) $color->product_quantity }}">{{ $color->color_title ?: 'Option' }} ({{ (int) $color->product_quantity }})</button>
                @endforeach
            </div>
        @endif
        <p class="mt-3 text-sm {{ $stock > 0 ? 'text-green-700' : 'text-red-700' }}" id="stock-label">{{ $stock > 0 ? $stock.' in stock' : 'Out of stock' }}</p>
        <p class="mt-1 text-sm text-stone-600">{{ $product->cod_available ? 'Cash on delivery is available.' : 'Cash on delivery can still be selected at checkout.' }}</p>

        @if($stock > 0)
            <form action="{{ route('cart.add') }}" method="post" class="mt-6 flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="color_id" id="color-id" value="{{ $colors->count() === 1 ? $colors->first()->color_id : '' }}">
                <label class="sr-only" for="quantity">Quantity</label>
                <input id="quantity" name="quantity" type="number" min="1" max="{{ min(10, $stock) }}" value="1" class="w-full sm:w-24 border border-stone-300 rounded-full px-4 py-3">
                <button class="flex-1 bg-stone-950 text-white rounded-full px-6 py-3 font-medium" type="submit">Add to cart</button>
                <button class="flex-1 bg-amber-500 text-stone-950 rounded-full px-6 py-3 font-medium" type="submit" name="buy_now" value="1">Buy now</button>
            </form>
        @endif
        <form action="{{ route('wishlist.store') }}" method="post" class="mt-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button class="text-sm underline" type="submit">Save to wishlist</button>
        </form>
        @if($errors->any())
            <p class="text-red-700 text-sm mt-3">{{ $errors->first() }}</p>
        @endif

        <div class="mt-6 text-stone-700 leading-7">
            {!! strip_tags($product->description, '<p><br><ul><ol><li><strong><em>') !!}
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 pb-8">
    <h2 class="text-xl font-semibold mb-3">Reviews</h2>
    @forelse($reviews as $review)
        <article class="bg-white border border-stone-200 rounded-xl p-4 mb-3">
            <p class="font-medium">{{ $review->review_title }} · {{ $review->rating }}/5</p>
            <p class="text-stone-600 mt-1">{{ $review->comments }}</p>
        </article>
    @empty
        <p class="text-stone-500">No published reviews yet.</p>
    @endforelse
    @auth
        <form method="post" action="{{ route('reviews.store') }}" class="mt-4 bg-white border border-stone-200 rounded-xl p-4 grid gap-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <label>Rating
                <select name="rating" class="mt-1 w-full border rounded-lg px-3 py-2"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>
            </label>
            <label>Title<input name="review_title" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
            <label>Review<textarea name="comments" class="mt-1 w-full border rounded-lg px-3 py-2" required></textarea></label>
            <button class="bg-stone-900 text-white rounded-full px-4 py-2 w-fit" type="submit">Submit review</button>
        </form>
    @endauth
</section>
@if($recent->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pb-8">
    <h2 class="text-xl font-semibold mb-4">Recently viewed</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recent as $item)
            @include('partials.product_card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif
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
<script>
document.querySelectorAll('#color-options button').forEach(function (button) {
    button.addEventListener('click', function () {
        const id = document.getElementById('color-id');
        const label = document.getElementById('stock-label');
        if (id) id.value = button.dataset.color;
        if (label) label.textContent = Number(button.dataset.stock) > 0 ? button.dataset.stock + ' in stock' : 'Out of stock';
    });
});
</script>
@endsection
