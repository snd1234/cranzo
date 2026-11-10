@extends('layout.app')

@section('content')
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-white">Events</h2>
                <p class="text-white/90 my-3 max-w-2xl">Browse upcoming and recent events hosted by our team.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="bg-gray-50 rounded-xl shadow overflow-hidden">
                @if(!empty($event->image))
                    <img src="{{ asset(config('uploads.path') . $event->image) }}" alt="{{ $event->title }}" class="h-48 w-full object-cover">
                @endif
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2">{{ \Carbon\Carbon::parse($event->created_at)->format('jS F Y') }}</div>
                    <h3 class="text-lg font-semibold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600">{{ 
Illuminate\Support\Str::words($event->short_description ?? $event->content ?? '', 26, '...') }}</p>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 text-center text-gray-600">No events available right now.</div>
        @endforelse
    </div>
</section>
@endsection
