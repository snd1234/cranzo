@extends('layout.app')
@section('content')

 <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2>Scientific Events & Workshops</h2>
                    <p class="text-white/90 my-3 max-w-2xl">Stay updated with our latest conferences, exhibitions,
                        product launches, and certified
                        training workshops across the Middle East. We bring together global experts, researchers,
                        and technology leaders to exchange knowledge and showcase innovation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section class="event-card py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-center mb-8">Upcoming Events</h3>


            <div class="grid md:grid-cols-3 gap-10">
                <!-- Event Card 1 -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6" class="rounded-lg mb-4" />
                    <h4 class="mb-2">Genomics Innovation Summit 2025</h4>
                    <p class="date mb-3">📅 March 18, 2025 </p>
                    <p class="text-gray-700 mb-4">
                        A regional gathering of genomic scientists and healthcare pioneers
                        discussing advancements in NGS, PCR, and diagnostics workflows.
                    </p>
                    <a href="#" class="hover:underline font-medium">Learn More →</a>
                </div>


                <!-- Event Card 2 -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551836022-4c4c79ecde51" class="rounded-lg mb-4" />
                    <h4 class="mb-2">Clinical Diagnostics Expo</h4>
                    <p class="date mb-3">📅 April 12, 2025 </p>
                    <p class="text-gray-700  mb-4">
                        Showcasing cutting-edge diagnostic tools, automation platforms, and
                        laboratory equipment from leading global manufacturers.
                    </p>
                    <a href="#" class="hover:underline font-medium">Learn More →</a>
                </div>


                <!-- Event Card 3 -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c" class="rounded-lg mb-4" />
                    <h4 class="mb-2">PCR Masterclass Workshop</h4>
                    <p class="date mb-3">📅 May 3, 2025 </p>
                    <p class="text-gray-700 mb-4">
                        Hands-on technical training covering qPCR optimization, troubleshooting,
                        and workflow best practices.
                    </p>
                    <a href="#" class="hover:underline font-medium">Learn More →</a>
                </div>
            </div>
        </div>
    </section>


    <!-- Past Events Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-center mb-8">Past Events Highlights</h3>


            <div class="grid md:grid-cols-2 gap-10">
                <!-- Past Event 1 -->
                <div
                    class="event-card bg-gray-50 p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" class="rounded-lg mb-4" />
                    <h4 class="text">NGS Workflow Bootcamp 2024</h4>
                    <p class="text-gray-700">
                        A hands-on sequencing workshop attended by over 80 researchers. Covered
                        sequencing protocols, library preparation, and data interpretation.
                    </p>
                </div>


                <!-- Past Event 2 -->
                <div
                    class="event-card bg-gray-50 p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf" class="rounded-lg mb-4" />
                    <h4 class="text">Middle East BioTech Conference 2024</h4>
                    <p class="text-gray-700">
                        An international gathering of scientists, industry experts, and innovators
                        discussing the future of biotechnology in healthcare and research.
                    </p>
                </div>
            </div>
        </div>
    </section>

    
@endsection