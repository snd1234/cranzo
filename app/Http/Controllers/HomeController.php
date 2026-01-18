<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use App\Models\{Page, Product, Category, Subcategory, Blog };
use Illuminate\Support\Facades\DB, Hash, Auth, Crypt, Mail;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Arr;
use DataTables;
use Validator;
use Response;
use Illuminate\Support\Str; 

class HomeController extends Controller
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
        //echo "<pre>";print_r($countries);die;
        return view('index',compact('blogs','latestnews','webinar','events','countries'));
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
        return view('events');
    }
    public function newsRoom()
    {
        return view('news_room');
    }
    public function contactUs()
    {
        return view('contact_us');
    }

    public function products()
    {
        return view('products');
    }

    public function productDetail()
    {
        return view('product_detail');
    }

    public function ourSolutionDetail()
    {
        return view('our_solution_detail');
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
        //echo "<pre>";print_r($blog);die;
        return view('blog_detail', compact('blog'));
    }

}
