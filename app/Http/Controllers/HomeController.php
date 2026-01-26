<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use App\Models\{Page, Products, ProductCategory, ProductSubCategory, Blog, ProductImage };
use Illuminate\Support\Facades\DB, Hash, Auth, Crypt, Mail;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Arr;
use DataTables;
use Validator;
use Response;
use Illuminate\Support\Str; 

class HomeController extends BaseController
{
    //
    public function index()
    {
        $blogs = DB::table('blogs')->where('status', 1)->where('type', 1)->get()->take(3);
        $latestnews = DB::table('blogs')->where('status', 1)->where('type', 2)->get()->take(5);
        $webinar = DB::table('blogs')->where('status', 1)->where('type', 3)->get()->take(3);
        $events = DB::table('blogs')->where('status', 1)->where('type', 4)->get()->take(3);
        $countries = DB::table('countries')->select('iso3', 'name')->where('status', 1)->orderBy('name', 'asc')->get();
        $countries = $countries->mapWithKeys(function ($item) {
            return [$item->iso3 => $item->name];
        });
        $categories = DB::table('product_category')->where('status', 1)->get()->take(3);
        // echo "<pre>";print_r($featuredProducts);die;
        return view('index',compact('blogs','latestnews','webinar','events','countries','categories'));
    }


    public function aboutUs()
    {
        return view('about_us');
    }
   
    public function ourPartners()
    {
        return view('our_partners');
    }
    public function events()
    {
        
        $events = DB::table('blogs')->where('status', 1)->where('type', 4)->get();
        return view('events',compact('events'));
    }
    public function newsRoom()
    {
        $latestnews = DB::table('blogs')->where('status', 1)->where('type', 2)->get();
        return view('news_room',compact('latestnews'));
    }
    public function contactUs()
    {
        return view('contact_us');
    }

    public function products()
    {
        $products = Products::with('productImages')
        ->where('status', 1)
        ->orderBy('title')
        ->get();
        return view('products',compact('products'));
    }

    public function productDetail($slug)
    {
        $product = Products::where('slug', $slug)->where('status', 1)->firstOrFail();
        if (!$product) {
            abort(404);
        }
        $product_images = DB::table('product_image')->where('product_id', $product->id)->get();
        $product_catalogs = DB::table('product_catalogs')->where('product_id', $product->id)->get();
       
        $subcategories =  ProductSubCategory::with(['products'])->where('status', 1)->orderBy('name')->get();
        $countries = DB::table('countries')->select('iso3', 'name')->where('status', 1)->orderBy('name', 'asc')->get();
        $countries = $countries->mapWithKeys(function ($item) {
            return [$item->iso3 => $item->name];
        });
        return view('product_detail', compact('product','product_images','subcategories', 'countries', 'product_catalogs'));
    }

    public function ourSolutionDetail($slug)
    {
        $category = ProductCategory::where('slug', $slug)->firstOrFail();
        if (!$category) {
            abort(404);
        }
        $products = Products::with('productImages')
        ->where('category_id', $category->id)
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->get();
        $subcategories =  ProductSubCategory::with(['products'])->where('status', 1)->orderBy('name')->get();
        return view('our_solution_detail', compact('category','products', 'subcategories'));
    }

    public function commonDetailPage()
    {
        return view('common_detail_page');
    }

    public function blogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        if (!$blog) {
            abort(404);
        }
        $subcategories =  ProductSubCategory::with(['products'])->where('status', 1)->orderBy('name')->get();
        return view('blog_detail', compact('blog', 'subcategories'));
    }

}
