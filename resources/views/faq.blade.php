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
    <div class="mt-6 space-y-3">
        @forelse($faqs as $faq)
            <details class="bg-white border border-stone-200 rounded-xl">
                <summary class="px-4 py-3 font-medium cursor-pointer">{{ $faq->question }}</summary>
                <div class="px-4 pb-4 text-stone-700 space-y-2 [&_a]:text-blue-700">
                    {!! \App\Support\HtmlCleaner::clean($faq->answer) !!}
                </div>
            </details>
        @empty
            <p class="bg-white border border-stone-200 rounded-xl p-5">No questions are published yet.</p>
        @endforelse
    </div>
</section>
@endsection
