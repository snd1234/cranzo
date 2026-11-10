@extends('layout.app')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-stone-500 mb-4" aria-label="Breadcrumb">
        <a href="{{ url('/') }}" class="hover:text-stone-800">Home</a>
        <span class="mx-1">/</span>
        <span>Contact Us</span>
    </nav>
    <h1 class="text-3xl font-semibold">{{ $page->title ?? 'Contact Cranzo' }}</h1>
    @if(!empty($page?->content))
        <div class="mt-4 bg-white border border-stone-200 rounded-xl p-5 space-y-3 [&_a]:text-blue-700">
            {!! \App\Support\HtmlCleaner::clean($page->content) !!}
        </div>
    @else
        <p class="mt-2 text-stone-600">Send a question about a product, an order, or a fitment.</p>
    @endif
    @if(session('success'))
        <p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>
    @endif
    <form action="{{ route('contact.submit') }}" method="post" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4" id="contact-form">
        @csrf
        <label class="hidden" aria-hidden="true">Company website
            <input name="company_website" tabindex="-1" autocomplete="off">
        </label>
        <label>Name
            <input name="full_name" value="{{ old('full_name') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Phone
            <input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Subject
            <input name="subject" value="{{ old('subject') }}" class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Message
            <textarea name="message" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2 h-32">{{ old('message') }}</textarea>
        </label>
        @if($errors->any())
            <div class="text-red-700 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif
        <button class="bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium" type="submit">Send message</button>
    </form>
</section>
<script>
document.getElementById('contact-form')?.addEventListener('submit', function () {
    const button = this.querySelector('[type=submit]');
    if (button) button.disabled = true;
});
</script>
@endsection
