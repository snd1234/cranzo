<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    @if(!empty($seo['keywords']))
        <meta name="keywords" content="{{ $seo['keywords'] }}">
    @endif
    <meta name="robots" content="{{ $seo['robots'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    @if(!empty($seo['image']))
        <meta property="og:image" content="{{ $seo['image'] }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('front/css/shop.css') }}">
</head>
<body class="bg-stone-100 text-slate-900">
    @php
        $carMenu = $headerCategories->first(fn ($category) => stripos($category->name, 'car access') !== false);
        $bikeMenu = $headerCategories->first(fn ($category) => stripos($category->name, 'bike access') !== false);
        $navCategories = $headerCategories->reject(fn ($category) => in_array($category->id, array_filter([$carMenu->id ?? null, $bikeMenu->id ?? null]), true))->take(4);
    @endphp
    <div class="site-bar text-xs sm:text-sm">
        <p class="max-w-7xl mx-auto px-4 py-2 text-center">Genuine Cranzo products · Car and bike accessories · Cash on delivery</p>
    </div>

    <header class="site-header sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3 lg:gap-6">
            <a href="{{ url('/') }}" class="shrink-0 flex items-center gap-3">
                <img src="{{ asset('front/images/cranzo-logo.jpg') }}" alt="Cranzo" class="h-14 w-14 rounded-lg object-cover">
                <span class="site-word hidden sm:inline">Cranzo</span>
            </a>
            <div class="hidden md:flex flex-1 justify-center px-8 lg:px-16">
                <form action="{{ url('/products') }}" method="get" class="relative w-full max-w-xl">
                    <label class="sr-only" for="site-search">Search products</label>
                    <input id="site-search" type="search" name="q" value="{{ request('q') }}" placeholder="Search product, category or brand..." class="site-search w-full pr-12">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-700" type="submit" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="ml-auto flex items-center gap-4 sm:gap-6">
                <a href="{{ auth()->check() ? route('account.dashboard') : route('customer.login') }}" class="hidden sm:flex flex-col items-center text-xs leading-tight">
                    <i class="fa-regular fa-user text-lg"></i>
                    <span>My Account</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="relative hidden sm:flex flex-col items-center text-xs leading-tight">
                    <i class="fa-regular fa-heart text-lg"></i>
                    <span>Wishlist</span>
                    <span class="absolute -top-2 -right-3 bg-[var(--shop-gold)] text-[var(--shop-ink)] text-[10px] font-semibold rounded-full min-w-5 h-5 px-1 inline-flex items-center justify-center">{{ $wishlistCount }}</span>
                </a>
                <a href="{{ url('/cart') }}" class="relative flex flex-col items-center text-xs leading-tight" aria-label="Cart, {{ $cartCount }} items">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <span class="hidden sm:inline">Cart</span>
                    <span class="absolute -top-2 -right-3 bg-[var(--shop-gold)] text-[var(--shop-ink)] text-[10px] font-semibold rounded-full min-w-5 h-5 px-1 inline-flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                <button type="button" class="lg:hidden" aria-expanded="false" aria-controls="mobile-menu" onclick="const menu=document.getElementById('mobile-menu'); menu.classList.toggle('hidden'); this.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');" aria-label="Open menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ url('/products') }}" method="get" class="md:hidden px-4 pb-3 relative">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search product, category or brand..." class="site-search w-full pr-12">
            <button class="absolute right-7 top-1/2 -translate-y-1/2 text-stone-700" type="submit" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <nav class="site-nav hidden lg:block">
            <ul class="max-w-7xl mx-auto px-4 flex items-center gap-8 text-sm">
                @if($carMenu)
                    <li class="relative group py-3">
                        <a href="{{ \App\Support\CataloguePath::category($carMenu) }}" class="inline-flex items-center gap-1">Car Accessories <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
                        <div class="nav-drop absolute left-0 top-full z-40">
                            <p class="nav-drop-title">{{ $carMenu->name }}</p>
                            <div class="nav-drop-grid">
                                @forelse($carMenu->subcategories as $subcategory)
                                    <a href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}">{{ $subcategory->name }}</a>
                                @empty
                                    <a href="{{ \App\Support\CataloguePath::category($carMenu) }}">View all</a>
                                @endforelse
                            </div>
                        </div>
                    </li>
                @endif
                @if($bikeMenu)
                    <li class="relative group py-3">
                        <a href="{{ \App\Support\CataloguePath::category($bikeMenu) }}" class="inline-flex items-center gap-1">Bike Accessories <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
                        <div class="nav-drop absolute left-0 top-full z-40">
                            <p class="nav-drop-title">{{ $bikeMenu->name }}</p>
                            <div class="nav-drop-grid">
                                @forelse($bikeMenu->subcategories as $subcategory)
                                    <a href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}">{{ $subcategory->name }}</a>
                                @empty
                                    <a href="{{ \App\Support\CataloguePath::category($bikeMenu) }}">View all</a>
                                @endforelse
                            </div>
                        </div>
                    </li>
                @endif
                @foreach($navCategories as $category)
                    <li class="py-3"><a href="{{ \App\Support\CataloguePath::category($category) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </nav>
        <div id="mobile-menu" class="site-nav hidden lg:hidden px-4 py-4 space-y-3 text-sm max-h-[70vh] overflow-y-auto">
            <a class="block" href="{{ url('/products') }}">All products</a>
            <a class="block" href="{{ url('/cart') }}">Cart ({{ $cartCount }})</a>
            <a class="block" href="{{ route('wishlist.index') }}">Wishlist ({{ $wishlistCount }})</a>
            <a class="block" href="{{ auth()->check() ? route('account.dashboard') : route('customer.login') }}">My Account</a>
            <a class="block" href="{{ url('/contact-us') }}">Contact</a>
            @if($carMenu)
                <a class="block font-semibold" href="{{ \App\Support\CataloguePath::category($carMenu) }}">Car Accessories</a>
                @foreach($carMenu->subcategories as $subcategory)
                    <a class="site-sub block pl-3" href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}">{{ $subcategory->name }}</a>
                @endforeach
            @endif
            @if($bikeMenu)
                <a class="block font-semibold" href="{{ \App\Support\CataloguePath::category($bikeMenu) }}">Bike Accessories</a>
                @foreach($bikeMenu->subcategories as $subcategory)
                    <a class="site-sub block pl-3" href="{{ \App\Support\CataloguePath::subcategory($subcategory) }}">{{ $subcategory->name }}</a>
                @endforeach
            @endif
        </div>
    </header>

    @yield('content')

    <footer class="site-footer mt-12">
        <div class="max-w-7xl mx-auto px-4 py-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <img src="{{ asset('front/images/cranzo-logo.jpg') }}" alt="Cranzo" class="h-16 w-16 rounded-lg mb-4">
                <h2 class="mb-3">About the store</h2>
                <p>Cranzo supplies car covers, floor mats, seat covers, bike covers, and everyday automotive accessories.</p>
                <a href="{{ url('/contact-us') }}" class="inline-block mt-3 hover:text-white">Contact information</a>
            </div>
            <div>
                <h2 class="mb-4">Customer Support</h2>
                <div class="grid gap-2">
                    @foreach($footerPages->where('footer_group', 'support') as $page)
                        <a href="{{ \App\Support\CmsPages::url($page->slug) }}" class="hover:text-white">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h2 class="mb-4">Information</h2>
                <div class="grid gap-2">
                    @foreach($footerPages->where('footer_group', 'information') as $page)
                        <a href="{{ \App\Support\CmsPages::url($page->slug) }}" class="hover:text-white">{{ $page->title }}</a>
                    @endforeach
                    <a href="{{ url('/products') }}" class="hover:text-white">Shop</a>
                </div>
            </div>
            <div>
                <h2 class="mb-3">Stay Updated with the Latest Automotive Deals</h2>
                <p class="text-sm mb-3">Subscribe to our newsletter and be the first to know about new arrivals, exclusive offers, automotive accessories and special promotions.</p>
                <form id="newsletter-form" data-skip-lock="1" action="{{ route('newsletter.subscribe') }}" method="post" class="grid gap-2">
                    @csrf
                    <label class="hidden" aria-hidden="true">Company website
                        <input name="company_website" tabindex="-1" autocomplete="off">
                    </label>
                    <input type="email" name="email" required placeholder="Email address" class="site-search">
                    <label class="flex gap-2 items-start text-sm">
                        <input type="checkbox" name="consent" value="1" class="mt-1">
                        <span>Send me product news and offers.</span>
                    </label>
                    <button class="site-gold-btn" type="submit">Subscribe</button>
                    <p id="newsletter-message" class="text-sm min-h-5" role="status"></p>
                </form>
            </div>
        </div>
        <p class="site-legal">cranzo.in, {{ date('Y') }}. All rights reserved. Cash on delivery. Online payment is not enabled yet.</p>
    </footer>
    <script src="{{ asset('front/js/shop.js') }}"></script>
    <script>
    document.getElementById('newsletter-form')?.addEventListener('submit', async function (event) {
        event.preventDefault();
        const form = this;
        const button = form.querySelector('[type=submit]');
        const message = document.getElementById('newsletter-message');
        button.disabled = true;
        message.textContent = 'Sending...';
        message.className = 'text-sm min-h-5 text-stone-300';
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value },
                body: new FormData(form)
            });
            const data = await response.json();
            message.textContent = data.message || 'Saved.';
            message.className = response.ok ? 'text-sm min-h-5 text-green-300' : 'text-sm min-h-5 text-amber-300';
            if (response.ok) form.reset();
        } catch (error) {
            message.textContent = 'The subscription could not be saved. Try again.';
            message.className = 'text-sm min-h-5 text-amber-300';
        }
        button.disabled = false;
    });
    </script>
</body>
</html>
