<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

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
    



});
