@extends('layout.app')
@section('content')
<section class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold">Wishlist</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @forelse($products as $product)
            <div>
                @include('partials.product_card', ['product' => $product])
                <div class="flex gap-3 mt-2 text-sm">
                    <form method="post" action="{{ route('wishlist.move', $product->id) }}">@csrf<button type="submit">Move to cart</button></form>
                    <form method="post" action="{{ route('wishlist.destroy', $product->id) }}">@csrf @method('DELETE')<button type="submit">Remove</button></form>
                </div>
            </div>
        @empty
            <p class="text-stone-500">Your wishlist is empty.</p>
        @endforelse
    </div>
</section>
@endsection
