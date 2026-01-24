<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AjaxController;

Route::get('/', function () {
    return app(\App\Http\Controllers\HomeController::class)->index();
});
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('aboutUs');
Route::get('/our-partners', [HomeController::class, 'ourPartners'])->name('ourPartners');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/news-room', [HomeController::class, 'newsRoom'])->name('newsRoom');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contactUs');

// Route::get('/products', [HomeController::class, 'products'])->name('products');
// Route::get('/product-detail', [HomeController::class, 'productDetail'])->name('productDetail');
Route::get('/our-solution-detail', [HomeController::class, 'ourSolutionDetail'])->name('ourSolutionDetail');
Route::get('/common-detail-page', [HomeController::class, 'commonDetailPage'])->name('commonDetailPage');

Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');

Route::post('/ajax/subscribe-newsletter', [AjaxController::class, 'subscribeNewsletter'])->name('ajax.subscribeNewsletter');

Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('productDetail');

// frontend authentication routes
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
// Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
// Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/home', [AuthController::class, 'home'])->name('home')->middleware('auth');

// admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('auth:admin');
    Route::get('/users', [AdminController::class, 'userList'])->name('users')->middleware('auth:admin');   
    Route::post('/users', [AdminController::class, 'addUser'])->name('users.add')->middleware('auth:admin');
    Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('edituser.form')->middleware('auth:admin');
    Route::get('/add-user', [AdminController::class, 'showAddUserForm'])->name('adduser.form')->middleware('auth:admin');
    Route::post('/add-user', [AdminController::class, 'addUser'])->name('adduser.submit')->middleware('auth:admin');
    Route::put('/update-user/{id}', [AdminController::class, 'updateUser'])->name('updateuser.submit')->middleware('auth:admin');
    Route::get('/view-user/{id}', [AdminController::class, 'viewUser'])->name('viewuser.form')->middleware('auth:admin');
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('deleteuser.submit')->middleware('auth:admin');
  
    // Page management routes
    Route::get('/page-management', [PageController::class, 'index'])->name('pagemgmt.index')->middleware('auth:admin');
    Route::get('/add-page', [PageController::class, 'showAddForm'])->name('pagemgmt.showAddForm')->middleware('auth:admin');
    Route::post('/save-page-data', [PageController::class, 'store'])->name('pagemgmt.store')->middleware('auth:admin');
    Route::get('/edit-page/{id}', [PageController::class, 'edit'])->name('pagemgmt.edit')->middleware('auth:admin');
    Route::put('/page-management/{id}', [PageController::class, 'update'])->name('pagemgmt.update')->middleware('auth:admin');
    Route::get('/page-management/{id}', [PageController::class, 'show'])->name('pagemgmt.show')->middleware('auth:admin');
    Route::delete('/page-management/{id}', [PageController::class, 'destroy'])->name('pagemgmt.destroy')->middleware('auth:admin');

   // Route for product category and subcategory management
    
    Route::get('/product-category', [ProductController::class, 'categoryIndex'])->name('productcategory.index')
    ->middleware('auth:admin');
    Route::get('/add-product-category', [ProductController::class, 'showAddCategoryForm'])->name('productcategory.showAddForm')->middleware('auth:admin');
    Route::post('/save-product-category', [ProductController::class, 'storeCategory'])->name('productcategory.store')->middleware('auth:admin');
    Route::get('/edit-product-category/{id}', [ProductController::class, 'editCategory'])->name('productcategory.edit')->middleware('auth:admin');
    Route::put('/update-product-category/{id}', [ProductController::class, 'updateCategory'])->name('productcategory.update')->middleware('auth:admin');
   

    Route::get('/product-sub-category', [ProductController::class, 'subCategoryIndex'])->name('productsubcategory.index')
    ->middleware('auth:admin');
    Route::get('/add-product-sub-category', [ProductController::class, 'showAddSubCategoryForm'])->name('productsubcategory.showAddForm')->middleware('auth:admin');
    Route::post('/save-product-sub-category', [ProductController::class, 'storeSubCategory'])->name('productsubcategory.store')->middleware('auth:admin');
    Route::get('/edit-product-sub-category/{id}', [ProductController::class, 'editSubCategory'])->name('productsubcategory.edit')->middleware('auth:admin');
    Route::put('/update-product-sub-category/{id}', [ProductController::class, 'updateSubCategory'])->name('productsubcategory.update')->middleware('auth:admin');
   
    Route::get('/product', [ProductController::class, 'productIndex'])->name('product.index')
    ->middleware('auth:admin');
    Route::get('/add-product', [ProductController::class, 'showAddProductForm'])->name('product.showAddForm')->middleware('auth:admin');
    Route::post('/save-product', [ProductController::class, 'storeProduct'])->name('product.store')->middleware('auth:admin');
    Route::get('/edit-product/{id}', [ProductController::class, 'editProduct'])->name('product.edit')->middleware('auth:admin');
    Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->name('product.update')->middleware('auth:admin');
    Route::get('/view-product/{id}', [ProductController::class, 'viewProduct'])->name('product.view')->middleware('auth:admin');

    Route::get('/blogs', [AdminController::class, 'blogIndex'])->name('blog.index')
    ->middleware('auth:admin');
    Route::get('/add-blog', [AdminController::class, 'showAddBlogForm'])->name('blog.showAddForm')->middleware('auth:admin');
    Route::post('/save-blog', [AdminController::class, 'storeBlog'])->name('blog.store')->middleware('auth:admin');
    Route::get('/edit-blog/{id}', [AdminController::class, 'editBlog'])->name('blog.edit')->middleware('auth:admin');
    Route::put('/update-blog/{id}', [AdminController::class, 'updateBlog'])->name('blog.update')->middleware('auth:admin');

});
