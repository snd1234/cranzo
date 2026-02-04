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
    

    public function subscribeNewsletter(Request $request)
    {
       if ($request->ajax()) {
           
            $requestData =  $request->all();
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
                'source' => 'Newsletter Subscription',
                'ip_address' => $request->ip(),
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

    public function contactUs(Request $request)
    {
       if ($request->ajax()) {
            $requestData =  $request->all();
            // Validate email
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'full_name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return Response::json(['status' => 'error', 'errors' => $validator->errors()->all()]);
            }
            // Save to database
            $inserted = DB::table('contact_us')->insert([
                'email' => isset($requestData['email']) ? $requestData['email'] : null,
                'name' => isset($requestData['full_name']) ? $requestData['full_name'] : null,
                'phone' => isset($requestData['phone']) ? $requestData['phone'] : null,
                'message' => isset($requestData['message']) ? $requestData['message'] : null,
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted) {
                return Response::json(['status' => 'success', 'message' => 'Thank you for contacting us!']);
            } else {
                return Response::json(['status' => 'error', 'message' => 'Failed to contact us. Please try again later.']);
            }
         }
    }

    public function productEnquiry(Request $request)
    {
       if ($request->ajax()) {
            $requestData =  $request->all();
            //echo "<pre>";print_r($requestData);die;
            // Validate email
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'full_name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'pincode' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return Response::json(['status' => 'error', 'errors' => $validator->errors()->all()]);
            }
            // Save to database
            $inserted = DB::table('enquiry')->insert([
                'email' => isset($requestData['email']) ? $requestData['email'] : null,
                'full_name' => isset($requestData['full_name']) ? $requestData['full_name'] : null,
                'designation' => isset($requestData['designation']) ? $requestData['designation'] : null,
                'mobile_number' => isset($requestData['phone']) ? $requestData['phone'] : null,
                'address' => isset($requestData['address']) ? $requestData['address'] : null,
                'city' => isset($requestData['city']) ? $requestData['city'] : null,
                'state' => isset($requestData['state']) ? $requestData['state'] : null,
                'pin_code' => isset($requestData['pincode']) ? $requestData['pincode'] : null,
                'country' => isset($requestData['country']) ? $requestData['country'] : null,
                'remarks' => isset($requestData['remarks']) ? $requestData['remarks'] : null,
                'source' => 'Product Enquiry',
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted) {
                return Response::json(['status' => 'success', 'message' => 'Thank you for your product enquiry!']);
            } else {
                return Response::json(['status' => 'error', 'message' => 'Failed to submit product enquiry. Please try again later.']);
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
