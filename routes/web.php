<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('pages.auth.login');
});

//middleware auth group
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::middleware(['role:admin,staff'])->group(function () {
        // Products
        Route::resource(
            'products',
            \App\Http\Controllers\ProductController::class
        );
        // Categories
        Route::resource(
            'categories',
            \App\Http\Controllers\CategoryController::class
        );
        // Orders / Transactions
        Route::resource(
            'orders',
            \App\Http\Controllers\OrderController::class
        )->only(['index', 'show']);
    });

    Route::middleware(['role:admin'])->group(function () {
        // Users
        Route::resource(
            'users',
            \App\Http\Controllers\UserController::class
        );
    });
});