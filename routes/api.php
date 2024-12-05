<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReviewController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('users/{id}', [UserController::class, 'show']);

Route::get('categories', [CategoryController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('/products-with-promotions', [ProductController::class, 'productsWithPromotions']);
Route::get('/products/{id}/related', [ProductController::class, 'relatedProducts']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart', [CartController::class, 'addProduct']);      // Thêm sản phẩm vào giỏ hàng
    Route::put('/cart/{product}', [CartController::class, 'updateProduct']);  // Sửa số lượng sản phẩm
    Route::delete('/cart/{product}', [CartController::class, 'removeProduct']); // Xóa sản phẩm khỏi giỏ hàng

    Route::get('/cart', [CartController::class, 'show']); // Hiển thị giỏ hàng

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/checkout', [PaymentController::class, 'checkout']);

    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);

    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);
    Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
});

Route::prefix('promotions')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::post('/', [PromotionController::class, 'store']);
    Route::get('/{id}', [PromotionController::class, 'show']);
    Route::put('/{id}', [PromotionController::class, 'update']);
    Route::delete('/{id}', [PromotionController::class, 'destroy']);
    Route::post('/{id}/attach-products', [PromotionController::class, 'attachProduct']);
    Route::post('/{id}/detach-products', [PromotionController::class, 'detachProduct']);
});
