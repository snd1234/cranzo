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

class AjaxController extends Controller
{
    //
    public function index()
    {
        $blogs = DB::table('blogs')->where('status', 1)->where('type', 1)->get()->take(3);
        $news = DB::table('blogs')->where('status', 1)->where('type', 2)->first();
        return view('index',compact('blogs','news'));
    }


    public function subscribeNewsletter(Request $request)
    {
       if ($request->ajax()) {
            // $email = $request->input('email');
            // $organization = $request->input('organization');
            $requestData =  $request->all();
            // echo "<pre>";
            // print_r($requestData);
            // echo "</pre>";
            // die;
            // Debugging code removed to prevent memory exhaustion.
              // Validate email
              $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'contact_number' => 'required|string|max:15',
                'full_name' => 'required|string|max:255',
              ]);
    
              if ($validator->fails()) {
                return Response::json(['status' => 'error', 'errors' => $validator->errors()->all()]);
              }
    
              // Save to database
              $inserted = DB::table('enquiry')->insert([
                'email' => isset($requestData['email']) ? $requestData['email'] : null,
                'full_name' => isset($requestData['full_name']) ? $requestData['full_name'] : null,
                'mobile_number' => isset($requestData['contact_number']) ? $requestData['contact_number'] : null,
                'designation' => isset($requestData['designation']) ? $requestData['designation'] : null,
                'organization' => isset($requestData['organisantion']) ? $requestData['organisantion'] : null,
                'country' => isset($requestData['country']) ? $requestData['country'] : null,
                'state' => isset($requestData['state']) ? $requestData['state'] : null,
                'city' => isset($requestData['city']) ? $requestData['city'] : null,
                'remarks' => isset($requestData['remarks']) ? $requestData['remarks'] : null,
                'created_at' => now(),
                'updated_at' => now(),
              ]);

              if ($inserted) {
                  return Response::json(['status' => 'success', 'message' => 'Thank you for subscribing to our newsletter!']);
              } else {
                  return Response::json(['status' => 'error', 'message' => 'Failed to subscribe to the newsletter. Please try again later.']);
              }
         }
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
