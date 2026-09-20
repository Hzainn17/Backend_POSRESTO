<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryhController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProducthController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes (Kasir & Admin)
Route::middleware('auth:sanctum')->group(function () {
    // Current User & Logout
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/api-categories', [CategoryhController::class, 'index']); // backward compatibility

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/api-products', [ProducthController::class, 'index']); // backward compatibility

    // Orders / Transactions (POS)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Reports / Analytics for Owner
    Route::get('/reports/dashboard', [ReportController::class, 'dashboardSummary']);
    Route::get('/reports/revenue', [ReportController::class, 'revenueChart']);
});