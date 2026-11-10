@extends('layout.app')

@section('content')
<section class="bg-stone-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-semibold">{{ $category->name }}</h1>
        <p class="text-stone-300 mt-2">{{ $products->count() }} products</p>
    </div>
</section>

@if($subcategories->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pt-8 flex gap-3 overflow-x-auto">
    @foreach($subcategories as $subcategory)
        @if((int) $subcategory->category_id === (int) $category->id)
            <a href="{{ url('/products?subcategory=' . $subcategory->id) }}" class="whitespace-nowrap bg-white border border-stone-200 rounded-full px-4 py-2 text-sm">{{ $subcategory->name }}</a>
        @endif
    @endforeach
</section>
@endif

<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            @include('partials.product_card', ['product' => $product])
        @empty
            <p class="text-stone-500">No products are listed in this category.</p>
        @endforelse
    </div>
</section>
@endsection
