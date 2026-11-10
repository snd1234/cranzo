<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ url('/') }}</loc></url>
    <url><loc>{{ url('/products') }}</loc></url>
    @foreach($categories as $category)
        <url>
            <loc>{{ url('/our-solutions/'.$category->slug) }}</loc>
            @if($category->updated_at)<lastmod>{{ \Illuminate\Support\Carbon::parse($category->updated_at)->toDateString() }}</lastmod>@endif
        </url>
    @endforeach
    @foreach($subcategories as $subcategory)
        <url>
            <loc>{{ url('/products?subcategory='.$subcategory->slug) }}</loc>
        </url>
    @endforeach
    @foreach($pages as $page)
        <url>
            <loc>{{ \App\Support\CmsPages::url($page->slug) }}</loc>
            @if($page->updated_at)<lastmod>{{ \Illuminate\Support\Carbon::parse($page->updated_at)->toDateString() }}</lastmod>@endif
        </url>
    @endforeach
    @foreach($products as $product)
        <url>
            <loc>{{ url('/product-detail/'.$product->slug) }}</loc>
            @if($product->updated_at)<lastmod>{{ \Illuminate\Support\Carbon::parse($product->updated_at)->toDateString() }}</lastmod>@endif
        </url>
    @endforeach
</urlset>
