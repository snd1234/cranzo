@extends('layout.app')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-semibold">Contact Cranzo</h1>
    <p class="mt-2 text-stone-600">Send a question about a product, an order, or a fitment.</p>
    @if(session('success'))
        <p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>
    @endif
    <form action="{{ route('contact.submit') }}" method="post" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4">
        @csrf
        <label>Name
            <input name="full_name" value="{{ old('full_name') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
        </label>
        <label>Phone
            <input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full border border-stone-300 rounded-lg px-3 py-2">
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
@endsection
