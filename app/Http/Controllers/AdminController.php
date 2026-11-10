<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\{Admin, Category, SubCategory, Product, Orders, OrderDetail, Addresss};
use App\Services\ProductCatalog;
use App\Support\CataloguePath;
use App\Support\Slugger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $request->session()->regenerate();
        $request->session()->regenerateToken();

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
            $this->prepareSlug($request);
            $validated = $request->validate(array_merge([
                'name'  => 'required|string|max:255|unique:category,name',
                'status' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], $this->seoRules('category')));
            $category = Category::create([
                'name' => $validated['name'],
                'image' => $request->hasFile('image') ? $this->storeUploadedImage($request->file('image'), 'categories') : null,
                'status' => $validated['status'],
                ...$this->seoValues('category', $validated['name']),
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
            $this->prepareSlug($request);
            $validated = $request->validate(array_merge([
                'name'  => 'required|string|max:255|unique:category,name,' . $category->id,
                'status' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], $this->seoRules('category', $category->id)));
            $category->name = $validated['name'];
            $category->status = $validated['status'];
            $category->fill($this->seoValues('category', $validated['name'], $category->id));
            if ($request->hasFile('image')) {
                $filename = $this->storeUploadedImage($request->file('image'), 'categories');
                $this->deleteUploadedImage($category->image, 'categories');
                $category->image = $filename;
            }
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
            $this->prepareSlug($request);
            $validated = $request->validate(array_merge([
                'category_id' => 'required|exists:category,id',
                'name'  => 'required|string|max:255|unique:sub_category,name',
                'status' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], $this->seoRules('sub_category')));
            $subcategory = SubCategory::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'image' => $request->hasFile('image') ? $this->storeUploadedImage($request->file('image'), 'subcategories') : null,
                'status' => $validated['status'],
                ...$this->seoValues('sub_category', $validated['name']),
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
            $this->prepareSlug($request);
            $validated = $request->validate(array_merge([
                'category_id' => 'required|exists:category,id',
                'name'  => 'required|string|max:255|unique:sub_category,name,' . $subCategory->id,
                'status' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], $this->seoRules('sub_category', $subCategory->id)));
            $subCategory->category_id = $validated['category_id'];
            $subCategory->name = $validated['name'];
            $subCategory->status = $validated['status'];
            $subCategory->fill($this->seoValues('sub_category', $validated['name'], $subCategory->id));
            if ($request->hasFile('image')) {
                $filename = $this->storeUploadedImage($request->file('image'), 'subcategories');
                $this->deleteUploadedImage($subCategory->image, 'subcategories');
                $subCategory->image = $filename;
            }
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
    
    public function addProduct(Request $request, ProductCatalog $catalog)
    {
        if ($request->isMethod('post')) {
            $validated = $this->validateProduct($request);
            $productId = $catalog->create($validated, $request->file('images', []) ?? [], (int) Auth::guard('admin')->id());

            return redirect()->route('product.view', $productId)->with('success', 'Product added.');
        }

        return view('admin.add_product', $this->productFormData());
    }

    public function viewProduct($id)
    {
        $product = Product::findOrFail($id);
        $data = $this->productFormData($product);
        $data['publicUrl'] = $product->status ? CataloguePath::product($product) : null;

        return view('admin.view_product', $data);
    }

    public function updateProduct(Request $request, $id, ProductCatalog $catalog)
    {
        $product = Product::findOrFail($id);
        if ($request->isMethod('put')) {
            $validated = $this->validateProduct($request);
            $catalog->update(
                $product->id,
                $validated,
                $request->file('images', []) ?? [],
                array_map('intval', $request->input('remove_images', [])),
                (int) Auth::guard('admin')->id()
            );

            return redirect()->route('product.view', $product->id)->with('success', 'Product updated.');
        }

        return view('admin.edit_product', $this->productFormData($product));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 0;
        $product->updated_at = now();
        $product->update_by = Auth::guard('admin')->id();
        $product->save();

        return redirect()->route('product.index')->with('success', 'Product marked inactive. Its images and stock were kept.');
    }

    private function validateProduct(Request $request): array
    {
        $this->prepareSlug($request);
        $form = ProductRequest::createFrom($request);
        $form->setContainer(app())->setRedirector(app('redirect'));
        $form->validateResolved();

        return $form->validated();
    }

    private function productFormData(?Product $product = null): array
    {
        return [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => DB::table('sub_category')->orderBy('name')->get(['id', 'category_id', 'name']),
            'ternaryCategories' => DB::table('ternary_category')->orderBy('ternary_title')->get(['ternary_id', 'ternary_title', 'sub_category_id', 'category_id']),
            'colors' => DB::table('colors')->where('color_status', 1)->orderBy('color_title')->get(),
            'details' => $product
                ? DB::table('product_details')
                    ->leftJoin('colors', 'colors.color_id', '=', 'product_details.color_id')
                    ->where('product_details.product_id', $product->id)
                    ->orderBy('product_details.product_details_id')
                    ->get(['product_details.product_details_id', 'product_details.color_id', 'product_details.product_quantity', 'colors.color_title'])
                : collect(),
            'images' => $product
                ? DB::table('product_images')->where('product_id', $product->id)->orderBy('image_id')->get()
                : collect(),
        ];
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
        $orderData = Orders::with('user:id,first_name,middle_name,last_name,email,mobile_number', 'orderDetail', 'Address.state')
                ->findOrFail($id);
        //echo "<pre>"; print_r($orderData); die;
        return view('admin.view_order', compact('orderData'));
    }

    private function prepareSlug(Request $request): void
    {
        if (! $request->exists('slug')) {
            return;
        }

        $slug = Str::slug((string) $request->input('slug'));
        $request->merge(['slug' => $slug !== '' ? $slug : null]);
    }

    private function seoRules(string $table, ?int $ignoreId = null): array
    {
        $unique = 'unique:'.$table.',slug';
        if ($ignoreId) {
            $unique .= ','.$ignoreId;
        }

        return [
            'slug' => ['nullable', 'string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $unique],
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    private function seoValues(string $table, string $source, ?int $ignoreId = null, ?array $validated = null): array
    {
        $validated ??= request()->all();

        return [
            'slug' => ! empty($validated['slug']) ? $validated['slug'] : Slugger::unique($table, $source, $ignoreId),
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ];
    }

    private function storeUploadedImage($file, string $folder): string
    {
        $extension = strtolower($file->extension() ?: 'jpg');
        $filename = $folder.'-'.time().'-'.bin2hex(random_bytes(4)).'.'.$extension;
        $directory = public_path('uploads/'.$folder);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $file->move($directory, $filename);

        return $filename;
    }

    private function deleteUploadedImage(?string $stored, string $folder): void
    {
        if (! $stored || str_contains($stored, '://')) {
            return;
        }

        $path = public_path('uploads/'.$folder.'/'.basename($stored));
        if (is_file($path)) {
            unlink($path);
        }
    }
}
