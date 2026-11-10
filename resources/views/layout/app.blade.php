<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cranzo | Car and Bike Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-stone-100 text-stone-900">
    @php
        $carMenu = $headerCategories->first(fn ($category) => stripos($category->name, 'car access') !== false);
        $bikeMenu = $headerCategories->first(fn ($category) => stripos($category->name, 'bike access') !== false);
        $navCategories = $headerCategories->reject(fn ($category) => in_array($category->id, array_filter([$carMenu->id ?? null, $bikeMenu->id ?? null]), true))->take(4);
    @endphp
    <div class="bg-blue-700 text-white text-xs sm:text-sm">
        <p class="max-w-7xl mx-auto px-4 py-2 text-center">Genuine Cranzo products • Car and bike accessories • Cash on delivery available</p>
    </div>

    <header class="bg-white sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3 lg:gap-6">
            <a href="{{ url('/') }}" class="shrink-0 flex items-center gap-2">
                <img src="{{ asset('front/images/cranzo-logo.jpg') }}" alt="" class="h-16 w-16 rounded-full object-cover">
                <span class="text-3xl font-extrabold tracking-tight text-slate-900 lowercase">cranzo</span>
            </a>
            <div class="hidden md:flex flex-1 justify-center px-8 lg:px-16">
                <form action="{{ url('/products') }}" method="get" class="relative w-full max-w-xl">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search product, category or brand..." class="w-full rounded-md border border-slate-300 px-4 py-2.5 pr-12 text-slate-800">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-600" type="submit" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="ml-auto flex items-center gap-4 sm:gap-6 text-slate-700">
                <a href="{{ url('/about-us') }}" class="hidden sm:flex flex-col items-center text-xs leading-tight">
                    <i class="fa-regular fa-user text-lg"></i>
                    <span>My Account</span>
                </a>
                <a href="{{ url('/products') }}" class="hidden sm:flex flex-col items-center text-xs leading-tight">
                    <i class="fa-regular fa-heart text-lg"></i>
                    <span>Wishlist</span>
                </a>
                <a href="{{ url('/cart') }}" class="relative flex flex-col items-center text-xs leading-tight" aria-label="Cart">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <span class="hidden sm:inline">Cart</span>
                    <span class="absolute -top-2 -right-3 bg-blue-700 text-white text-[10px] font-semibold rounded-full min-w-5 h-5 px-1 inline-flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                <button type="button" class="lg:hidden" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" aria-label="Open menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ url('/products') }}" method="get" class="md:hidden px-4 pb-3 relative">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search product, category or brand..." class="w-full rounded-md border border-slate-300 px-4 py-2.5 pr-12">
            <button class="absolute right-7 top-1/2 -translate-y-1/2 text-slate-600" type="submit" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <nav class="hidden lg:block border-t border-slate-200">
            <ul class="max-w-7xl mx-auto px-4 flex items-center gap-8 text-sm text-slate-700">
                @if($carMenu)
                    <li class="relative group py-3">
                        <a href="{{ url('/our-solutions/' . $carMenu->id) }}" class="inline-flex items-center gap-1">Car Accessories <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
                        <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-lg border border-slate-100 rounded-md min-w-56 py-2 z-40">
                            @forelse($carMenu->subcategories as $subcategory)
                                <a class="block px-4 py-2 hover:bg-slate-50" href="{{ url('/products?subcategory=' . $subcategory->id) }}">{{ $subcategory->name }}</a>
                            @empty
                                <a class="block px-4 py-2 hover:bg-slate-50" href="{{ url('/our-solutions/' . $carMenu->id) }}">View all</a>
                            @endforelse
                        </div>
                    </li>
                @endif
                @if($bikeMenu)
                    <li class="relative group py-3">
                        <a href="{{ url('/our-solutions/' . $bikeMenu->id) }}" class="inline-flex items-center gap-1">Bike Accessories <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
                        <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-lg border border-slate-100 rounded-md min-w-56 py-2 z-40">
                            @forelse($bikeMenu->subcategories as $subcategory)
                                <a class="block px-4 py-2 hover:bg-slate-50" href="{{ url('/products?subcategory=' . $subcategory->id) }}">{{ $subcategory->name }}</a>
                            @empty
                                <a class="block px-4 py-2 hover:bg-slate-50" href="{{ url('/our-solutions/' . $bikeMenu->id) }}">View all</a>
                            @endforelse
                        </div>
                    </li>
                @endif
                @foreach($navCategories as $category)
                    <li class="py-3"><a href="{{ url('/our-solutions/' . $category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </nav>
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200 px-4 py-4 space-y-3 text-sm max-h-[70vh] overflow-y-auto">
            <a class="block" href="{{ url('/products') }}">All products</a>
            <a class="block" href="{{ url('/cart') }}">Cart ({{ $cartCount }})</a>
            <a class="block" href="{{ url('/about-us') }}">My Account</a>
            <a class="block" href="{{ url('/contact-us') }}">Contact</a>
            @if($carMenu)
                <a class="block font-semibold" href="{{ url('/our-solutions/' . $carMenu->id) }}">Car Accessories</a>
                @foreach($carMenu->subcategories as $subcategory)
                    <a class="block pl-3 text-slate-600" href="{{ url('/products?subcategory=' . $subcategory->id) }}">{{ $subcategory->name }}</a>
                @endforeach
            @endif
            @if($bikeMenu)
                <a class="block font-semibold" href="{{ url('/our-solutions/' . $bikeMenu->id) }}">Bike Accessories</a>
                @foreach($bikeMenu->subcategories as $subcategory)
                    <a class="block pl-3 text-slate-600" href="{{ url('/products?subcategory=' . $subcategory->id) }}">{{ $subcategory->name }}</a>
                @endforeach
            @endif
        </div>
    </header>

    @yield('content')

    <footer class="bg-stone-950 text-stone-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
            <div>
                <img src="{{ asset('front/images/cranzo-logo.jpg') }}" alt="Cranzo" class="h-16 w-16 rounded-full mb-4">
                <p>Cranzo supplies car covers, floor mats, seat covers, bike covers, and everyday automotive accessories.</p>
            </div>
            <div>
                <h2 class="text-white uppercase text-sm mb-4">Shop</h2>
                <div class="grid gap-2">
                    @foreach($headerCategories->take(8) as $category)
                        <a href="{{ url('/our-solutions/' . $category->id) }}" class="hover:text-white">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h2 class="text-white uppercase text-sm mb-4">Latest products</h2>
                <div class="grid gap-2">
                    @foreach($latestProducts as $product)
                        <a href="{{ url('/product-detail/' . $product->id) }}" class="hover:text-white">{{ $product->product_title }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <p class="text-center text-sm border-t border-white/10 py-4">cranzo.in, {{ date('Y') }}. All rights reserved.</p>
    </footer>
</body>
</html>
