<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('users/{id}', [UserController::class, 'show']);

Route::get('categories', [CategoryController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart', [CartController::class, 'addProduct']);      // Thêm sản phẩm vào giỏ hàng
    Route::put('/cart/{product}', [CartController::class, 'updateProduct']);  // Sửa số lượng sản phẩm
    Route::delete('/cart/{product}', [CartController::class, 'removeProduct']); // Xóa sản phẩm khỏi giỏ hàng

	Route::get('/cart', [CartController::class, 'show']); // Hiển thị giỏ hàng

	Route::get('/user', function (Request $request) {
		return $request->user();
	});
});