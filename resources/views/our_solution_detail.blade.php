@extends('layout.app')

@section('content')
<section class="bg-stone-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-semibold">{{ $category->name }}</h1>
        <p class="text-stone-300 mt-2">{{ $products->count() }} products</p>
    </div>
</section>

@if($subcategories->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 pt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    @foreach($subcategories as $subcategory)
        @if((int) $subcategory->category_id === (int) $category->id)
            <a href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}" class="bg-white border border-stone-200 rounded-xl overflow-hidden text-sm hover:border-amber-500">
                @if(!empty($subcategory->image))
                    <img src="{{ asset(str_contains($subcategory->image, '/') ? $subcategory->image : 'uploads/subcategories/'.$subcategory->image) }}" alt="" class="h-24 w-full object-cover">
                @endif
                <span class="block px-3 py-2">{{ $subcategory->name }}</span>
            </a>
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
