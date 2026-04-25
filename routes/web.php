<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', function() { return view('about'); });
Route::get('/contact', function() { return view('contact'); });
Route::post('/contact', function() {
    return back()->with('success', 'Yêu cầu đã được gửi thành công!');
});
Route::get('/thucdon', [MenuController::class, 'index']);
Route::get('/halal', function() { return view('halal'); });
Route::get('/details/{id}', [ProductController::class, 'show']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'process']);
Route::get('/checkout/vnpay_return', [CheckoutController::class, 'vnpayReturn']);
Route::get('/history', [OrderController::class, 'history']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Middleware\AdminMiddleware;

// Admin Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('category', CategoryController::class);
    Route::resource('product', AdminProductController::class);
    Route::resource('order', AdminOrderController::class)->only(['index', 'edit', 'update']);
    Route::resource('user', UserController::class)->only(['index', 'destroy']);
});
