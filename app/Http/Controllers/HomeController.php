<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('index');
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


}
