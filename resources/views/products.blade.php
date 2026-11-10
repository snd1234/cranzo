@extends('layout.app')

@section('content')
<section class="bg-stone-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-semibold">Shop</h1>
        <p class="text-stone-300 mt-2">
            @if(request('q'))
                Results for “{{ request('q') }}”
            @else
                {{ $products->total() }} products
            @endif
        </p>
        <form method="get" class="mt-4">
            @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
            @if(request('subcategory'))<input type="hidden" name="subcategory" value="{{ request('subcategory') }}">@endif
            <input name="price_min" value="{{ request('price_min') }}" placeholder="Min price" class="rounded-full px-4 py-2 text-stone-900 w-28">
            <input name="price_max" value="{{ request('price_max') }}" placeholder="Max price" class="rounded-full px-4 py-2 text-stone-900 w-28">
            <select name="color" class="rounded-full px-4 py-2 text-stone-900">
                <option value="">Colour</option>
                @foreach($colors as $color)
                    <option value="{{ $color->color_id }}" @selected(request('color') == $color->color_id)>{{ $color->color_title }}</option>
                @endforeach
            </select>
            <label class="text-sm"><input type="checkbox" name="stock" value="1" @checked(request('stock') === '1')> In stock</label>
            <select name="sort" class="rounded-full px-4 py-2 text-stone-900" onchange="this.form.submit()">
                <option value="name" @selected(request('sort', 'name') === 'name')>Name</option>
                <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: low to high</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: high to low</option>
            </select>
            <button class="rounded-full bg-white text-stone-900 px-4 py-2" type="submit">Apply</button>
            <a class="text-sm text-stone-300" href="{{ url('/products') }}">Clear</a>
        </form>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            @include('partials.product_card', ['product' => $product])
        @empty
            <p class="text-stone-500 sm:col-span-3">No products matched this search.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
</section>
@endsection
