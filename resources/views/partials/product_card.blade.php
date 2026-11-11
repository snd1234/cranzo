@php
    $imagePath = null;
    foreach ($product->images ?? [] as $image) {
        $candidate = $image->image ?: $image->thumbnail;
        if ($candidate && is_file(public_path('uploads/products/'.$candidate))) {
            $imagePath = $candidate;
            break;
        }
    }
    $selling = (float) $product->discounted_price;
    $regular = (float) ($product->actual_price ?? 0);
    $off = $regular > $selling && $regular > 0 ? (int) round((($regular - $selling) / $regular) * 100) : null;
    $highlight = ! empty($highlight);
@endphp
<article class="product-card shop-card overflow-hidden flex flex-col">
    <a href="{{ \App\Support\CataloguePath::product($product) }}" class="relative block bg-stone-50 aspect-[4/3]">
        @if($highlight)
            <span class="featured-flag">Featured</span>
            @if($off)<span class="featured-off">{{ $off }}% off</span>@endif
        @endif
        @if($imagePath)
            <img src="{{ asset('uploads/products/' . $imagePath) }}" alt="{{ $product->product_title }}" class="h-full w-full object-contain" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span class="hidden h-full w-full items-center justify-center text-stone-400 text-sm">Image coming soon</span>
        @else
            <span class="flex h-full w-full items-center justify-center text-stone-400 text-sm">Image coming soon</span>
        @endif
    </a>
    <div class="p-4 flex flex-col flex-1">
        <h3 class="text-base font-semibold text-slate-900 leading-snug">
            <a href="{{ \App\Support\CataloguePath::product($product) }}">{{ $product->product_title }}</a>
        </h3>
        <p class="text-xs text-stone-500 mt-1">SKU {{ $product->sku_id }}</p>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-lg font-semibold text-slate-950">₹{{ number_format($selling, 0) }}</span>
            @if($regular > $selling)
                <span class="text-sm text-stone-400 line-through">₹{{ number_format($regular, 0) }}</span>
                @if($off)<span class="text-xs font-semibold text-amber-700">{{ $off }}% off</span>@endif
            @endif
        </div>
        <a href="{{ \App\Support\CataloguePath::product($product) }}" class="{{ $highlight ? 'featured-view' : 'mt-4 inline-flex text-sm font-medium text-blue-700' }}">View product</a>
    </div>
</article>
