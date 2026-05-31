<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('pages.auth.login');
});

//miidleware auth grupup
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('pages.dashboard');
    })->name('home');
    Route::resource('users', \App\Http\Controllers\UserController::class);  
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class); 
});