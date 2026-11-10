<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    public function __construct()
    {
        $categories = DB::table('category')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $category->subcategories = DB::table('sub_category')
                    ->where('category_id', $category->id)
                    ->where('status', 1)
                    ->orderBy('name')
                    ->get();

                return $category;
            });

        $latestProducts = DB::table('product')
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $this->attachProductImages($latestProducts);

        $cart = session('shop_cart', []);
        $cartCount = 0;
        foreach ($cart as $row) {
            $cartCount += (int) ($row['qty'] ?? 0);
        }

        view()->share('headerCategories', $categories);
        view()->share('latestProducts', $latestProducts);
        view()->share('cartCount', $cartCount);
    }

    protected function attachProductImages(Collection $products): Collection
    {
        $ids = $products->pluck('id')->filter()->all();

        $images = $ids === []
            ? collect()
            : DB::table('product_images')->whereIn('product_id', $ids)->get()->groupBy('product_id');

        foreach ($products as $product) {
            $product->images = $images->get($product->id, collect());
        }

        return $products;
    }
}
