@extends('layout.app')

@section('content')
<section class="bg-slate-950">
    <a href="{{ url('/products') }}" class="block">
        <img src="{{ asset('front/images/banner.jpg') }}" alt="Car accessories. We add charm to your vehicle." class="w-full h-auto max-h-[420px] object-cover object-center">
    </a>
</section>

<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl font-semibold">Categories</h2>
        <a href="{{ url('/products') }}" class="text-sm text-amber-800">View all products</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($headerCategories as $category)
            <a href="{{ url('/our-solutions/' . $category->id) }}" class="bg-white border border-stone-200 rounded-xl p-5 hover:border-amber-500">
                <h3 class="font-semibold">{{ $category->name }}</h3>
                <p class="text-sm text-stone-500 mt-2">{{ $category->subcategories->count() }} {{ \Illuminate\Support\Str::plural('range', $category->subcategories->count()) }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 pb-12">
    <h2 class="text-2xl font-semibold mb-6">Featured products</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($featured as $product)
            @include('partials.product_card', ['product' => $product])
        @empty
            <p class="text-stone-500">No featured products are active.</p>
        @endforelse
    </div>
</section>
@endsection
