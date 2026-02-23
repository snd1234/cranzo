<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Partner;
use App\Models\SeoContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AdminController extends BaseController
{
    
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('system-auth/dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/system-auth/login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }   

    public function userList()
    {
        $users = Admin::all();
        return view('admin.users', compact('users'));
    }

    public function editUser($id)
    {
        $user = Admin::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }
    public function showAddUserForm()
    {
        return view('admin.add_user');
    }
    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:1,2,3',
            'status' => 'required|in:0,1',
            'mobile_number' => 'nullable|string|max:15',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'status' => $request->status,
        ]);


        return redirect()->route('admin.users')->with('success', 'User added successfully.');
    }
    public function updateUser(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'mobile_number' => 'nullable|string|max:15',
            //'address' => 'nullable|string|max:255',
            
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->address = $request->address;
        $user->mobile_number = $request->mobile_number;
        $user->status = $request->status;
        //echo "<pre>"; print_r($request->all()); exit;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }   
    public function viewUser($id)
    {
        $user = Admin::findOrFail($id);
        //echo "<pre>"; print_r($user); exit;
        return view('admin.view_user', compact('user'));
    }

    public function deleteUser($id)
    {
        $user = Admin::findOrFail($id);
        // mark user inactive instead of deleting
        $user->status = 0;
        $user->save();
        return redirect()->route('admin.users')->with('success', 'User marked inactive successfully.');
    }
    

    // public function blogIndex()
    // {
    //     $blogs = \App\Models\Blog::orderBy('updated_at', 'desc')->get();
    //     return view('admin.blogs', compact('blogs'));
    // }   
    // public function showAddBlogForm()
    // {
    //     return view('admin.add_blogs');
    // }
    // public function storeBlog(Request $request)
    // {
    //     if($request->isMethod('post')){
    //         //echo "<pre>";print_r($request->all());die;
        
    //         $request->validate([
    //             'title' => 'required|string|max:255',
    //             'slug' => 'required|string|max:255|unique:blogs,slug',
    //             'type' => 'required|in:1,2,3,4',
    //             'short_description' => 'required|string|max:500',
    //             'content' => 'required|string',
    //             'image' => 'nullable|image|max:2048',
    //             'status' => 'required|boolean',
    //         ]);

    //         // Save SEO
    //         $seoContent = SeoContent::addOrUpdateSeoContent([
    //             'page_slug' => $request->slug ?? null,
    //             'meta_title' => $request->title,
    //             'meta_description' => $request->meta_description ?? '',
    //             'meta_keywords' => $request->meta_keywords ?? '',
    //         ]);

    //         $blog = new \App\Models\Blog();
    //         $blog->title = $request->title;
    //         $blog->type = $request->type;
    //         $blog->slug = $request->slug ?? null;
    //         $blog->short_description = $request->short_description;
    //         $blog->content = $request->content;
    //         $blog->meta_title = $request->meta_title ?? '';
    //         $blog->meta_description = $request->meta_description ?? '';
    //         $blog->seo_id = $seoContent->id ?? null;
    //         if ($request->hasFile('image')) {
    //             $path = $request->file('image')->store('uploads/blogs', 'public');
    //             $blog->image = $path;
    //         }
    //         $blog->status = $request->status;
    //         $blog->save();
    //         return redirect()->route('admin.blog.index')->with('success', 'Blog added successfully.');
    //     }
        
    // }
    
    // public function editBlog($id)
    // {
    //     $blog = Blog::findOrFail($id);
    //     $seoContent = SeoContent::find($blog->seo_id);
    //     // echo "<pre>"; print_r($seoContent); echo "</pre>"; die;
    //     return view('admin.edit_blogs', compact('blog','seoContent'));
    // }
    // public function updateBlog(Request $request, $id)
    // {
    //     $blog = Blog::findOrFail($id);

    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
    //         'type' => 'required|in:1,2,3,4',
    //         'short_description' => 'required|string|max:500',
    //         'content' => 'required|string',
    //         'image' => 'nullable|image|max:2048',
    //         'status' => 'required|boolean',
    //     ]);

    //      //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
    //     // Update SEO
    //     $seoContent = SeoContent::addOrUpdateSeoContent([
    //         'id' => $blog->seo_id,
    //         'page_slug' => $request->slug, // Keep existing slug if not changed
    //         'meta_title' => $request->title,
    //         'meta_description' => $request->meta_description ?? '',
    //         'meta_keywords' => $request->meta_keywords ?? '',
    //     ]);
    //     $blog->title = $request->title;
    //     $blog->slug = $request->slug ?? null;
    //     $blog->type = $request->type;
    //     $blog->short_description = $request->short_description;
    //     $blog->content = $request->content;
    //     $blog->seo_id = $seoContent->id ?? null;
    //     $blog->meta_title = $request->meta_title ?? '';
    //     $blog->meta_description = $request->meta_description ?? '';
        
    //     if ($request->hasFile('image')) {
    //         if ($blog->image) {
    //             Storage::disk('public')->delete($blog->image);
    //         }
    //         $path = $request->file('image')->store('uploads/blogs', 'public');
    //         $blog->image = $path;
    //     }
    //     $blog->status = $request->status;
    //     $blog->save();
    //     return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully.');
    // }


}
