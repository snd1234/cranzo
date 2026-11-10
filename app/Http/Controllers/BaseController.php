<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseController extends Controller
{
    public function __construct()
    {
        foreach (self::layoutData() as $key => $value) {
            view()->share($key, $value);
        }
    }

    public static function layoutData(): array
    {
        $categories = Schema::hasTable('category')
            ? DB::table('category')
                ->where('status', 1)
                ->orderBy('name')
                ->get()
                ->map(function ($category) {
                    $category->subcategories = Schema::hasTable('sub_category')
                        ? DB::table('sub_category')
                            ->where('category_id', $category->id)
                            ->where('status', 1)
                            ->orderBy('name')
                            ->get()
                        : collect();

                    return $category;
                })
            : collect();

        $latestProducts = Schema::hasTable('product')
            ? DB::table('product')
                ->where('status', 1)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
            : collect();

        if ($latestProducts->isNotEmpty() && Schema::hasTable('product_images')) {
            $ids = $latestProducts->pluck('id')->filter()->all();
            $images = DB::table('product_images')->whereIn('product_id', $ids)->get()->groupBy('product_id');
            foreach ($latestProducts as $product) {
                $product->images = $images->get($product->id, collect());
            }
        }

        $cartCount = 0;
        foreach (session('shop_cart', []) as $row) {
            $cartCount += (int) ($row['qty'] ?? 0);
        }

        $footerPages = Schema::hasTable('cms_pages')
            ? DB::table('cms_pages')
                ->where('status', 1)
                ->where('show_in_footer', 1)
                ->orderBy('footer_order')
                ->orderBy('id')
                ->get()
            : collect();

        return [
            'headerCategories' => $categories,
            'latestProducts' => $latestProducts,
            'cartCount' => $cartCount,
            'wishlistCount' => WishlistController::countForView(),
            'footerPages' => $footerPages,
        ];
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
