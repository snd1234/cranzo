@extends('layout.app')
@section('content')

 <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2>Latest News & Updates</h2>
                    <p class="text-white/90 my-3 max-w-2xl">Stay informed with company announcements, achievements, and
                        industry insights.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ===== NEWS SECTION ===== -->
    <section class="p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase">Latest News & Updates</span>
        </div>
        <div class="grid md:grid-cols-3 gap-10">
            <?php foreach($latestnews as $news){ ?>
            <div class="event-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                <img src="{{asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="rounded-lg mb-4 w-full h-48 object-cover">
                <h4 class="mb-2">{{ strlen($news->title) > 66 ? substr($news->title, 0, 66) . '...' : $news->title }}</h4>
                <p class="text-gray-700 mb-4">
                     {{ \Illuminate\Support\Str::words($news->short_description, 30, '...') }}
                </p>
                <p class="text-md text-gray-700">{{ \Carbon\Carbon::parse($news->created_at)->format('jS F Y') }}</p>
            </div>
            <?php } ?>
          
        </div>

    </section>

    <!-- <div class="p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase">More Articles</span>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h4 class="font-bold text-lg">Expansion of Service Centers Across Asia</h4>
                <p class="text-gray-700 mt-2">
                    New regional centers are being established to provide faster technical support and service.
                </p>
            </div>

            <div class="bg-white  rounded-xl shadow p-6">
                <h4 class="font-bold text-lg">Launch of Advanced Training Program</h4>
                <p class="text-gray-700 mt-2">
                    A new online training platform for laboratory professionals is now available.
                </p>
            </div>

            <div class="bg-white  rounded-xl shadow p-6">
                <h4 class="font-bold text-lg">New Patent for Molecular Diagnostics</h4>
                <p class="text-gray-700 mt-2">
                    Our R&D division secures a groundbreaking patent that enhances diagnostic accuracy.
                </p>
            </div>
        </div>
    </div> -->



@endsection