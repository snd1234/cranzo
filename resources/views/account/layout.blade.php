@extends('layout.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 grid lg:grid-cols-4 gap-6">
    <aside class="bg-white border border-stone-200 rounded-xl p-4 h-fit">
        <p class="font-semibold">{{ auth()->user()->displayName() }}</p>
        <p class="text-sm text-stone-500">{{ auth()->user()->email }}</p>
        <nav class="grid gap-2 mt-4 text-sm">
            <a class="hover:text-blue-700" href="{{ route('account.dashboard') }}">Dashboard</a>
            <a class="hover:text-blue-700" href="{{ route('account.orders') }}">Orders</a>
            <a class="hover:text-blue-700" href="{{ route('account.addresses') }}">Addresses</a>
            <a class="hover:text-blue-700" href="{{ route('wishlist.index') }}">Wishlist</a>
            <a class="hover:text-blue-700" href="{{ route('account.returns') }}">Returns</a>
            <a class="hover:text-blue-700" href="{{ route('account.profile') }}">Profile</a>
            <form method="post" action="{{ route('customer.logout') }}">@csrf<button class="text-left hover:text-blue-700" type="submit">Log out</button></form>
        </nav>
    </aside>
    <div class="lg:col-span-3">
        @if(session('success'))<p class="mb-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>@endif
        @if($errors->any())<div class="mb-4 bg-red-50 text-red-800 rounded-lg px-4 py-3 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        @yield('account')
    </div>
</div>
@endsection
