@extends('layout.app')
@section('content')
<section class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Reset password</h1>
    @if(session('success'))<p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('password.email') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4">
        @csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" class="shop-field" required></label>
        <button class="bg-stone-900 text-white rounded-full px-5 py-3" type="submit">Send reset link</button>
    </form>
</section>
@endsection
