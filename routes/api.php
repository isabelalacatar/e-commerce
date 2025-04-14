<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


// Public routes (for register and login)
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes (authentication required via Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes (protected by role:admin middleware)
    Route::middleware('role:admin')->group(function () {
        // CRUD operations for products
        Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index'); // View all products
        Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store'); // Create a new product
        Route::get('/admin/products/{id}', [ProductController::class, 'show'])->name('admin.products.show'); // View a specific product
        Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update'); // Update a product
        Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy'); // Delete a product

        // Admin can view all orders
        Route::get('/admin/orders', [OrderController::class, 'allOrders'])->name('admin.orders.index');
    });

    // Customer Routes (protected by role:customer middleware)
    Route::middleware('role:customer')->group(function () {
        // Browse products (view all products)
        Route::get('/products', [ProductController::class, 'browse'])->name('products.index');

        // Create a new order
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

        // Customer can view their own orders
        Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
    });
});
