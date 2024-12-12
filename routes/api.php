<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('categories/parent', [CategoryController::class, 'parentCategories']);
    Route::get('categories/all', [CategoryController::class, 'allCategories']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
});
