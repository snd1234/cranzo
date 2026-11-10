<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsletterController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('/our-partners', [HomeController::class, 'ourPartners'])->name('our-partners');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/news-room', [HomeController::class, 'newsRoom'])->name('news-room');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/product-detail/{slug}', [HomeController::class, 'productDetail'])->name('product-detail');
Route::get('/our-solutions/{slug}', [HomeController::class, 'ourSolutionDetail'])->name('our-solutions');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');
Route::get('/news/{slug}', [HomeController::class, 'blogDetail'])->name('news.detail');
Route::post('/contact-us', [HomeController::class, 'submitContact'])->middleware('throttle:5,1')->name('contact.submit');
Route::get('/faq', [HomeController::class, 'page'])->defaults('slug', 'faq')->name('faq');
Route::get('/privacy-policy', [HomeController::class, 'page'])->defaults('slug', 'privacy-policy')->name('privacy-policy');
Route::get('/terms-conditions', [HomeController::class, 'page'])->defaults('slug', 'terms-conditions')->name('terms-conditions');
Route::get('/returns-exchange', [HomeController::class, 'page'])->defaults('slug', 'returns-exchange')->name('returns-exchange');
Route::get('/terms-of-use', [HomeController::class, 'page'])->defaults('slug', 'terms-of-use')->name('terms-of-use');
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('cms.page');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->middleware('throttle:5,1')->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'confirmUnsubscribe'])->middleware('throttle:10,1')->name('newsletter.unsubscribe.confirm');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'place'])->name('checkout.place');
Route::get('/order/{orderNumber}', [CartController::class, 'complete'])->name('checkout.complete');

// frontend authentication routes
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
// Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
// Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/home', [AuthController::class, 'home'])->name('home')->middleware('auth');

// admin routes
Route::prefix('system-auth')->group(function () {
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
  
    Route::get('/colors', [AdminController::class, 'colors'])->name('colors')->middleware('auth:admin');
    Route::get('/add-color', [AdminController::class, 'showAddColorForm'])->name('addColor.form')->middleware('auth:admin');
    Route::post('/add-color', [AdminController::class, 'addColor'])->name('addColor.submit')->middleware('auth:admin');
    Route::get('/edit-color/{id}', [AdminController::class, 'editColor'])->name('editColor.form')->middleware('auth:admin');
    Route::put('/update-color/{id}', [AdminController::class, 'updateColor'])->name('updateColor.submit')->middleware('auth:admin');
   
    // Category routes
    Route::get('/category', [AdminController::class, 'categoryList'])->name('category.index')
    ->middleware('auth:admin');
    Route::match(['get', 'post'], '/add-category', [AdminController::class, 'addCategory'])->name('category.store')->middleware('auth:admin');
    Route::match(['get', 'put'], '/update-category/{id}', [AdminController::class, 'updateCategory'])->name('category.update')->middleware('auth:admin');
    Route::match(['get', 'delete'], '/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('category.delete')->middleware('auth:admin');

    // SubCategory routes
    Route::get('/sub-category', [AdminController::class, 'subcategoryList'])->name('sub-category.index')->middleware('auth:admin');
    Route::match(['get', 'post'], '/add-sub-category', [AdminController::class, 'addSubCategory'])->name('sub-category.store')->middleware('auth:admin');
    Route::match(['get', 'put'], '/update-sub-category/{id}', [AdminController::class, 'updateSubCategory'])->name('sub-category.update')->middleware('auth:admin');
    Route::match(['get', 'delete'], '/delete-sub-category/{id}', [AdminController::class, 'deleteSubCategory'])->name('sub-category.delete')->middleware('auth:admin');

    // Product routes
    
    Route::get('/products', [AdminController::class, 'productList'])->name('product.index')->middleware('auth:admin');
    Route::match(['get', 'post'], '/add-product', [AdminController::class, 'addProduct'])->name('product.store')->middleware('auth:admin');
    Route::get('/view-product/{id}', [AdminController::class, 'viewProduct'])->name('product.view')->middleware('auth:admin');
    Route::match(['get', 'put'], '/update-product/{id}', [AdminController::class, 'updateProduct'])->name('product.update')->middleware('auth:admin');
    Route::match(['get', 'delete'], '/delete-product/{id}', [AdminController::class, 'deleteProduct'])->name('product.delete')->middleware('auth:admin');

    // Order routes
    Route::get('/orders', [AdminController::class, 'orderList'])->name('order.index')->middleware('auth:admin');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/add-banner', [BannerController::class, 'create'])->name('banners.create');
        Route::post('/add-banner', [BannerController::class, 'store'])->name('banners.store');
        Route::get('/view-banner/{banner}', [BannerController::class, 'show'])->name('banners.show');
        Route::get('/update-banner/{banner}', [BannerController::class, 'edit'])->name('banners.edit');
        Route::put('/update-banner/{banner}', [BannerController::class, 'update'])->name('banners.update');
        Route::post('/banners/{banner}/status', [BannerController::class, 'status'])->name('banners.status');
        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');

        Route::get('/cms-pages', [CmsPageController::class, 'index'])->name('cms.index');
        Route::get('/cms-pages/create', [CmsPageController::class, 'create'])->name('cms.create');
        Route::post('/cms-pages', [CmsPageController::class, 'store'])->name('cms.store');
        Route::get('/cms-pages/{page}', [CmsPageController::class, 'show'])->name('cms.show');
        Route::get('/cms-pages/{page}/edit', [CmsPageController::class, 'edit'])->name('cms.edit');
        Route::put('/cms-pages/{page}', [CmsPageController::class, 'update'])->name('cms.update');
        Route::post('/cms-pages/{page}/status', [CmsPageController::class, 'status'])->name('cms.status');
        Route::delete('/cms-pages/{page}', [CmsPageController::class, 'destroy'])->name('cms.destroy');

        Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::post('/faqs/{faq}/status', [FaqController::class, 'status'])->name('faqs.status');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

        Route::get('/newsletter-subscribers', [NewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('/newsletter-subscribers/export', [NewsletterController::class, 'export'])->name('newsletter.export');
        Route::get('/newsletter-subscribers/{subscriber}', [NewsletterController::class, 'show'])->name('newsletter.show');
        Route::post('/newsletter-subscribers/{subscriber}/status', [NewsletterController::class, 'status'])->name('newsletter.status');
        Route::get('/newsletter-campaigns', [NewsletterController::class, 'campaigns'])->name('newsletter.campaigns');
        Route::post('/newsletter-campaigns', [NewsletterController::class, 'storeCampaign'])->name('newsletter.campaigns.store');
        Route::post('/newsletter-campaigns/{campaign}/queue', [NewsletterController::class, 'queueCampaign'])->name('newsletter.campaigns.queue');
    });
    Route::get('/view-order/{id}', [AdminController::class, 'viewOrder'])->name('order.view')->middleware('auth:admin');

});
