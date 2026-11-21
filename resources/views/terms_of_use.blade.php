@extends('layout.app')

@section('content')
@php
    $icons = ['fa-bag-shopping', 'fa-user-lock', 'fa-arrow-up-right-from-square', 'fa-scale-balanced'];
    $cleaned = \App\Support\HtmlCleaner::clean((string) $page->content);
    $points = [];
    foreach (preg_split('/<\/p>/i', $cleaned) ?: [] as $part) {
        $text = trim(html_entity_decode(strip_tags($part), ENT_QUOTES, 'UTF-8'));
        $text = trim(preg_replace('/\s*\[[^\]]*\]/u', '', $text) ?? '');
        if ($text === '' || str_contains($text, 'Owner confirmation')) {
            continue;
        }
        $points[] = $text;
    }
@endphp
<section class="catalogue-hero">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <nav class="catalogue-crumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span>{{ $page->title }}</span>
        </nav>
        <p class="catalogue-kicker">Website use</p>
        <h1>{{ $page->title }}</h1>
        @if($page->excerpt)
            <p class="catalogue-lead">{{ $page->excerpt }}</p>
        @endif
    </div>
</section>
<section class="terms-page">
    <div class="max-w-6xl mx-auto px-4 py-8 grid lg:grid-cols-[minmax(0,1fr)_280px] gap-6 items-start">
        <div class="grid gap-4">
            @foreach($points as $point)
                <article class="terms-point">
                    <span class="contact-icon"><i class="fa-solid {{ $icons[$loop->index % count($icons)] }}" aria-hidden="true"></i></span>
                    <p>{{ $point }}</p>
                </article>
            @endforeach
        </div>
        <aside class="grid gap-4">
            <a class="contact-aside" href="{{ url('/terms-conditions') }}">
                <span class="contact-icon"><i class="fa-solid fa-file-lines" aria-hidden="true"></i></span>
                <span>
                    <strong>Terms &amp; Conditions</strong>
                    <span>Ordering and catalogue conditions.</span>
                </span>
            </a>
            <a class="contact-aside" href="{{ url('/privacy-policy') }}">
                <span class="contact-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
                <span>
                    <strong>Privacy Policy</strong>
                    <span>How customer and order information is handled.</span>
                </span>
            </a>
            <a class="contact-aside" href="{{ url('/contact-us') }}">
                <span class="contact-icon"><i class="fa-regular fa-envelope" aria-hidden="true"></i></span>
                <span>
                    <strong>Contact Us</strong>
                    <span>Ask about a product, an order, or a fitment.</span>
                </span>
            </a>
        </aside>
    </div>
</section>
@endsection
