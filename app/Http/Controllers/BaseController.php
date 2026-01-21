<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;

class BaseController extends Controller
{
    public function __construct()
    {
        $categories = ProductCategory::with('subcategories')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        view()->share('headerCategories', $categories);
    }
}
