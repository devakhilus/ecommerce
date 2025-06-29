<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\OrderController as FrontOrderController;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\Mail;

// Public routes
Route::get('/', [HomeController::class, 'welcome']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PolicyController::class, 'terms'])->name('terms');
Route::get('/cancellation-and-refund', [PolicyController::class, 'refund'])->name('refund');
Route::get('/shipping-and-delivery', [PolicyController::class, 'shipping'])->name('shipping');


// 📨 Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // ✅ marks email as verified
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// 🛡️ Protected routes (logged-in + verified users or admin)
Route::middleware(['auth', 'verified_or_admin'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 🧑 User dashboard (non-admins)
    Route::get('/dashboard', function () {
        return auth()->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : view('user.dashboard');
    })->name('dashboard');

    // 🛒 Orders
    Route::get('/checkout', [FrontOrderController::class, 'checkout']);
    Route::post('/orders', [FrontOrderController::class, 'store'])->name('orders.store');

    // 🛠️ Admin routes (for admins only)
    Route::middleware('can:isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
    });
});
Route::view('/contact', 'contact')->name('contact.show');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'message' => 'required|string'
    ]);

    // You can later replace this with Mail::to() for actual emailing
    // For now, just simulate successful submission
    return back()->with('success', '✅ Your message has been sent successfully!');
})->name('contact.submit');
