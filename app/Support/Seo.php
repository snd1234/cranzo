<?php

namespace App\Support;

use Illuminate\Support\Str;

class Seo
{
    public static function defaults(): array
    {
        $name = request()->route()?->getName();
        $page = config('seo.pages.'.$name) ?? config('seo.pages.default');

        return [
            'title' => $page['title'],
            'description' => $page['description'],
            'keywords' => $page['keywords'] ?? '',
            'canonical' => url()->current(),
            'robots' => $page['robots'] ?? 'index,follow',
            'type' => 'website',
        ];
    }

    public static function share(array $meta): void
    {
        view()->share('seo', array_merge(self::defaults(), array_filter($meta, fn ($value) => $value !== null && $value !== '')));
    }

    public static function forCategory(object $category): void
    {
        $description = $category->meta_description ?: $category->name.' car and bike accessories from Cranzo.';

        self::share([
            'title' => $category->meta_title ?: $category->name.' | Cranzo',
            'description' => Str::limit(trim(strip_tags($description)), 160),
            'keywords' => $category->meta_keywords ?: '',
            'canonical' => CataloguePath::category($category),
        ]);
    }

    public static function forProduct(object $product): void
    {
        $description = $product->meta_description ?: strip_tags((string) $product->description);

        self::share([
            'title' => $product->meta_title ?: $product->product_title.' | Cranzo',
            'description' => Str::limit(trim($description), 160),
            'keywords' => $product->meta_keywords ?: '',
            'canonical' => CataloguePath::product($product),
            'type' => 'product',
        ]);
    }

    public static function forCms(object $page): void
    {
        $description = $page->meta_description ?: ($page->excerpt ?: strip_tags((string) $page->content));

        self::share([
            'title' => $page->meta_title ?: $page->title.' | Cranzo',
            'description' => Str::limit(trim($description), 160),
            'keywords' => $page->meta_keywords ?: '',
            'canonical' => $page->canonical_url ?: \App\Support\CmsPages::url($page->slug),
            'image' => $page->image ? asset('uploads/cms/'.basename($page->image)) : null,
        ]);
    }
}
