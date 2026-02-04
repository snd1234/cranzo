<?php

namespace App\Http\Controllers;
use App\Models\MainCategory;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function categoryIndex()
    {
       
        $categories = DB::table('product_category')->get();
        return view('admin.product_categories', compact('categories'));
    }

    public function showAddCategoryForm()
    {
        // Logic to show form to add new product category
        $mainCategory = DB::table('main_category')->get();
        return view('admin.add_product_category', compact('mainCategory'));
    }
    public function storeCategory(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|alpha_dash|max:255|unique:product_category,slug',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/category_images', 'public');
            } else {
                $imagePath = null; // or set a default image path
            }   
            DB::table('product_category')->insert([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'image_path' => $imagePath,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/admin/product-category')->with('success', 'Product category added successfully.');
       
        }
        // Logic to store new product category
    }
    public function editCategory($id)
    {
        $category = DB::table('product_category')->where('id', $id)->first();
        $mainCategory = DB::table('main_category')->get();
        if (!$category) {
            return redirect('/admin/product-category')->with('error', 'Product category not found.');
        }

        return view('admin.edit_product_category', compact('category', 'mainCategory'));
    }
    public function updateCategory(Request $request, $id)
    {
        if($request->isMethod('put')){
        
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|alpha_dash|max:255|unique:product_category,slug,'.$id,
            ]);
            $category = DB::table('product_category')->where('id', $id)->first();
            if ($request->hasFile('image')) {
                $oldImagePath = 'storage/app/public/' . $category->image_path;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }   
                $imagePath = $request->file('image')->store('uploads/category_images', 'public');
            } else {
                $imagePath = null; // or set a default image path
            }  
            DB::table('product_category')->where('id', $id)->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'image_path' => $imagePath,
                'description' => $request->description,
                'status' => $request->status,
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);

            return redirect('/admin/product-category')->with('success', 'Product category updated successfully.');
       
        }
    }

    public function subCategoryIndex()
    {
        $subCategories = ProductSubCategory::with('productCategory:id,name')->get();
        //echo "<pre>";print_r($subCategories);die;
        return view('admin.product_sub_category', compact('subCategories'));
    }

    public function showAddSubCategoryForm()
    {       
        $categories = DB::table('product_category')->get();
        return view('admin.add_product_sub_category', compact('categories'));
    }
    public function storeSubCategory(Request $request)
    {
        if($request->isMethod('post')){
           
            $request->validate([
                'category_id' => 'required|exists:product_category,id',
                'name' => 'required|string|max:255',
                // 'slug' => 'required|string|alpha_dash|max:255|unique:product_sub_category,slug',
            ]);

            DB::table('product_sub_category')->insert([
                'category_id' => $request->category_id,
                'name' => $request->name,
                //'slug' => $request->slug,
                'description' => $request->description,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/admin/product-sub-category')->with('success', 'Product sub-category added successfully.');
       
        }
        // Logic to store new product sub-category
    }
    
    public function editSubCategory($id)
    {
        $subCategory = DB::table('product_sub_category')->where('id', $id)->first();
        $categories = DB::table('product_category')->get();

        if (!$subCategory) {
            return redirect('/admin/product-sub-category')->with('error', 'Product sub-category not found.');
        }

        return view('admin.edit_product_sub_category', compact('subCategory','categories'));
    }

    public function updateSubCategory(Request $request, $id)
    {
        if($request->isMethod('put')){
            //echo "<pre>";print_r($request->all());die;
            $request->validate([
                'category_id' => 'required|exists:product_category,id',
                'name' => 'required|string|max:255',
                //'slug' => 'required|string|alpha_dash|max:255|unique:product_sub_category,slug,'.$id,
            ]);

            DB::table('product_sub_category')->where('id', $id)->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                //'slug' => $request->slug,
                'description' => $request->description,
                'status' => $request->status,
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);
            return redirect('/admin/product-sub-category')->with('success', 'Product sub-category updated successfully.');
       
        }
    }   


    public function productIndex()
    {
        $products = Products::with('productCategory:id,name')->with('productSubCategory:id,name')->where('status', 1)->orderBy('id', 'DESC')->get();
        return view('admin.product', compact('products'));
    }
    public function showAddProductForm()
    {
        // Logic to show form to add new product
        $categories = DB::table('product_category')->get();
        $subCategories = DB::table('product_sub_category')->get();
        return view('admin.add_product', compact('categories', 'subCategories'));
    }
    public function storeProduct(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|alpha_dash|max:255|unique:products,slug',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image
            ]);

            // Insert product data into the products table
            $productId = DB::table('products')->insertGetId([
                'title' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'short_description' => $request->short_description,
                'description' => $request->content,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle image uploads and store in product_image table
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/product_images', 'public'); // Store image in 'storage/app/public/product_images'
                    DB::table('product_image')->insert([
                        'product_id' => $productId,
                        'image_path' => $path,
                        'created_at' => now(),
                        'status' => 1,
                        'updated_at' => now(),
                    ]);
                }
            }

            $catalogNames = $request->catalog['catalog_names'] ?? [];
            $catalogFiles = $request->file('catalog.catalog_files') ?? [];
            if(!empty($catalogNames) && !empty($catalogFiles)){
                $this->saveProductCatalogs($catalogNames, $catalogFiles, $productId);
            }
            

            return redirect('/admin/product')->with('success', 'Product added successfully.');
        }
    }
   
    public function editProduct($id)
    {
        $categories = DB::table('product_category')->get();
        $subCategories = DB::table('product_sub_category')->get();
        $product = DB::table('products')->where('id', $id)->first();
        $product_images = DB::table('product_image')->where('product_id', $id)->get();
        $product_catalogs = DB::table('product_catalogs')->where('product_id', $id)->get();
        //echo "<pre>";print_r($product_catalogs);die;
        if (!$product) {
            return redirect('/admin/product')->with('error', 'Product not found.');
        }

        return view('admin.edit_product', compact('product', 'product_images','categories','subCategories','product_catalogs'));
    }
    public function updateProduct(Request $request, $id)
    {
        if($request->isMethod('put')){
             //echo "<pre>";print_r($request->all());die;

            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|regex:/^[a-z-]+$/|max:255|unique:products,slug,' . $id,
            ]);

            DB::table('products')->where('id', $id)->update([
                'title' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'short_description' => $request->short_description,
                'description' => $request->content,
                'status' => $request->status,
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/product_images', 'public'); // Store image in 'storage/app/public/product_images'
                    DB::table('product_image')->insert([
                        'product_id' => $id,
                        'image_path' => $path,
                        'created_at' => now(),
                        'status' => 1,
                        'updated_at' => now(),
                    ]);
                }
            }

            $catalogNames = $request->catalog['catalog_names'] ?? [];
            $catalogFiles = $request->file('catalog.catalog_files') ?? [];
            if(!empty($catalogNames) && !empty($catalogFiles)){
                $this->saveProductCatalogs($catalogNames, $catalogFiles, $id);
            }
            return redirect('/admin/product')->with('success', 'Product updated successfully.');
       
        }
    }

    public function saveProductCatalogs($catalogNames, $catalogFiles, $productId)
    {
        foreach ($catalogNames as $index => $name) {
            if (isset($catalogFiles[$index])) {
                $path = $catalogFiles[$index]->store('uploads/product_catalogs', 'public');

                DB::table('product_catalogs')->insert([
                    'product_id' => $productId,
                    'catalog_title' => $name,
                    'catalog_file' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function deleteCatalog(Request $request)
    {
        $catalogId = $request->input('catalogId');
        if (!$catalogId) {
            return response()->json(['status' => 'error', 'message' => 'Invalid catalog ID.'], 400);
        }
        $catalog = DB::table('product_catalogs')->where('id', $catalogId)->first();

        if (!$catalog) {
            return response()->json(['status' => 'error', 'message' => 'Catalog not found.'], 404);
        }
        
        DB::table('product_catalogs')->where('id', $catalogId)->delete();
        $filePath = storage_path('app/public/' . $catalog->catalog_file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return response()->json(['status' => 'success', 'message' => 'Catalog deleted successfully.']);
    }

    public function deleteImage(Request $request)
    {
        $imageId = $request->input('imageId');
        if (!$imageId) {
            return response()->json(['status' => 'error', 'message' => 'Invalid image ID.'], 400);
        }
        $image = DB::table('product_image')->where('id', $imageId)->first();

        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Image not found.'], 404);
        }
        
        DB::table('product_image')->where('id', $imageId)->delete();
        $filePath = storage_path('app/public/' . $image->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return response()->json(['status' => 'success', 'message' => 'Image deleted successfully.']);
    }


    public function viewProduct($id)
    {
        $product = DB::table('product')->where('id', $id)->first();

        if (!$product) {
            return redirect('/admin/product')->with('error', 'Product not found.');
        }

        return view('admin.view_product', compact('product'));
    }

    


}


