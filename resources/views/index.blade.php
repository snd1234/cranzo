@extends('layout.app')
@section('content')
<!-- Slider main container -->
    <div class="homeSwiper swiper w-full">
        <div class="slider-overlay"></div>
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <img src="{{ asset('front/images/banner.jpg') }}" alt="">
                <div class="slide-content">
                    <h2>Beautiful Nature</h2>
                    <p>Experience the calm and beauty of green mountains, fresh air, and peaceful surroundings.</p>
                    <button class="btn-hover color-1">Read More</button>
                </div>
            </div>


            <div class="swiper-slide">
                <img src="{{ asset('front/images/banner.jpg') }}" alt="">
                <div class="slide-content">
                    <h2>City Lights</h2>
                    <p>Discover the vibrant nightlife and sparkling skyline of modern metropolitan cities.</p>
                    <button class="btn-hover color-1">Read More</button>
                </div>
            </div>

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Upcoming Events + News -->

    <div class="p-12 grid md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">upcoming Events</span>
                </div>
                <div class="space-y-8">
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-[180px] w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <figure>
                                <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                            </figure>
                        </div>

                        <div class="date text-gray-600 flex items-center gap-2 mb-4">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>30th September</span>
                        </div>
                        <h3 class="mb-2">Clinical Genomics Day – Riyadh</h3>
                        <p class="text-gray-700">See the break throughs shaping the future of clinical and research in
                            person. Integrated Gulf
                            Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and
                            innovators.</p>
                        <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                            More
                            <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-[180px] w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <figure>
                                <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                            </figure>

                        </div>

                        <div class="date text-gray-600 flex items-center gap-2 mb-4">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>30th September</span>
                        </div>
                        <h3 class="mb-2">Clinical Genomics Day – Dubai</h3>
                        <p class="text-gray-700">See the break throughs shaping the future of clinical and research in
                            person. Integrated Gulf
                            Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and
                            innovators.</p>
                        <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                            More
                            <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-[180px] w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <figure>
                                <img src="{{ asset('front/images/three.jpg') }}" class="w-full object-cover">
                            </figure>
                        </div>

                        <div class="date text-gray-600 flex items-center gap-2 mb-4">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>30th September</span>
                        </div>
                        <h3 class="mb-2">Medlab 2026</h3>
                        <p class="text-gray-700">Medlab Middle East 2026 exhibition, which will be held from February
                            9-12,
                            2026 at the
                            Dubai World Trade Centre in Dubai, UAE. It is a leading event for the laboratory and
                            healthcare industry in the Middle East.</p>
                        <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4  inline-block  ">Learn
                            More
                            <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>


        </div>
        <div class="md:col-span-2">
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">latest news</span>
                </div>
                <div class="space-y-6">
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{ asset('front/images/image1.jpg') }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>

                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>30th September</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">Innovation Day – Unlock Tomorrow’s
                                    Science | Jeddah</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    Our Innovation Day, held on 4th September at Hotel Warwick, Jeddah in collaboration
                                    with
                                    Thermo Fisher Scientific, concluded successfully.
                                </p>
                                <p class="text-gray-700 mb-4">
                                    The event brought together scientific leaders, industry professionals, and key
                                    stakeholders,
                                    featuring insightful sessions from Thermo Fisher Scientific speakers and discussions
                                    on
                                    advancing innovation in life sciences.
                                </p>

                                <!-- Learn More Link -->
                                <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{ asset('front/images/image3.jpg') }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>

                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>30th September</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">Unlock Tomorrow’s Science at
                                    Innovation Day</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    Join us on 4th September at Hotel Warwick, Jeddah for Innovation Day – Unlock
                                    Tomorrow’s
                                    Science, in collaboration with Thermo Fisher Scientific.
                                </p>

                                <!-- Learn More Link -->
                                <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{ asset('front/images/image4.jpg') }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>

                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>30th September</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">MENA ART 2025, hosted by Sidra
                                    Medicine and Qatar
                                    Foundation</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    A premier platform bringing together global leaders in flow cytometry and optical
                                    microscopy. Engaging with top researchers and clinicians reaffirmed our commitment
                                    to
                                    advancing precision medicine through cutting-edge technologies and trusted
                                    partnerships.
                                </p>

                                <!-- Learn More Link -->
                                <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{ asset('front/images/image5.jpg') }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>30th September</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">First AVITI24 has landed in the
                                    Middle East!</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    We’re proud to announce Sidra Medicine, Qatar is the first adopter of the AVITI24 by
                                    Element Biosciences — marking a major &#39;&#39;SHIFT IN SCIENCE&#39;&#39; for the
                                    genomics community in
                                    the region.
                                </p>

                                <!-- Learn More Link -->
                                <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{ asset('front/images/image6.jpg') }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>

                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>30th September</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">IGB Roadshow in Collab with Element
                                    Biosciences</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    We&#39;re excited to share some highlights from the AVITI 24 Roadshow, which
                                    successfully
                                    wrapped up last week with impactful sessions across leading universities in the UAE
                                    and
                                    KSA.
                                </p>

                                <!-- Learn More Link -->
                                <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="p-12">
        <div class="mb-6">
            <div class="page-heading relative mb-4">
                <span class="uppercase">Latest Webinars</span>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                        </figure>

                    </div>
                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">miRNA workflow by Thermo Fisher Scientific</h3>
                    <p class="text-gray-600">Comprehensive Approaches to miRNA Analysis: From Sample Extraction to
                        qPCR/dPCR
                        MicroRNA analysis is essential for understanding gene regulation and expression. This
                        webinar reviews methodologies for miRNA sample extraction and introduces real-time PCR
                        and TaqMan assays for miRNA quantification. It also addresses digital PCR for absolute
                        miRNA quantification and outlines workflows for pri-miRNA and mature miRNA using
                        various chemistries. The webinar aims to equip researchers with the knowledge to conduct
                        accurate and reliable miRNA studies.</p>
                    <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>
                    </div>
                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">miRNA workflow by Thermo Fisher Scientific</h3>
                    <p class="text-gray-600">Comprehensive Approaches to miRNA Analysis: From Sample Extraction to
                        qPCR/dPCR
                        MicroRNA analysis is essential for understanding gene regulation and expression. This
                        webinar reviews methodologies for miRNA sample extraction and introduces real-time PCR
                        and TaqMan assays for miRNA quantification. It also addresses digital PCR for absolute
                        miRNA quantification and outlines workflows for pri-miRNA and mature miRNA using
                        various chemistries. The webinar aims to equip researchers with the knowledge to conduct
                        accurate and reliable miRNA studies.</p>
                    <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/three.jpg') }}" class="w-full object-cover">
                        </figure>

                    </div>
                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">miRNA workflow by Thermo Fisher Scientific</h3>
                    <p class="text-gray-600">Comprehensive Approaches to miRNA Analysis: From Sample Extraction to
                        qPCR/dPCR
                        MicroRNA analysis is essential for understanding gene regulation and expression. This
                        webinar reviews methodologies for miRNA sample extraction and introduces real-time PCR
                        and TaqMan assays for miRNA quantification. It also addresses digital PCR for absolute
                        miRNA quantification and outlines workflows for pri-miRNA and mature miRNA using
                        various chemistries. The webinar aims to equip researchers with the knowledge to conduct
                        accurate and reliable miRNA studies.</p>
                    <a href="{{ url('/common-detail-page') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <div class="page-heading relative mb-4">
                <span class="uppercase">Latest blogs</span>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>

                    </div>

                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>
                    </div>

                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>
                    </div>

                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- Latest Webinars + Blogs -->

    <div class="p-12 bg-white">
        <div class="page-heading relative mb-4">
            <span class="bg-white uppercase">Our solutions</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <figure>
                        <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                    </figure>
                </div>

                <h3 class="mb-2">Equipment</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <figure>
                        <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                    </figure>
                </div>

                <h3 class="mb-2">Consumables</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                </div>

                <h3 class="mb-2">Services</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }} " class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>

        </div>

    </div>

    <div class="Newsletter p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase">Subscribe to our Newsletter</span>
        </div>
        <p class="text-gray-800 mb-8">
            Stay updated with the latest news, events, and exclusive offers delivered right to your inbox.
        </p>

        <div class="p-8 bg-white shadow rounded-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">Fisrt Name</label>
                    <input type="email" placeholder="Fisrt Name"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="email" placeholder="Last Name"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Id</label>
                    <input type="email" placeholder="Email Id"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Job Title</label>
                    <input type="email" placeholder="Job Title"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Org/Inst</label>
                    <input type="email" placeholder="Org/Inst"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="email" placeholder="Country"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="email" placeholder="Department"
                        class="form-input"
                        required="">
                </div>
                <div class="form-group">
                    <label class="form-label">Application</label>
                    <input type="email" placeholder="Application"
                        class="form-input"
                        required="">
                </div>


                <div class="form-group text-end col-span-4">
                    <button type="submit" class="btn-hover color-1">
                        Subscribe
                    </button>
                </div>

            </div>
        </div>


    </div>
@endsection