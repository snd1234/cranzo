@extends('layout.app')

@section('content')
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-white">{{ $blog->title }}</h2>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @if(!empty($blog->image))
                <img src="{{ asset(config('uploads.path') . $blog->image) }}" alt="{{ $blog->title }}" class="w-full rounded-xl object-cover h-80 mb-6">
            @endif
            <div class="prose max-w-none">{!! $blog->content !!}</div>
        </div>
        <div>
            <h3 class="font-semibold text-xl mb-4">More updates</h3>
            <div class="space-y-4">
                @foreach($relatedBlogs as $related)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium">{{ $related->title }}</h4>
                        <a href="{{ url('/blog/' . $related->slug) }}" class="text-indigo-600 text-sm">Read more</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
