@php
    $image = $product->images->first() ?? null;
    $imagePath = $image ? ($image->image ?: $image->thumbnail) : null;
    $selling = (float) $product->discounted_price;
    $regular = (float) $product->actual_price;
    $off = $regular > $selling && $regular > 0 ? (int) round((($regular - $selling) / $regular) * 100) : null;
@endphp
<article class="bg-white rounded-xl shadow-sm overflow-hidden border border-stone-200 flex flex-col">
    <a href="{{ \App\Support\CataloguePath::product($product) }}" class="block bg-stone-100 h-48">
        @if($imagePath)
            <img src="{{ asset('uploads/products/' . $imagePath) }}" alt="{{ $product->product_title }}" class="h-48 w-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span class="hidden h-48 w-full items-center justify-center text-stone-400 text-sm">Image coming soon</span>
        @else
            <span class="flex h-48 w-full items-center justify-center text-stone-400 text-sm">Image coming soon</span>
        @endif
    </a>
    <div class="p-5 flex flex-col flex-1">
        <h3 class="text-base font-semibold text-stone-900 leading-snug">
            <a href="{{ \App\Support\CataloguePath::product($product) }}">{{ $product->product_title }}</a>
        </h3>
        <p class="text-sm text-stone-500 mt-1">SKU {{ $product->sku_id }}</p>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-lg font-semibold text-stone-900">₹{{ number_format($selling, 0) }}</span>
            @if($regular > $selling)
                <span class="text-sm text-stone-400 line-through">₹{{ number_format($regular, 0) }}</span>
                @if($off)
                    <span class="text-xs font-medium text-amber-700">{{ $off }}% off</span>
                @endif
            @endif
        </div>
        <a href="{{ \App\Support\CataloguePath::product($product) }}" class="mt-4 inline-flex text-sm font-medium text-amber-800">View product</a>
    </div>
</article>
