<?php

use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Middleware\DefaultAdminData;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');



    Route::middleware('check.auth.admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    });

    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


    Route::middleware(['auth.custom', DefaultAdminData::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/account', [AccountController::class, 'index'])->name('admin.account');
        Route::post('/profile/update', [AccountController::class, 'updateProfile']);
        Route::get('/notifications', [NotificationsController::class, 'index'])->name('admin.notifications.index');
        Route::post('/notifications/update', [NotificationsController::class, 'update']);

        Route::middleware(['permission:manager_users'])->group(function () {
            Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
            Route::post('/user/upgrade', [UsersController::class, 'upgrade']);
            Route::post('/user/updateStatus', [UsersController::class, 'updateStatus']);
        });

        Route::middleware(['permission:manager_categories'])->group(function () {
            Route::get('/categories/add', [CategoriesController::class, 'showFormAddCategories'])->name('admin.categories.add');
            Route::post('/categories/add', [CategoriesController::class, 'addCategory'])->name('admin.categories.add');

            Route::get('/categories', [CategoriesController::class, 'index'])->name('admin.categories.index');
            Route::post('/categories/update', [CategoriesController::class, 'updateCategory'])->name('admin.categories.update');
            Route::post('/categories/delete', [CategoriesController::class, 'deleteCategory']);
        });

        Route::middleware(['permission:manager_products'])->group(function () {
            Route::get('/products/add', [ProductController::class, 'showFormAddProduct'])->name('admin.products.add');
            Route::post('/products/add', [ProductController::class, 'addProduct'])->name('admin.products.store');
            Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
            Route::post('/products/update', [ProductController::class, 'updateProduct'])->name('admin.products.update');
            Route::post('/products/delete', [ProductController::class, 'deleteProduct']);
        });


        Route::middleware(['permission:manager_orders'])->group(function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
            Route::post('/orders/confirm', [OrderController::class, 'confirmOrder'])->name('admin.orders.confirm');
            Route::get('/orders/detail/{id}', [OrderController::class, 'showOrderDetail'])->name('admin.orders-detail');
            Route::post('/orders/send-invoice-mail', [OrderController::class, 'sendInvoiceMail']);
            Route::post('/orders/cancel', [OrderController::class, 'cancelOrder']);
        });

        Route::middleware(['permission:manager_contact'])->group(function () {
            Route::get('/contact', [ContactController::class, 'index'])->name('admin.contact.index');
            Route::post('/contact/reply', [ContactController::class, 'replyContact']);
        });
    });
});
