@extends('layout.app')
@section('content')
<!--Banner section-->
    <div class="relative w-full">
        <!-- Banner Image -->
        <img src="{{ asset('front/images/banner1.jpg') }}" class="w-full h-auto object-cover">

        <!-- Overlay Content -->
        <div class="absolute inset-0 flex flex-col justify-center items-start p-8 gap-4 text-white">
            <div class="banner-content space-y-3">
                <h1>Latest Upcoming Feature</h1>
                <p>
                    This features the latest events and featured products which have to be updated regularly.
                </p>
                <button class="more-btn px-6 py-2 mt-5 uppercase rounded-md transition duration-300 ease-in-out">
                    Read More
                </button>
            </div>
        </div>
    </div>

    <!-- Upcoming Events + News -->

    <div class="p-12 grid md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">upcoming Events</span>
                </div>
                <div class="space-y-6">
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <img src="" class="w-full object-cover">
                        </div>

                        <div class="date mb-2">30th September</div>
                        <h3 class="mb-2 text-gray-700">Clinical Genomics Day – Riyadh</h3>
                        <p class="text-gray-600">See the break throughs shaping the future of clinical and research in
                            person. Integrated Gulf
                            Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and
                            innovators.</p>
                        <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                            <i data-lucide="move-right"></i></a>
                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <img src="" class="w-full object-cover">
                        </div>

                        <div class="date mb-2">2nd Oct September</div>
                        <h3 class="mb-2 text-gray-700">Clinical Genomics Day – Dubai</h3>
                        <p class="text-gray-600">See the break throughs shaping the future of clinical and research in
                            person. Integrated Gulf
                            Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and
                            innovators.</p>
                        <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                            <i data-lucide="move-right"></i></a>
                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <img src="" class="w-full object-cover">
                        </div>

                        <div class="date mb-2">10- 13 February</div>
                        <h3 class="mb-2 text-gray-700">Medlab 2026</h3>
                        <p class="text-gray-600">Medlab Middle East 2026 exhibition, which will be held from February 9-12,
                            2026 at the
                            Dubai World Trade Centre in Dubai, UAE. It is a leading event for the laboratory and
                            healthcare industry in the Middle East.</p>
                        <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                            <i data-lucide="move-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">Latest Webinars</span>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <img src="" class="w-full object-cover">
                    </div>

                    <div class="date mb-2">19th Sep</div>
                    <h3 class="mb-2 text-gray-700">miRNA workflow by Thermo Fisher Scientific</h3>
                    <p class="text-gray-600">Comprehensive Approaches to miRNA Analysis: From Sample Extraction to
                        qPCR/dPCR
                        MicroRNA analysis is essential for understanding gene regulation and expression. This
                        webinar reviews methodologies for miRNA sample extraction and introduces real-time PCR
                        and TaqMan assays for miRNA quantification. It also addresses digital PCR for absolute
                        miRNA quantification and outlines workflows for pri-miRNA and mature miRNA using
                        various chemistries. The webinar aims to equip researchers with the knowledge to conduct
                        accurate and reliable miRNA studies.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                        <i data-lucide="move-right"></i></a>
                </div>
            </div>
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">Latest blogs</span>
                </div>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <img src="" class="w-full object-cover">
                    </div>

                    <div class="date mb-2">DEC 15-17</div>
                    <h3 class="mb-2 text-gray-700">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                        <i data-lucide="move-right"></i></a>
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
                                class="w-full sm:w-[200px] h-[180px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ asset('front/images/image1.jpg') }}" alt="Event Image"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="mb-2">4th Sep</div>

                                <!-- Title -->
                                <h3 class="mb-2 text-gray-700">Innovation Day – Unlock Tomorrow’s Science | Jeddah</h3>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">
                                    Our Innovation Day, held on 4th September at Hotel Warwick, Jeddah in collaboration
                                    with
                                    Thermo Fisher Scientific, concluded successfully.
                                </p>
                                <p class="text-gray-600 mb-4">
                                    The event brought together scientific leaders, industry professionals, and key
                                    stakeholders,
                                    featuring insightful sessions from Thermo Fisher Scientific speakers and discussions
                                    on
                                    advancing innovation in life sciences.
                                </p>

                                <!-- Learn More Link -->
                                <a href="#" class="flex items-center gap-2 inline-block uppercase hover:underline">
                                    Learn More
                                    <i data-lucide="move-right" class="w-4 h-4"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[200px] h-[180px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ asset('front/images/image3.jpg') }}" alt="Event Image"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="mb-2">4th September</div>

                                <!-- Title -->
                                <h3 class="mb-2 text-gray-700">Unlock Tomorrow’s Science at Innovation Day</h3>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">
                                    Join us on 4th September at Hotel Warwick, Jeddah for Innovation Day – Unlock
                                    Tomorrow’s
                                    Science, in collaboration with Thermo Fisher Scientific.
                                </p>

                                <!-- Learn More Link -->
                                <a href="#" class="flex items-center gap-2 inline-block uppercase hover:underline">
                                    Learn More
                                    <i data-lucide="move-right" class="w-4 h-4"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[200px] h-[180px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ asset('front/images/image4.jpg') }}" alt="Event Image"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="mb-2">18th – 19th May 2025</div>

                                <!-- Title -->
                                <h3 class="mb-2 text-gray-700">MENA ART 2025, hosted by Sidra Medicine and Qatar
                                    Foundation</h3>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">
                                    A premier platform bringing together global leaders in flow cytometry and optical
                                    microscopy. Engaging with top researchers and clinicians reaffirmed our commitment
                                    to
                                    advancing precision medicine through cutting-edge technologies and trusted
                                    partnerships.
                                </p>

                                <!-- Learn More Link -->
                                <a href="#" class="flex items-center gap-2 inline-block uppercase hover:underline">
                                    Learn More
                                    <i data-lucide="move-right" class="w-4 h-4"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[200px] h-[180px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ asset('front/images/image5.jpg') }}" alt="Event Image"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="mb-2">18th – 19th May 2025</div>

                                <!-- Title -->
                                <h3 class="mb-2 text-gray-700">First AVITI24 has landed in the Middle East!</h3>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">
                                    We’re proud to announce Sidra Medicine, Qatar is the first adopter of the AVITI24 by
                                    Element Biosciences — marking a major &#39;&#39;SHIFT IN SCIENCE&#39;&#39; for the genomics community in
                                    the region.
                                </p>

                                <!-- Learn More Link -->
                                <a href="#" class="flex items-center gap-2 inline-block uppercase hover:underline">
                                    Learn More
                                    <i data-lucide="move-right" class="w-4 h-4"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            <!-- Image -->
                            <div
                                class="w-full sm:w-[200px] h-[180px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ asset('front/images/image6.jpg') }}" alt="Event Image"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="mb-2">13th – 16th May</div>

                                <!-- Title -->
                                <h3 class="mb-2 text-gray-700">IGB Roadshow in Collab with Element Biosciences</h3>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">
                                    We&#39;re excited to share some highlights from the AVITI 24 Roadshow, which successfully
                                    wrapped up last week with impactful sessions across leading universities in the UAE and
                                    KSA.
                                </p>

                                <!-- Learn More Link -->
                                <a href="#" class="flex items-center gap-2 inline-block uppercase hover:underline">
                                    Learn More
                                    <i data-lucide="move-right" class="w-4 h-4"></i>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <!-- <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">Latest Offerings</span>
                </div>
               
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                    
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x250" alt="Offering 1"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Premium Service</h3>
                            <p class="text-gray-600 mb-4">Get access to our premium features with unmatched quality and
                                support.</p>
                            <a href="#"
                                class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">Learn
                                More</a>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x250" alt="Offering 2"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">New Product</h3>
                            <p class="text-gray-600 mb-4">Experience innovation with our latest product launch designed
                                for you.</p>
                            <a href="#"
                                class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">Shop
                                Now</a>
                        </div>
                    </div>

                   
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x250" alt="Offering 3"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Exclusive Deal</h3>
                            <p class="text-gray-600 mb-4">Don’t miss out on our limited-time offers available only this
                                season.</p>
                            <a href="#"
                                class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">Grab
                                Offer</a>
                        </div>
                    </div>

                </div>


            </div> -->
            <!-- <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">Follow Us on Social Media</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    
                    <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="facebook" class="w-6 h-6 text-blue-600"></i>
                            <span class="text-gray-700">Facebook</span>
                        </div>
                        <p class="text-gray-600 mb-3">Catch our latest updates and events on Facebook.</p>
                        <a href="https://facebook.com" target="_blank"
                            class="text-blue-600 hover:underline text-sm font-medium">Visit Page →</a>
                    </div>

                   
                    <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="instagram" class="w-6 h-6 text-pink-500"></i>
                            <span class="text-gray-700">Instagram</span>
                        </div>
                        <p class="text-gray-600 mb-3">Follow our stories and daily posts on Instagram.</p>
                        <a href="https://instagram.com" target="_blank"
                            class="text-pink-500 hover:underline text-sm font-medium">Follow Us →</a>
                    </div>

                    
                    <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="twitter" class="w-6 h-6 text-sky-500"></i>
                            <span class="text-gray-700">Twitter</span>
                        </div>
                        <p class="text-gray-600 mb-3">Stay updated with real-time announcements.</p>
                        <a href="https://twitter.com" target="_blank"
                            class="text-sky-500 hover:underline text-sm font-medium">Follow Us →</a>
                    </div>

                </div>

            </div> -->
        </div>
    </div>

    <!-- Latest Webinars + Blogs -->

    <div class="p-12 bg-white">
        <div class="page-heading relative mb-4">
            <span class="bg-white uppercase">Latest Equipment</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <img src="" class="w-full object-cover">
                </div>
                <div class="date mb-2">DEC 15-17</div>
                <h3 class="mb-2 text-gray-700">Equipment</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                    <i data-lucide="move-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <img src="" class="w-full object-cover">
                </div>
                <div class="date mb-2">DEC 15-17</div>
                <h3 class="mb-2 text-gray-700">Consumables</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                    <i data-lucide="move-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <img src="" class="w-full object-cover">
                </div>
                <div class="date mb-2">DEC 15-17</div>
                <h3 class="mb-2 text-gray-700">Services</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="#" class="flex items-center gap-4 mt-4 inline-block uppercase hover:underline">Learn More
                    <i data-lucide="move-right"></i></a>
            </div>

        </div>

    </div>

    <div class="p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase">Subscribe to our Newsletter</span>
        </div>
        <p class="text-gray-800 mb-8">
            Stay updated with the latest news, events, and exclusive offers delivered right to your inbox.
        </p>
      
        <div class="p-8 bg-white shadow rounded-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="form-group">
                <label class="text-md uppercase">Fisrt Name</label>
                <input type="email" placeholder="Fisrt Name" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Last Name</label>
                <input type="email" placeholder="Last Name" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Email Id</label>
                <input type="email" placeholder="Email Id" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Job Title</label>
                <input type="email" placeholder="Job Title" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Org/Inst</label>
                <input type="email" placeholder="Org/Inst" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Country</label>
                <input type="email" placeholder="Country" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Department</label>
                <input type="email" placeholder="Department" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>
            <div class="form-group">
                <label class="text-md uppercase">Application</label>
                <input type="email" placeholder="Application" class="w-full px-3 py-1.5 rounded-lg text-gray-800 border border-gray-500 hover:border-green-500" required="">
            </div>

            
            <div class="form-group text-end col-span-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-yellow-400 text-indigo-900 font-semibold rounded-lg shadow hover:bg-yellow-300 transition duration-300">
                    Subscribe
                </button>
            </div>

        </div>
        </div>


    </div>
@endsection