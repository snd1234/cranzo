@extends('layout.app')

@section('content')
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-white">{{ $product->product_title }}</h2>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-10">
        <div>
            @php $image = $productImages->first(); $imagePath = $image->image ?? $image->thumbnail ?? $image->image_path ?? null; @endphp
            @if($imagePath)
                <img src="{{ asset(config('uploads.path') . $imagePath) }}" alt="{{ $product->product_title }}" class="w-full rounded-xl shadow object-cover h-96">
            @endif
        </div>
        <div>
            <h3 class="text-2xl font-semibold mb-4">{{ $product->product_title }}</h3>
            <p class="text-gray-700 leading-7">{{ $product->description ?? 'More details will be available soon.' }}</p>

            @if($productCatalogs->isNotEmpty())
                <div class="mt-8">
                    <h4 class="font-semibold mb-3">Product images</h4>
                    <ul class="space-y-2">
                        @foreach($productCatalogs as $catalog)
                            <li><span class="text-indigo-600">{{ $catalog->image ?? $catalog->thumbnail }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
