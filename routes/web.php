<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    'can:dashboard.access',
])->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', DashboardController::class)
            ->name('show');

        Route::prefix('/products')
            ->name('products.')
            ->group(function () {
                Route::get('/', [ProductController::class, 'index'])
                    ->name('index')
                    ->middleware('can:products.list');
                Route::get('/create', [ProductController::class, 'create'])
                    ->name('create')
                    ->middleware('can:products.create');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])
                    ->name('edit')
                    ->middleware('can:products.update');

            });

        Route::prefix('/orders')
            ->name('orders.')
            ->group(function () {
                Route::get('/', [OrderController::class, 'index'])
                    ->name('index')
                    ->middleware('can:orders.list');
            });

        Route::prefix('/users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])
                    ->name('index')
                    ->middleware('can:users.list');
            });
    });


include 'client.php';
