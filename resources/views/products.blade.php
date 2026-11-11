@extends('layout.app')

@section('content')
<section class="bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <p class="text-xs uppercase tracking-wide text-amber-300">Shop</p>
        <h1 class="text-3xl font-semibold mt-1">
            @if(request('q'))
                Results for “{{ request('q') }}”
            @else
                Car and bike accessories
            @endif
        </h1>
        <p class="text-stone-300 mt-2">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[260px_minmax(0,1fr)] gap-6">
    <form method="get" class="shop-card p-4 h-fit lg:sticky lg:top-28 grid gap-3">
        <h2 class="font-semibold">Filters</h2>
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
        <button class="bg-blue-700 text-white rounded-full px-4 py-2.5 font-medium" type="submit">Apply filters</button>
        <a class="text-sm text-blue-700 text-center" href="{{ url('/products') }}">Clear filters</a>
    </form>
    <div>
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($products as $product)
                @include('partials.product_card', ['product' => $product])
            @empty
                <p class="shop-card p-6 text-stone-500 sm:col-span-2 xl:col-span-3">No products matched these filters. <a class="text-blue-700" href="{{ url('/products') }}">View all products</a></p>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links('pagination::tailwind') }}</div>
    </div>
</section>
@endsection
