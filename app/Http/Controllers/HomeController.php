<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends BaseController
{
    public function index()
    {
        $events = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 4)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $latestnews = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 2)
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $webinar = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 3)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $blogs = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 1)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $categories = DB::table('category')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('index', compact('events', 'latestnews', 'webinar', 'blogs', 'categories'));
    }

    public function aboutUs()
    {
        $aboutUs = null;

        if (Schema::hasTable('pages')) {
            $aboutUs = DB::table('pages')
                ->where('status', 1)
                ->where('slug', 'about-us')
                ->first();
        }

        return view('about_us', compact('aboutUs'));
    }

    public function ourPartners()
    {
        $partners = [];

        if (Schema::hasTable('partners')) {
            $partners = DB::table('partners')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get();
        }

        return view('our_partners', compact('partners'));
    }

    public function events()
    {
        $events = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 4)
            ->orderByDesc('created_at')
            ->get();

        return view('events', compact('events'));
    }

    public function newsRoom()
    {
        $latestnews = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 2)
            ->orderByDesc('created_at')
            ->get();

        return view('news_room', compact('latestnews'));
    }

    public function contactUs()
    {
        return view('contact_us');
    }

    public function products()
    {
        $products = DB::table('product')
            ->where('status', 1)
            ->orderBy('product_title')
            ->get();

        foreach ($products as $product) {
            $product->images = DB::table('product_images')
                ->where('product_id', $product->id)
                ->get();
        }

        return view('products', compact('products'));
    }

    public function productDetail($slug)
    {
        $product = DB::table('product')
            ->where(function ($query) use ($slug) {
                $query->where('id', $slug)
                    ->orWhere('product_title', $slug);
            })
            ->where('status', 1)
            ->first();

        if (! $product) {
            abort(404);
        }

        $productImages = DB::table('product_images')
            ->where('product_id', $product->id)
            ->get();

        $productCatalogs = DB::table('product_images')
            ->where('product_id', $product->id)
            ->get();

        $subcategories = DB::table('sub_category')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('product_detail', compact('product', 'productImages', 'subcategories', 'productCatalogs'));
    }

    public function ourSolutionDetail($slug)
    {
        $category = DB::table('category')
            ->where(function ($query) use ($slug) {
                $query->where('id', $slug)
                    ->orWhere('name', $slug);
            })
            ->where('status', 1)
            ->first();

        if (! $category) {
            abort(404);
        }

        $products = DB::table('product')
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        $subcategories = DB::table('sub_category')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('our_solution_detail', compact('category', 'products', 'subcategories'));
    }

    public function commonDetailPage()
    {
        return view('common_detail_page');
    }

    public function blogDetail($slug)
    {
        $blog = DB::table('blogs')
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (! $blog) {
            abort(404);
        }

        $relatedBlogs = DB::table('blogs')
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('blog_detail', compact('blog', 'relatedBlogs'));
    }
}
