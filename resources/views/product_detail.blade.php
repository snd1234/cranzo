@extends('layout.app')

@section('content')
@php
    $gallery = $productImages->map(function ($image) {
        $image->file = $image->image ?: $image->thumbnail;
        $image->ready = $image->file && is_file(public_path('uploads/products/'.$image->file));
        return $image;
    })->filter(fn ($image) => $image->ready)->values();
    $hero = $gallery->first();
    $selling = (float) $product->discounted_price;
    $regular = (float) $product->actual_price;
    $off = $regular > $selling && $regular > 0 ? (int) round((($regular - $selling) / $regular) * 100) : null;
    $selectedColor = $colors->first(fn ($color) => (int) $color->product_quantity > 0) ?: $colors->first();
    $shownStock = $selectedColor ? (int) $selectedColor->product_quantity : $stock;
@endphp
<section class="bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <nav class="text-sm text-stone-500 mb-5 flex flex-wrap gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-slate-900">Home</a>
            <span aria-hidden="true">/</span>
            @if($category)
                <a href="{{ \App\Support\CataloguePath::category($category) }}" class="hover:text-slate-900">{{ $category->name }}</a>
                <span aria-hidden="true">/</span>
            @endif
            @if($subcategory)
                <a href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}" class="hover:text-slate-900">{{ $subcategory->name }}</a>
                <span aria-hidden="true">/</span>
            @endif
            <span class="text-slate-800">{{ $product->product_title }}</span>
        </nav>
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            <div>
                <div class="shop-card overflow-hidden bg-white aspect-square flex items-center justify-center">
                    @if($hero)
                        <img id="product-photo" src="{{ asset('uploads/products/'.$hero->file) }}" alt="{{ $product->product_title }}" class="h-full w-full object-contain p-4">
                    @else
                        <p class="text-stone-400 p-10">Image coming soon</p>
                    @endif
                </div>
                @if($gallery->count() > 1)
                    <div class="mt-3 flex gap-2">
                        @foreach($gallery as $thumb)
                            <button type="button" data-gallery data-src="{{ asset('uploads/products/'.$thumb->file) }}" class="gallery-thumb shop-card overflow-hidden w-20 h-20 bg-white {{ $loop->first ? 'is-selected' : '' }}" aria-label="Show photo {{ $loop->iteration }}">
                                <img src="{{ asset('uploads/products/'.$thumb->file) }}" alt="" class="h-full w-full object-contain p-1">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="lg:pt-2">
                <p class="text-xs font-medium tracking-wide uppercase text-stone-500">SKU {{ $product->sku_id }}</p>
                <h1 class="text-2xl md:text-4xl font-semibold text-slate-950 mt-2 leading-tight">{{ $product->product_title }}</h1>
                <div class="mt-5 flex flex-wrap items-end gap-3">
                    <span class="text-4xl font-semibold text-slate-950">₹{{ number_format($selling, 0) }}</span>
                    @if($regular > $selling)
                        <span class="text-lg text-stone-400 line-through mb-1">₹{{ number_format($regular, 0) }}</span>
                        @if($off)<span class="mb-1 rounded-full bg-amber-100 text-amber-800 px-2.5 py-1 text-sm font-semibold">{{ $off }}% off</span>@endif
                    @endif
                </div>
                @if($colors->count() > 1)
                    <p class="mt-6 text-sm font-medium text-slate-900">Colour</p>
                    <div class="mt-2 flex flex-wrap gap-2" id="color-options">
                        @foreach($colors as $color)
                            <button type="button" class="color-option border border-stone-300 rounded-full px-4 py-2 text-sm {{ $selectedColor && $selectedColor->color_id == $color->color_id ? 'is-selected' : '' }}" data-color="{{ $color->color_id }}" data-stock="{{ (int) $color->product_quantity }}" aria-pressed="{{ $selectedColor && $selectedColor->color_id == $color->color_id ? 'true' : 'false' }}">{{ $color->color_title ?: 'Option' }} · {{ (int) $color->product_quantity }}</button>
                        @endforeach
                    </div>
                @endif
                <p class="mt-4 inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $shownStock > 0 ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-700' }}" id="stock-label">{{ $shownStock > 0 ? $shownStock.' in stock' : 'Out of stock' }}</p>
                <p class="mt-3 text-sm text-stone-600"><i class="fa-solid fa-truck text-stone-400 mr-2"></i>{{ $product->cod_available ? 'Cash on delivery is available.' : 'Cash on delivery can still be selected at checkout.' }}</p>

                @if($stock > 0)
                    <form action="{{ route('cart.add') }}" method="post" class="shop-card mt-6 p-4 grid gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="color_id" id="color-id" value="{{ $selectedColor->color_id ?? '' }}">
                        <label class="text-sm font-medium text-slate-800" for="quantity">Quantity
                            <input id="quantity" name="quantity" type="number" min="1" max="{{ min(10, max(1, $shownStock)) }}" value="1" class="shop-field sm:max-w-28">
                        </label>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <button class="bg-slate-950 text-white rounded-full px-6 py-3 font-medium" type="submit">Add to cart</button>
                            <button class="bg-amber-500 text-slate-950 rounded-full px-6 py-3 font-medium" type="submit" name="buy_now" value="1">Buy now</button>
                        </div>
                    </form>
                @endif
                <form action="{{ route('wishlist.store') }}" method="post" class="mt-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="inline-flex items-center gap-2 rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-medium" type="submit"><i class="fa-regular fa-heart"></i> Save to wishlist</button>
                </form>
                @if($errors->any())
                    <p class="text-red-700 text-sm mt-3">{{ $errors->first() }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8">
    <div class="shop-card p-5 md:p-8">
        <h2 class="text-xl font-semibold text-slate-950">Description</h2>
        <div class="cms-prose mt-2 text-stone-700">
            {!! \App\Support\HtmlCleaner::clean($product->description) !!}
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 pb-8">
    <div class="shop-card p-5 md:p-8">
        <h2 class="text-xl font-semibold text-slate-950">Reviews</h2>
        @forelse($reviews as $review)
            <article class="border-t border-stone-200 py-4 first:border-t-0">
                <p class="font-medium text-slate-900">{{ $review->review_title }} <span class="text-amber-700 font-normal">· {{ $review->rating }}/5</span></p>
                <p class="text-stone-600 mt-1">{{ $review->comments }}</p>
            </article>
        @empty
            <p class="text-stone-500 mt-3">No published reviews yet.</p>
        @endforelse
        @auth
            <form method="post" action="{{ route('reviews.store') }}" class="mt-4 grid gap-3 border-t border-stone-200 pt-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label class="text-sm font-medium">Rating
                    <select name="rating" class="shop-field"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>
                </label>
                <label class="text-sm font-medium">Title<input name="review_title" class="shop-field" required></label>
                <label class="text-sm font-medium">Review<textarea name="comments" class="shop-field" required></textarea></label>
                <button class="bg-slate-950 text-white rounded-full px-5 py-2.5 w-fit" type="submit">Submit review</button>
            </form>
        @endauth
    </div>
</section>
@if($recent->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pb-8">
    <h2 class="text-xl font-semibold text-slate-950 mb-4">Recently viewed</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recent as $item)
            @include('partials.product_card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif
@if($related->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pb-12">
    <h2 class="text-xl font-semibold text-slate-950 mb-4">More in this category</h2>
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
