@extends('layout.app')
@section('content')
  <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image:  url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2>About Integrated Gulf Biosystems</h2>
                    <p class="text-white/90 my-3 max-w-2xl">For over two decades, Integrated Gulf Biosystems has
                        empowered laboratories, research institutions, and biotechnology companies with advanced
                        genomic, molecular, and analytical technologies across the MENA region.</p>
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
                    <span class="bg-white  uppercase">KingFisher Duo Prime</span>
                </div>
                <p class="text-gray-700 mb-4">The Thermo Scientific KingFisher Duo Prime is the most versatile compact
                    benchtop sample
                    preparation instrument in the lab for 6 or 12 samples per run. It offers consistent extraction
                    and purification of DNA, RNA, proteins, and cells, and includes an ultraviolet lamp for
                    decontamination. The KingFisher Duo Prime is an affordable choice for academic or small
                    labs.</p>
                <div class="mb-5">
                    <h4 class="mb-3">Features</h4>
                    <p class="text-gray-700 mb-3">In addition to the common features of all KingFisher Purification
                        Systems, the Duo Prime
                        offers:</p>
                    <div class="features-info space-y-4">
                        <div class="features-item">
                            <h4>Automated purification</h4>
                            <p>6–12 samples per run</p>
                        </div>
                        <div class="features-item">
                            <h4>Choose from two plate formats</h4>
                            <p>Wide 20 μL–5 mL volume range</p>
                        </div>
                        <div class="features-item">
                            <h4>Download protocols from our library</h4>
                            <p>Or easily customize protocols using ThermoScientific BindIt Software</p>
                        </div>
                        <div class="features-item">
                            <h4>Easy to install and run</h4>
                            <p>Ready to run in 10 minutes or less</p>
                        </div>
                        <div class="features-item">
                            <h4>Simple workflow</h4>
                            <p>Just follow the on-screen instructions</p>
                        </div>
                        <div class="features-item">
                            <h4>Ultraviolet lamp</h4>
                            <p>Decontamination is standard</p>
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <h4 class="mb-3">Applications</h4>
                    <p class="text-gray-700">The KingFisher Duo Prime can gently and efficiently isolate and purify
                        nucleic acids, proteins,
                        and cells from a range of sample types for a wide variety of downstream applications. For a
                        selection of application kits, reagents, and protocols that run on the KingFisher Duo Prime,
                        reach out to us.</p>
                </div>
                <div class="mb-5">
                    <h4 class="mb-3">Protocols and software</h4>
                    <p class="text-gray-700 mb-3">Thermo Scientific BindIt software is included with KingFisher Duo
                        Prime and Flex
                        instruments. The software makes it easy to create, download, run, modify, and store
                        protocols for your KingFisher applications.</p>
                    <p class="text-gray-700 mb-3">Protocols are available for nucleic acid and protein purification
                        procedures with reagent kits.
                        The software allows you to define and edit steps, parameters, plates, and reagents for every
                        part of your protocol—bind, wash, and elute.</p>
                </div>
                <div class="my-10">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Form Fill Up</span>
                    </div>
                    <form onsubmit="event.preventDefault(); alert('Form submitted (demo)')" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Designation<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="name" type="text" placeholder="Designation"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="contact" type="text" placeholder="Phone"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pincode<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="email" type="email" placeholder="Pincode"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="org" type="text" placeholder="Address"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="city" type="text" placeholder="City"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">State or Province<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="city" type="text" placeholder="State or Province"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <input id="city" type="text" placeholder="City"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country / Region<span
                                        class="text-red-500 text-xl ms-1">*</span></label>
                                <textarea id="remarks" placeholder="Any additional information"
                                    class="form-input"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn uppercase flex items-center gap-2">Submit <i class="fa-regular fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>

                <div class="my-10">
                    <h4 class="mb-3 text-center">Our Resources</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="resource-box">
                            <div class="mb-2"><i data-lucide="download"></i></div>
                            <p class="title">Catalog (English)</p>
                            <p class="link">Data Connectivity Catalog</p>
                        </div>
                        <div class="resource-box">
                            <div class="mb-2"><i data-lucide="download"></i></div>
                            <p class="title">Catalog (English)</p>
                            <p class="link">Data Connectivity Catalog</p>
                        </div>
                        <div class="resource-box">
                            <div class="mb-2"><i data-lucide="download"></i></div>
                            <p class="title">Catalog (English)</p>
                            <p class="link">Data Connectivity Catalog</p>
                        </div>
                        <div class="resource-box">
                            <div class="mb-2"><i data-lucide="download"></i></div>
                            <p class="title">Catalog (English)</p>
                            <p class="link">Data Connectivity Catalog</p>
                        </div>
                    </div>


                </div>

            </div>


            <!-- Side column -->
            <aside class="flex flex-col gap-4">
                <div
                    class="bg-gray-200 text-back text-lg flex items-center justify-center rounded border-2 border-gray-200 min-h-[240px]">
                    <div class="productSwiper swiper w-full">
                        <div class="slider-overlay"></div>
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ asset('front/images/one.jpg') }}" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('front/images/two.jpg') }}" alt="">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('front/images/three.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <div class="p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Browse by</span>
                    </div>
                    <div class="accordion">

                        <div class="acc-item">
                          <div class="acc-title">GBA Solutions</div>
                          <div class="acc-content">
                            <div class="flex flex-col space-y-3">
                                <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>GBA Solutions</a>
                                <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>GFood Safety Solutions</a>
                                <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>Clinical Research</a>
                            </div>
                          </div>
                        </div>
                        <div class="acc-item">
                            <div class="acc-title">GBA Solutions</div>
                            <div class="acc-content">
                              <div class="flex flex-col space-y-3">
                                  <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>GBA Solutions</a>
                                  <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>GFood Safety Solutions</a>
                                  <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>Clinical Research</a>
                              </div>
                            </div>
                          </div>
                      
                       
                      
                      </div>
                    
                </div>
            </aside>

        </div>
    </div>

@endsection