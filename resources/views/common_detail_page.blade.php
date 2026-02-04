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
                    <span class="bg-white  uppercase">Equipment</span>
                </div>
                
                <p class="text-gray-700 text-justify mb-5">
                    <img src="{{ asset('front/images/two.jpg') }}" class="common-page-img">
                    Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC
                    columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but
                    also special application columns like bio inert and SEC . If your application is critical need some
                    special columns which you need to custom designed we can help you to get custom column of your
                    requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality
                    good in price. 

                    Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC
                    columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but
                    also special application columns like bio inert and SEC . If your application is critical need some
                    special columns which you need to custom designed we can help you to get custom column of your
                    requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality
                    good in price. 
                   
                </p>

                <div class="mb-5">
                    <h4 class="mb-3">Gulf Bio Analytical provide huge</h4>
                    <p class="text-gray-700 mb-2">
                        Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC
                    columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but
                    also special application columns like bio inert and SEC . If your application is critical need some
                    special columns which you need to custom designed we can help you to get custom column of your
                    requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality
                    good in price. 
                    </p>
                    <p class="text-gray-700 mb-2">
                        Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC
                    columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but
                    also special application columns like bio inert and SEC . If your application is critical need some
                    special columns which you need to custom designed we can help you to get custom column of your
                    requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality
                    good in price. 
                    </p>
                </div>
                
                
            </div>


            <!-- Side column -->
            <aside class="flex flex-col gap-4">
                <div class="p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Browse by</span>
                    </div>
                    <div class="accordion">

                        <div class="acc-item">
                            <div class="acc-title">GBA Solutions</div>
                            <div class="acc-content">
                                <div class="flex flex-col space-y-3">
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>GBA Solutions</a>
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>GFood Safety Solutions</a>
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>Clinical Research</a>
                                </div>
                            </div>
                        </div>
                        <div class="acc-item">
                            <div class="acc-title">GBA Solutions</div>
                            <div class="acc-content">
                                <div class="flex flex-col space-y-3">
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>GBA Solutions</a>
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>GFood Safety Solutions</a>
                                    <a href="#" class="flex items-center gap-2"><i
                                            class="fa-solid fa-angle-right"></i>Clinical Research</a>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
            </aside>

        </div>
    </div>

@endsection