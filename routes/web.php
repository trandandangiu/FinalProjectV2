<?php

use App\Http\Controllers\Clients\CheckoutController;
use App\Http\Controllers\Clients\CartController;
use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\OrderController;
use App\Http\Controllers\Clients\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ProductController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('clients.pages.about');
})->name('about');

Route::get('/service', function () {
    return view('clients.pages.services');
})->name('service');

Route::get('/team', function () {
    return view('clients.pages.team');
})->name('team');

Route::get('/faq', function () {
    return view('clients.pages.faq');
})->name('faq');

//guest

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('post-register');

    Route::get('/login', [AuthController::class, 'showloginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('post_login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

Route::middleware(['auth.custom'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');
        Route::put('/update', [AccountController::class, 'update'])->name('account.update');

        Route::post('/change-password', [AccountController::class, 'ChangePassword'])->name('account.change-password');

        Route::post('/addresses', [AccountController::class, 'addAddress'])->name('account.addresses.add');

        Route::put('/addresses/{id}', [AccountController::class, 'updatePrimaryAddress'])->name('account.addresses.update');
        Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('account.addresses.delete');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/get-address', [CheckoutController::class, 'getAddress'])->name('checkout.address');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

        Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
        Route::post('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');

    
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
// detail product
Route::get('/product/{slug}', [ProductController::class, 'detail'])->name('products.detail');

//handler cart
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromMiniCart'])->name('cart.remove');
Route::get('/mini-cart', [CartController::class, 'loadMiniCart'])->name('cart.mini');


//handler page cart 
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove-cart', [CartController::class, 'removeCartItem'])->name('cart.remove-cart');


////////////// Admin routes
require __DIR__ . '/admin.php';
