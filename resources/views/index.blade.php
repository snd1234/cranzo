@extends('layout.app')

@section('content')
@if($banners->isNotEmpty())
<section class="relative bg-slate-950 overflow-hidden" data-banner-slider>
    @foreach($banners as $banner)
        <article class="banner-slide {{ $loop->first ? '' : 'hidden' }}" data-slide>
            <picture>
                @if($banner->mobile_image)
                    <source media="(max-width: 767px)" srcset="{{ $banner->imageUrl($banner->mobile_image) }}">
                @endif
                <img src="{{ $banner->imageUrl($banner->desktop_image) }}" alt="{{ $banner->title ?: 'Cranzo banner' }}" class="w-full h-[280px] sm:h-[380px] object-cover object-center" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
            </picture>
            @if($banner->title || $banner->subtitle || $banner->description || ($banner->button_text && $banner->button_url))
                <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/35 to-transparent flex items-center">
                    <div class="px-6 sm:px-16 max-w-xl text-white">
                        @if($banner->subtitle)<p class="text-sm uppercase tracking-wide text-amber-300">{{ $banner->subtitle }}</p>@endif
                        @if($banner->title)<h2 class="text-3xl sm:text-5xl font-semibold mt-1">{{ $banner->title }}</h2>@endif
                        @if($banner->description)<p class="mt-3 text-sm sm:text-base text-stone-100 max-w-lg">{{ $banner->description }}</p>@endif
                        @if($banner->button_text && $banner->button_url)
                            <a href="{{ $banner->button_url }}" class="inline-flex mt-5 bg-amber-500 text-stone-950 rounded-full px-5 py-2.5 font-medium">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                </div>
            @endif
        </article>
    @endforeach
    @if($banners->count() > 1)
        <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 text-slate-900 rounded-full w-10 h-10" data-banner-prev aria-label="Previous banner">‹</button>
        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 text-slate-900 rounded-full w-10 h-10" data-banner-next aria-label="Next banner">›</button>
        <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2">
            @foreach($banners as $banner)
                <button type="button" class="w-2.5 h-2.5 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/50' }}" data-banner-dot="{{ $loop->index }}" aria-label="Show banner {{ $loop->iteration }}"></button>
            @endforeach
        </div>
    @endif
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-banner-slider]');
    if (!root || root.dataset.ready) return;
    root.dataset.ready = '1';
    const slides = Array.from(root.querySelectorAll('[data-slide]'));
    if (slides.length < 2) return;
    const dots = Array.from(root.querySelectorAll('[data-banner-dot]'));
    let index = 0;
    let timer;
    const show = function (next) {
        index = (next + slides.length) % slides.length;
        slides.forEach((slide, slideIndex) => slide.classList.toggle('hidden', slideIndex !== index));
        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle('bg-white', dotIndex === index);
            dot.classList.toggle('bg-white/50', dotIndex !== index);
        });
    };
    const start = function () { timer = setInterval(() => show(index + 1), 5000); };
    const stop = function () { clearInterval(timer); };
    root.querySelector('[data-banner-prev]').addEventListener('click', () => show(index - 1));
    root.querySelector('[data-banner-next]').addEventListener('click', () => show(index + 1));
    dots.forEach(dot => dot.addEventListener('click', () => show(Number(dot.dataset.bannerDot))));
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    let touchX = null;
    root.addEventListener('touchstart', event => { touchX = event.changedTouches[0].clientX; }, {passive: true});
    root.addEventListener('touchend', event => {
        if (touchX === null) return;
        const diff = event.changedTouches[0].clientX - touchX;
        if (Math.abs(diff) > 40) show(index + (diff < 0 ? 1 : -1));
        touchX = null;
    });
    start();
});
</script>
@else
<section class="bg-slate-950">
    <a href="{{ url('/products') }}" class="block">
        <img src="{{ asset('front/images/banner.jpg') }}" alt="Car accessories. We add charm to your vehicle." class="w-full h-auto max-h-[420px] object-cover object-center">
    </a>
</section>
@endif

<section class="home-notes" aria-label="Shopping notes">
    <div class="max-w-7xl mx-auto px-4 py-6 grid sm:grid-cols-3 gap-4">
        <article class="home-note">
            <span class="home-note-icon" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
            <div>
                <p class="home-note-label">Catalogue</p>
                <p class="home-note-text">Listed products are from the Cranzo catalogue</p>
            </div>
        </article>
        <article class="home-note">
            <span class="home-note-icon" aria-hidden="true"><i class="fa-solid fa-truck"></i></span>
            <div>
                <p class="home-note-label">Payment</p>
                <p class="home-note-text">Cash on delivery at checkout</p>
            </div>
        </article>
        <article class="home-note">
            <span class="home-note-icon" aria-hidden="true"><i class="fa-solid fa-box"></i></span>
            <div>
                <p class="home-note-label">Stock</p>
                <p class="home-note-text">Stock is checked again when you place an order</p>
            </div>
        </article>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl font-semibold">Categories</h2>
        <a href="{{ url('/products') }}" class="text-sm text-amber-800">View all products</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($headerCategories as $category)
            <a href="{{ \App\Support\CataloguePath::category($category) }}" class="bg-white border border-stone-200 rounded-xl overflow-hidden hover:border-amber-500">
                @if(!empty($category->image))
                    <img src="{{ asset(str_contains($category->image, '/') ? $category->image : 'uploads/categories/'.$category->image) }}" alt="" class="h-36 w-full object-cover">
                @endif
                <div class="p-5">
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-sm text-stone-500 mt-2">{{ $category->subcategories->count() }} {{ \Illuminate\Support\Str::plural('range', $category->subcategories->count()) }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section class="home-featured">
    <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="home-featured-head">
            <div>
                <p class="home-featured-kicker">From the catalogue</p>
                <h2>Featured products</h2>
                <p class="home-featured-note">Marked featured in the shop. Prices are the amounts stored on each product.</p>
            </div>
            <a href="{{ url('/products') }}" class="site-gold-btn">Shop all products</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featured as $product)
                @include('partials.product_card', ['product' => $product, 'highlight' => true])
            @empty
                <p class="home-featured-note">No featured products are active.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
