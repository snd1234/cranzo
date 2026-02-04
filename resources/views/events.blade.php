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
            <h3 class="text-3xl font-bold text-center mb-8">Events</h3>


            <div class="grid md:grid-cols-3 gap-10">
                <!-- Event Card 1 -->
                  <?php foreach($events as $event){ ?>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="{{asset(config('uploads.path')  . $event->image)}}" class="rounded-lg mb-4" />
                    <h4 class="mb-2">{{ strlen($event->title) > 66 ? substr($event->title, 0, 66) . '...' : $event->title }}</h4>
                    <p class="date mb-3">📅 {{ \Carbon\Carbon::parse($event->created_at)->format('jS F Y') }} </p>
                    <p class="text-gray-700 mb-4">
                        {{ \Illuminate\Support\Str::words($event->short_description, 30, '...') }}
                    </p>
                    <a href="{{ url('/event/' . $event->slug) }}" class="hover:underline font-medium">Learn More →</a>
                </div>
                <?php } ?>

            </div>
        </div>
    </section>


    <!-- Past Events Section -->
    <!-- <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-center mb-8">Past Events Highlights</h3>


            <div class="grid md:grid-cols-2 gap-10">
                <div
                    class="event-card bg-gray-50 p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" class="rounded-lg mb-4" />
                    <h4 class="text">NGS Workflow Bootcamp 2024</h4>
                    <p class="text-gray-700">
                        A hands-on sequencing workshop attended by over 80 researchers. Covered
                        sequencing protocols, library preparation, and data interpretation.
                    </p>
                </div>

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
    </section> -->

    
@endsection