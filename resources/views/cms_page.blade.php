@extends('layout.app')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-stone-500 mb-4" aria-label="Breadcrumb">
        <a href="{{ url('/') }}" class="hover:text-stone-800">Home</a>
        <span class="mx-1">/</span>
        <span>{{ $page->title }}</span>
    </nav>
    <h1 class="text-3xl font-semibold">{{ $page->title }}</h1>
    @if($page->excerpt)
        <p class="mt-2 text-stone-600">{{ $page->excerpt }}</p>
    @endif
    @if($page->imageUrl())
        <img src="{{ $page->imageUrl() }}" alt="" class="mt-6 w-full rounded-xl object-cover max-h-80">
    @endif
    <div class="cms-prose shop-card mt-6 p-5 sm:p-8">
        {!! \App\Support\HtmlCleaner::clean($page->content) !!}
    </div>
</section>
@endsection
