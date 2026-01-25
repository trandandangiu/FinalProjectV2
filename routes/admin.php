<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
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
    });
});
