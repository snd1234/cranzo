@extends('layout.app')
@section('content')
 
<!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image:  url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2>Equipment Integrated Gulf Biosystems</h2>
                    <p class="text-white/90 my-3 max-w-2xl">For over two decades, Integrated Gulf Biosystems has
                        empowered laboratories, research institutions.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="p-12">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-6 mb-7">

            <!-- Product Info -->
            <div
                class="product-info p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300 min-h-[520px]">
                <div class="page-heading relative mb-4">
                    <span class="bg-white  uppercase">{{ $category->name }}</span>
                </div>
                <p class="text-gray-700 mb-4">{{ $category->description }} </p>
                <div class="mt-5">
                    <h4 class="mb-5">Highlight of the Product Offering</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <?php foreach($products as $product){ 
                            ?>
                        <div class="solution-detail-card text-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                            <div class="product-img">
                                @if(isset($product->productImages[0]) && !empty($product->productImages[0]))
                                <a href="{{ url('product-detail/' . $product->slug) }}" target="_self">
                                  <img src="{{ asset('storage/' . $product->productImages[0]['image_path']) }}" alt="{{ $product->title }}" class="mx-auto">
                                </a>
                               @endif
                            </div>
                            <div class="product-title">
                              <a href="{{ url('product-detail/' . $product->slug) }}" target="_self">{{ strlen($product->title) > 40 ? substr($product->title, 0, 40) . '...' : $product->title }} </a>
                            </div>
                        </div>
                        <?php } ?>
                          
                          
                    </div>
                </div>

            </div>


            <!-- Side column -->
            <aside class="flex flex-col gap-4">
                <div class="p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Browse by</span>
                    </div>
                    <div class="accordion">
                        @if($subcategories->isNotEmpty())
                            @foreach($subcategories as $sub)
                             @if($sub->products->isNotEmpty())
                            <div class="acc-item">
                                <div class="acc-title">{{ $sub->name }}</div>
                                    <div class="acc-content">
                                        <div class="flex flex-col space-y-3">
                                            @foreach($sub->products as $product)
                                            <a href="{{ url('product-detail/' . $product->slug) }}" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i> {{ $product->title }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </aside>

        </div>
    </div>



@endsection