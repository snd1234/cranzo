<?php

namespace App\Support;

class CataloguePath
{
    public static function category(object|array $category): string
    {
        return url('/our-solutions/'.(data_get($category, 'slug') ?: data_get($category, 'id')));
    }

    public static function product(object|array $product): string
    {
        return url('/product-detail/'.(data_get($product, 'slug') ?: data_get($product, 'id')));
    }

    public static function subcategory(object|array $subcategory): string
    {
        return url('/products?subcategory='.(data_get($subcategory, 'slug') ?: data_get($subcategory, 'id')));
    }
}
