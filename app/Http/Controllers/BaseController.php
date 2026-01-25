<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Products;

class BaseController extends Controller
{
    public function __construct()
    {
        $categories =  ProductCategory::with([
                'subcategories.products'
            ])
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $latestproducts = Products::select('id', 'title', 'slug')->where('status', 1)->orderBy('created_at', 'desc')->limit(6)->get();
        view()->share('headerCategories', $categories);
        view()->share('latestProducts', $latestproducts);
    }
}
