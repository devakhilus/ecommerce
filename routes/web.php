<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [HomeController::class, 'welcome']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



Route::middleware('auth')->group(function () {
    // Common dashboard route for all users (admin/user)
    Route::get('/dashboard', function () {
        return auth()->user()->is_admin
            ? view('admin.dashboard')
            : view('user.dashboard');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });


    // Category management accessible to ALL logged-in users
    Route::prefix('admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        // Add other admin routes here if needed
        Route::resource('products', ProductController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->names('admin.orders');
    });
    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'stats']);
    });
});
