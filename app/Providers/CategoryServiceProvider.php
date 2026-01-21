<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductCategory;

class CategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {

            $categories = Cache::remember('header_product_categories', 3600, function () {
                return ProductCategory::with('subcategories')
                    ->where('status', 1)
                    ->orderBy('name')
                    ->get();
            });

            $view->with('headerCategories', $categories);
        });
    }
}
