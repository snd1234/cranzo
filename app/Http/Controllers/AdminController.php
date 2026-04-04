<?php

namespace App\Http\Controllers;

use App\Models\{Admin, Category, SubCategory, Product, Orders, OrderDetail};
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
    

    /**
     * Sub-category management functions
     * creted by: Sandeep Patel
     * created at: 2026-03-29
     */
    public function subcategoryList()
    {
        $subcategories = SubCategory::with('Category')->orderBy('id', 'desc')->get()->toArray();
        return view('admin.sub_category', compact('subcategories'));
    }

    public function addSubCategory(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'category_id' => 'required|exists:category,id',
                'name'  => 'required|string|max:255|unique:sub_category,name',
                'status' => 'required|in:0,1',
            ]);
            $subcategory = SubCategory::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'status' => $validated['status'],
                'created_by' => Auth::guard('admin')->id(),
                'created_at' => now(),
                'updated_by' => Auth::guard('admin')->id(),
                'updated_at' => now(),
            ]);
            if(!$subcategory){
                return back()->withErrors(['error' => 'Failed to add sub-category. Please try again.']);
            }
            return redirect()->route('sub-category.index')->with('success', 'Sub-category added successfully.');
        }
        $categories = Category::where('status', 1)->get();
        return view('admin.add_sub_category', compact('categories'));
    }

    public function updateSubCategory(Request $request, $id)
    {
        $id = decrypt($id);
        $subCategory = SubCategory::findOrFail($id);
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'category_id' => 'required|exists:category,id',
                'name'  => 'required|string|max:255|unique:sub_category,name,' . $subCategory->id,
                'status' => 'required|in:0,1',
            ]);
            $subCategory->category_id = $validated['category_id'];
            $subCategory->name = $validated['name'];
            $subCategory->status = $validated['status'];
            $subCategory->updated_by = Auth::guard('admin')->id();
            $subCategory->updated_at = now();
            if(!$subCategory->save()){
                return back()->withErrors(['error' => 'Failed to update sub-category. Please try again.']);
            }
            return redirect()->route('sub-category.index')->with('success', 'Sub-category updated successfully.');
        }
        $categories = Category::where('status', 1)->get();
        return view('admin.update_sub_category', compact('subCategory', 'categories'));
    }

    public function deleteSubCategory($id)
    {
        $id = decrypt($id);
        $subCategory = SubCategory::findOrFail($id);
        // mark sub-category inactive instead of deleting
        $subCategory->status = 0;
        $subCategory->updated_by = Auth::guard('admin')->id();
        $subCategory->updated_at = now();
        if(!$subCategory->save()){
            return back()->withErrors(['error' => 'Failed to delete sub-category. Please try again.']);
        }
        return redirect()->route('sub-category.index')->with('success', 'Sub-category marked inactive successfully.');
    }

    /*
        * Product management functions
        * created by: Sandeep Patel
        * created at: 2026-03-29
        */

    public function productList()
    {
        $products = Product::with('Category:id,name')->with('SubCategory:id,name')->orderBy('id', 'DESC')->get()->toArray();
        $subcategories = SubCategory::with('Category')->orderBy('id', 'desc')->get()->toArray();
        return view('admin.product', compact('products', 'subcategories'));
    
        // to be implemented
    }
    
    public function addProduct(Request $request)
    {
        
         if ($request->isMethod('post')) {
            $validated = $request->validate([
                'category_id' => 'required|exists:category,id',
                'product_title'  => 'required|string|max:255|unique:sub_category,name',
                'status' => 'required|in:0,1',
            ]);
        }
        $categories = Category::where('status', 1)->get();
        $subcategories = SubCategory::where('status', 1)->get();
        return view('admin.add_product', compact('categories', 'subcategories'));
           
    }
    public function updateProduct(Request $request, $id)
    {
        // to be implemented
    }
    public function deleteProduct($id)
    {
        // to be implemented
    }

    public function addColor(Request $request)
    {
        $request->validate([
            'color_title' => 'required|string|max:255',
            'color_code' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            //'color_code' => ['required', 'string', 'size:7'], // #xxxxxx
            'color_status' => 'required|in:1,0',
        ]);

        DB::table('colors')->insert([
            'color_title' => $request->color_title,
            'color_code' => $request->color_code,
            'color_status' => $request->color_status
        ]);

        return redirect()->route('colors')->with('success', 'Color added successfully.');
    }

    public function colors()
    {
        $colors = DB::table('colors')->get();
        return view('admin.colors', compact('colors'));
    }

    public function showAddColorForm()
    {
        return view('admin.add_color'); // adjust path if needed
    }

    public function editColor($id)
    {
        $id = decrypt($id);
        $color = DB::table('colors')->where('color_id', $id)->first();
        if (!$color) {
            return redirect('/admin/colors')->with('error', 'Color not found.');
        }
        return view('admin.edit_color', compact('color'));
    }

    public function updateColor(Request $request, $id)
    {
        $id = decrypt($id);
        $request->validate([
            'color_title' => 'required|string|max:255',
            'color_code' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'color_status' => 'required|in:1,0',
        ]);

        DB::table('colors')->where('color_id', $id)->update([
            'color_title' => $request->color_title,
            'color_code' => $request->color_code,
            'color_status' => $request->color_status
        ]);

        return redirect()->route('colors')->with('success', 'Color updated successfully.');
    }

    public function orderList()
    {
        $orders = Orders::with('user:id,first_name,middle_name,last_name,email,mobile_number')->orderBy('id', 'desc')->get()->toArray();
        // echo "<pre>"; print_r($orders); die;
        return view('admin.orders', compact('orders'));
    }

    public function viewOrder($id)
    {
        $id = decrypt($id);//echo $id;
        $orderData = Orders::with('user:id,first_name,middle_name,last_name,email,mobile_number', 'orderDetail')
                    ->findOrFail($id);
        //echo "<pre>"; print_r($orderData); die;
        return view('admin.view_order', compact('orderData'));
    }
}
