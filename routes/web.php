<?php

use App\Http\Controllers\Client\Cart;
use App\Http\Controllers\Client\MainPage;
use App\Http\Controllers\Client\Product;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

/**
 * CLIENT INTERFACE
 * @TODO SPLIT IN OTHER ROUTE FILE
 */

Route::middleware([

])->name('client.')
    ->group(function () {
        Route::get('/', MainPage::class)
            ->name('main');
        Route::get('cart', Cart::class)
            ->name('cart');
        Route::prefix('/products')
            ->name('products.')
            ->group(function () {
                Route::get('/{product}', Product::class)
                    ->name('detail');
            });
    });

//Route::get('/', function () {
////    return Inertia::render('Welcome', [
////        'canLogin' => Route::has('login'),
////        'canRegister' => Route::has('register'),
////        'laravelVersion' => Application::VERSION,
////        'phpVersion' => PHP_VERSION,
////    ]);
//});

Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
])->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', DashboardController::class)
            ->name('show');

        Route::prefix('/products')
            ->name('products.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            });

        Route::prefix('/orders')
            ->name('orders.')
            ->group(function () {
                Route::get('/', [OrderController::class, 'index'])->name('index');
            });

        Route::prefix('/users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
            });
    });
