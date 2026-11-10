@extends('layout.app')
@section('content')
<section class="max-w-lg mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Confirm your email</h1>
    @if(session('success'))<p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>@endif
    <p class="mt-4 text-stone-600">Open the verification link sent to {{ auth()->user()->email }}. Until mail is configured, that message is written to the application log.</p>
    <form method="post" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button class="bg-stone-900 text-white rounded-full px-5 py-3" type="submit">Resend verification email</button>
    </form>
</section>
@endsection
