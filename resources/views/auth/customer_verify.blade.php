@extends('layout.app')

@section('content')
<section class="max-w-lg mx-auto px-4 py-12">
    <div class="shop-card p-6 sm:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#8a6a24]">Cranzo account</p>
        <h1 class="mt-2 text-3xl font-semibold text-[#1b1613]">Confirm your email</h1>
        <p class="mt-3 text-stone-600">We sent a confirmation link to <strong>{{ auth()->user()->email }}</strong>. Open it to use your account. The link expires in 48 hours.</p>
        @if(session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-green-800">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-red-800">{{ session('error') }}</p>
        @endif
        <form method="post" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button class="site-gold-btn" type="submit">Resend confirmation email</button>
        </form>
        <form method="post" action="{{ route('customer.logout') }}" class="mt-4">
            @csrf
            <button class="text-sm font-semibold text-[#8a6a24]" type="submit">Sign out</button>
        </form>
    </div>
</section>
@endsection
