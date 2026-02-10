<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UsersController;


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');



    Route::middleware('check.auth.admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    });

    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


    Route::middleware('auth.custom')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('admin.dashboard');
    });

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
        Route::get('/products/add', [ProductController::class, 'showFormAddProduct'])->name('admin.product.add');
        Route::post('/products/add', [ProductController::class, 'addProduct'])->name('admin.products.add');

        Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
        Route::post('/products/update', [ProductController::class, 'updateProduct'])->name('admin.products.update');
        Route::post('/products/delete', [ProductController::class, 'deleteProduct']);
    });
    
});
