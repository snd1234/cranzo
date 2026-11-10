<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends BaseController
{
    public function index()
    {
        $featured = DB::table('product')
            ->where('status', 1)
            ->where('featured_item', 1)
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $this->attachProductImages($featured);

        return view('index', compact('featured'));
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

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        DB::table('contact_us')->insert([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Thank you. We have received your message.');
    }

    public function products(Request $request)
    {
        $query = DB::table('product')->where('status', 1);

        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('product_title', 'like', $term)
                    ->orWhere('sku_id', 'like', $term);
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', (int) $request->input('category'));
        }

        if ($request->filled('subcategory')) {
            $query->where('sub_category_id', (int) $request->input('subcategory'));
        }

        $sort = $request->input('sort', 'name');
        if ($sort === 'price_asc') {
            $query->orderBy('discounted_price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('discounted_price');
        } elseif ($sort === 'newest') {
            $query->orderByDesc('id');
        } else {
            $query->orderBy('product_title');
        }

        $products = $query->paginate(12)->withQueryString();
        $this->attachProductImages(collect($products->items()));

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

        $related = DB::table('product')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(4)
            ->get();
        $this->attachProductImages($related);

        $stock = (int) DB::table('product_details')->where('product_id', $product->id)->sum('product_quantity');

        return view('product_detail', compact('product', 'productImages', 'productCatalogs', 'related', 'stock'));
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

        $this->attachProductImages($products);

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
