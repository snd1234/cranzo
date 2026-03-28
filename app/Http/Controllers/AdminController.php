<?php

namespace App\Http\Controllers;

use App\Models\{Admin, Category, SubCategory};
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


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
    

    public function categoryList()
    {
        $categories = Category::orderBy('id', 'desc')->get()->toArray();
        return view('admin.category', compact('categories'));
    }

    public function addCategory(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name'  => 'required|string|max:255|unique:category,name',
                'status' => 'required|in:0,1',
            ]);
            $category = Category::create([
                'name' => $validated['name'],
                'status' => $validated['status'],
                'created_by' => Auth::guard('admin')->id(),
                'created_at' => now(),
                'updated_by' => Auth::guard('admin')->id(),
                'updated_at' => now(),
            ]);
            if(!$category){
                return back()->withErrors(['error' => 'Failed to add category. Please try again.']);
            }
            return redirect()->route('category.index')->with('success', 'Category added successfully.');
        }
        return view('admin.add_category');
    }

    function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'name'  => 'required|string|max:255|unique:category,name,' . $category->id,
                'status' => 'required|in:0,1',
            ]);
            $category->name = $validated['name'];
            $category->status = $validated['status'];
            $category->updated_by = Auth::guard('admin')->id();
            $category->updated_at = now();
            if(!$category->save()){
                return back()->withErrors(['error' => 'Failed to update category. Please try again.']);
            }
            return redirect()->route('category.index')->with('success', 'Category updated successfully.');
        }
        return view('admin.update_category', compact('category'));
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        // mark category inactive instead of deleting
        $category->status = 0;
        $category->updated_by = Auth::guard('admin')->id();
        $category->updated_at = now();
        if(!$category->save()){
            return back()->withErrors(['error' => 'Failed to delete category. Please try again.']);
        }
        return redirect()->route('category.index')->with('success', 'Category marked inactive successfully.');
    }
    


}
