@extends('layout.app')
@section('content')
<!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2 class="text-white mb-5">Products Integrated Gulf Biosystems</h2>
                    <p class="text-white/90 my-3 max-w-2xl">For over two decades, Integrated Gulf Biosystems has
                        empowered laboratories, research institutions, and biotechnology companies with advanced
                        genomic, molecular, and analytical technologies across the MENA region.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="p-12">
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <!-- Product Card -->
             @foreach($products as $product)
            <div class="card p-4 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="product-img bg-gray-200 h-60 overflow-hidden">
                    <figure>
                        @if(isset($product->productImages[0]) && !empty($product->productImages[0]))
                        <img src="{{ asset('storage/' . $product->productImages[0]['image_path']) }}" class="w-full object-cover">
                         @endif
                    </figure>

                </div>
                <div class="product-description p-4">
                    <h2>{{ strlen($product->title) > 20 ? substr($product->title, 0, 20) . '...' : $product->title }}</h2>
                    <p class="text-gray-600">{{ strlen($product->short_description) > 66 ? substr($product->short_description, 0, 66) . '...' : $product->short_description }} </p>
                    <div class="flex items-center justify-end">
                        <a href="{{ url('product-detail/' . $product->slug) }}" class="btn uppercase">Read More</a>
                    </div>

                </div>
            </div>
            @endforeach

          
        </div>
    </div>
@endsection