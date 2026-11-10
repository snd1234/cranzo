@extends('layout.app')

@section('content')
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-white">Newsroom</h2>
                <p class="text-white/90 my-3 max-w-2xl">Stay updated with the latest announcements and stories from our company.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 space-y-6">
        @forelse($latestnews as $news)
            <div class="bg-gray-50 rounded-xl shadow p-6 md:flex md:gap-6">
                @if(!empty($news->image))
                    <img src="{{ asset(config('uploads.path') . $news->image) }}" alt="{{ $news->title }}" class="w-full md:w-48 h-40 object-cover rounded-lg">
                @endif
                <div>
                    <div class="text-sm text-gray-500 mb-2">{{ \Carbon\Carbon::parse($news->created_at)->format('jS F Y') }}</div>
                    <h3 class="text-lg font-semibold mb-2">{{ $news->title }}</h3>
                    <p class="text-gray-600">{{ \Illuminate\Support\Str::words($news->short_description ?? $news->content ?? '', 40, '...') }}</p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-600">No news posts available right now.</div>
        @endforelse
    </div>
</section>
@endsection
