@extends('layout.app')

@php
    $pageTitle = $subcategory->name ?? $category->name ?? 'Car and bike accessories';
    if (request('q')) {
        $pageTitle = 'Results for "'.request('q').'"';
    }
    $heroImage = null;
    if (! empty($subcategory?->image)) {
        $heroFile = str_contains($subcategory->image, '/') ? $subcategory->image : 'uploads/subcategories/'.$subcategory->image;
        if (is_file(public_path($heroFile))) {
            $heroImage = asset($heroFile);
        }
    } elseif (! empty($category?->image) && ! $subcategory) {
        $heroFile = str_contains($category->image, '/') ? $category->image : 'uploads/categories/'.$category->image;
        if (is_file(public_path($heroFile))) {
            $heroImage = asset($heroFile);
        }
    }
    $kept = array_filter(request()->only(['q', 'category', 'subcategory']), fn ($value) => $value !== null && $value !== '');
    $clearUrl = url('/products').($kept ? '?'.http_build_query($kept) : '');
    $hasExtra = request()->filled('price_min') || request()->filled('price_max') || request()->filled('color') || request('stock') === '1' || (request()->filled('sort') && request('sort') !== 'name');
@endphp

@section('content')
<section class="catalogue-hero">
    <div class="max-w-7xl mx-auto px-4 py-10 catalogue-hero-inner {{ $heroImage ? 'has-image' : '' }}">
        <div>
            <nav class="catalogue-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span aria-hidden="true">/</span>
                @if($category)
                    @if($subcategory)
                        <a href="{{ \App\Support\CataloguePath::category($category) }}">{{ $category->name }}</a>
                        <span aria-hidden="true">/</span>
                        <span>{{ $subcategory->name }}</span>
                    @else
                        <span>{{ $category->name }}</span>
                    @endif
                @else
                    <span>Shop</span>
                @endif
            </nav>
            <p class="catalogue-kicker">Cranzo catalogue</p>
            <h1>{{ $pageTitle }}</h1>
            <p class="catalogue-lead">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</p>
        </div>
        @if($heroImage)
            <img src="{{ $heroImage }}" alt="" class="catalogue-hero-image">
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[260px_minmax(0,1fr)] gap-6">
    <form method="get" class="shop-card catalogue-filters p-4 h-fit lg:sticky lg:top-28 grid gap-3">
        <h2 class="font-semibold text-lg">Filters</h2>
        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        @if(request('subcategory'))<input type="hidden" name="subcategory" value="{{ request('subcategory') }}">@endif
        <label class="text-sm font-medium">Minimum price<input name="price_min" inputmode="numeric" value="{{ request('price_min') }}" class="shop-field"></label>
        <label class="text-sm font-medium">Maximum price<input name="price_max" inputmode="numeric" value="{{ request('price_max') }}" class="shop-field"></label>
        <label class="text-sm font-medium">Colour
            <select name="color" class="shop-field">
                <option value="">Any colour</option>
                @foreach($colors as $color)
                    <option value="{{ $color->color_id }}" @selected((string) request('color') === (string) $color->color_id)>{{ $color->color_title }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm font-medium">Sort
            <select name="sort" class="shop-field">
                <option value="name" @selected(request('sort', 'name') === 'name')>Name</option>
                <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: low to high</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: high to low</option>
            </select>
        </label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="stock" value="1" @checked(request('stock') === '1')> In stock only</label>
        <button class="site-gold-btn catalogue-apply" type="submit">Apply filters</button>
        @if($hasExtra)
            <a class="catalogue-clear" href="{{ $clearUrl }}">Clear filters</a>
        @endif
        @if($category || $subcategory || request('q'))
            <a class="catalogue-clear" href="{{ url('/products') }}">View all products</a>
        @endif
    </form>
    <div>
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($products as $product)
                @include('partials.product_card', ['product' => $product])
            @empty
                <p class="shop-card p-6 text-stone-600 sm:col-span-2 xl:col-span-3">No products matched these filters. <a class="catalogue-clear" href="{{ url('/products') }}">View all products</a></p>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links('pagination::tailwind') }}</div>
    </div>
</section>
@endsection
