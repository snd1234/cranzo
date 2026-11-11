@extends('layout.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 lg:py-10 grid lg:grid-cols-[240px_minmax(0,1fr)] gap-6">
    <aside class="bg-white border border-stone-200 rounded-2xl shadow-sm p-4 h-fit lg:sticky lg:top-24">
        <div class="flex items-center gap-3 px-1">
            <span class="h-11 w-11 rounded-full bg-blue-700 text-white inline-flex items-center justify-center font-semibold">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</span>
            <div class="min-w-0">
                <p class="font-semibold text-slate-900 truncate">{{ auth()->user()->displayName() }}</p>
                <p class="text-xs text-stone-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <nav class="grid gap-1 mt-4 text-sm">
            @foreach ([
                ['account.dashboard', 'account.dashboard', 'fa-gauge-high', 'Dashboard'],
                ['account.orders', 'account.order*', 'fa-box', 'Orders'],
                ['account.addresses', 'account.addresses', 'fa-location-dot', 'Addresses'],
                ['wishlist.index', 'wishlist.index', 'fa-heart', 'Wishlist'],
                ['account.returns', 'account.returns', 'fa-rotate-left', 'Returns'],
                ['account.profile', 'account.profile', 'fa-user', 'Profile'],
            ] as [$routeName, $match, $icon, $label])
                <a class="rounded-lg px-3 py-2 inline-flex items-center gap-2 {{ request()->routeIs($match) ? 'bg-blue-50 text-blue-800 font-medium' : 'text-slate-700 hover:bg-stone-50' }}" href="{{ route($routeName) }}">
                    <i class="fa-solid {{ $icon }} w-4 text-center text-xs"></i>{{ $label }}
                </a>
            @endforeach
            <form method="post" action="{{ route('customer.logout') }}">@csrf<button class="w-full rounded-lg px-3 py-2 text-left text-slate-700 hover:bg-stone-50 inline-flex items-center gap-2" type="submit"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center text-xs"></i>Log out</button></form>
        </nav>
    </aside>
    <div class="min-w-0">
        @if(session('success'))<p class="mb-4 bg-green-50 text-green-800 border border-green-100 rounded-xl px-4 py-3">{{ session('success') }}</p>@endif
        @if($errors->any())<div class="mb-4 bg-red-50 text-red-800 border border-red-100 rounded-xl px-4 py-3 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        @yield('account')
    </div>
</div>
@endsection
