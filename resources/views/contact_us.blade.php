@extends('layout.app')

@section('content')
@php
    $supportEmail = config('mail.from.address');
    $showEmail = is_string($supportEmail) && filter_var($supportEmail, FILTER_VALIDATE_EMAIL) && ! str_contains($supportEmail, 'example.');
    $rawContent = (string) ($page->content ?? '');
    $showCms = $rawContent !== '' && ! str_contains($rawContent, 'Owner confirmation needed') && ! str_contains($rawContent, 'Owner to confirm');
@endphp
<section class="catalogue-hero">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <nav class="catalogue-crumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span>Contact Us</span>
        </nav>
        <p class="catalogue-kicker">Customer support</p>
        <h1>{{ $page->title ?? 'Contact Us' }}</h1>
        <p class="catalogue-lead">Send a question about a product, an order, or a fitment.</p>
    </div>
</section>
<section class="contact-page">
    <div class="max-w-6xl mx-auto px-4 py-8 grid lg:grid-cols-[minmax(0,1fr)_320px] gap-6 items-start">
        <div>
            @if($showCms)
                <div class="contact-card contact-copy mb-5">
                    {!! \App\Support\HtmlCleaner::clean($rawContent) !!}
                </div>
            @endif
            @if(session('success'))
                <p class="contact-success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ session('success') }}</p>
            @endif
            <form action="{{ route('contact.submit') }}" method="post" class="contact-card" id="contact-form">
                @csrf
                <h2><i class="fa-regular fa-paper-plane" aria-hidden="true"></i> Write to us</h2>
                <label class="hidden" aria-hidden="true">Company website
                    <input name="company_website" tabindex="-1" autocomplete="off">
                </label>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="checkout-field">Name
                        <span class="checkout-input"><i class="fa-regular fa-user" aria-hidden="true"></i><input name="full_name" value="{{ old('full_name') }}" required maxlength="255" autocomplete="name" placeholder="Your name"></span>
                    </label>
                    <label class="checkout-field">Email
                        <span class="checkout-input"><i class="fa-regular fa-envelope" aria-hidden="true"></i><input type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" placeholder="Email address"></span>
                    </label>
                    <label class="checkout-field">Phone
                        <span class="checkout-input"><i class="fa-solid fa-phone" aria-hidden="true"></i><input name="phone" value="{{ old('phone') }}" required maxlength="20" autocomplete="tel" placeholder="Phone number"></span>
                    </label>
                    <label class="checkout-field">Subject
                        <span class="checkout-input"><i class="fa-regular fa-comment" aria-hidden="true"></i><input name="subject" value="{{ old('subject') }}" maxlength="255" placeholder="What is this about?"></span>
                    </label>
                    <label class="checkout-field sm:col-span-2">Message
                        <span class="checkout-input checkout-input-area"><i class="fa-regular fa-message" aria-hidden="true"></i><textarea name="message" required maxlength="2000" placeholder="Tell us about the product, order, or fitment">{{ old('message') }}</textarea></span>
                    </label>
                </div>
                @if($errors->any())
                    <div class="contact-errors" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                @endif
                <button class="checkout-place" type="submit">Send message</button>
            </form>
        </div>
        <aside class="grid gap-4">
            @if($showEmail)
                <a class="contact-aside" href="mailto:{{ $supportEmail }}">
                    <span class="contact-icon"><i class="fa-regular fa-envelope" aria-hidden="true"></i></span>
                    <span>
                        <strong>Email</strong>
                        <span>{{ $supportEmail }}</span>
                    </span>
                </a>
            @endif
            <div class="contact-aside">
                <span class="contact-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></span>
                <span>
                    <strong>Products</strong>
                    <span>Ask about a cover, mat, or other catalogue item.</span>
                </span>
            </div>
            <div class="contact-aside">
                <span class="contact-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
                <span>
                    <strong>Orders</strong>
                    <span>Include your order number if you already placed one.</span>
                </span>
            </div>
            <div class="contact-aside">
                <span class="contact-icon"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></span>
                <span>
                    <strong>Fitment</strong>
                    <span>Tell us the vehicle so we can check the listing.</span>
                </span>
            </div>
        </aside>
    </div>
</section>
@endsection
