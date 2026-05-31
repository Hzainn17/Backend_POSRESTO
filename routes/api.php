<?php

use App\Http\Controllers\Api\CategoryhController;
use App\Http\Controllers\Api\ProducthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get(
    'api-categories',
    [CategoryhController::class, 'index']
)->middleware('auth:sanctum');

Route::get(
    'api-products',
    [ProducthController::class, 'index']
)->middleware('auth:sanctum');