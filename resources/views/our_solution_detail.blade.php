@extends('layout.app')

@php
    $categoryImage = null;
    if (! empty($category->image)) {
        $categoryFile = str_contains($category->image, '/') ? $category->image : 'uploads/categories/'.$category->image;
        if (is_file(public_path($categoryFile))) {
            $categoryImage = asset($categoryFile);
        }
    }
    $ranges = $subcategories->filter(fn ($subcategory) => (int) $subcategory->category_id === (int) $category->id);
@endphp

@section('content')
<section class="catalogue-hero">
    <div class="max-w-7xl mx-auto px-4 py-10 catalogue-hero-inner {{ $categoryImage ? 'has-image' : '' }}">
        <div>
            <nav class="catalogue-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>{{ $category->name }}</span>
            </nav>
            <p class="catalogue-kicker">Cranzo catalogue</p>
            <h1>{{ $category->name }}</h1>
            <p class="catalogue-lead">{{ $products->count() }} {{ \Illuminate\Support\Str::plural('product', $products->count()) }} in this category.</p>
        </div>
        @if($categoryImage)
            <img src="{{ $categoryImage }}" alt="{{ $category->name }}" class="catalogue-hero-image">
        @endif
    </div>
</section>

@if($ranges->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pt-10">
    <h2 class="catalogue-block-title mb-4">Shop by range</h2>
    <div class="range-grid">
        @foreach($ranges as $subcategory)
            @php
                $rangeImage = null;
                if (! empty($subcategory->image)) {
                    $rangeFile = str_contains($subcategory->image, '/') ? $subcategory->image : 'uploads/subcategories/'.$subcategory->image;
                    if (is_file(public_path($rangeFile))) {
                        $rangeImage = asset($rangeFile);
                    }
                }
            @endphp
            <a href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}" class="range-card">
                @if($rangeImage)
                    <img src="{{ $rangeImage }}" alt="">
                @else
                    <span class="range-mark" aria-hidden="true">{{ strtoupper(substr(trim($subcategory->name), 0, 1)) }}</span>
                @endif
                <span>{{ $subcategory->name }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="max-w-7xl mx-auto px-4 py-10">
    <h2 class="catalogue-block-title mb-6">Products</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            @include('partials.product_card', ['product' => $product])
        @empty
            <p class="shop-card p-6 text-stone-600 sm:col-span-2 lg:col-span-3">No products are listed in this category.</p>
        @endforelse
    </div>
</section>
@endsection
