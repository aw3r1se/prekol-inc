<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

Route::middleware(['api'])
    ->name('api.')
    ->group(function () {
        Route::prefix('media')
            ->name('media.')
            ->group(function () {
                Route::delete('/{media}/remove', [API\MediaController::class, 'remove'])
                    ->name('remove');
            });

        Route::prefix('products')
            ->name('products.')
            ->group(function () {
                Route::get('/', [API\ProductController::class, 'search'])
                    ->name('search');
                Route::get('/{product}', [API\ProductController::class, 'show'])
                    ->name('show');
                Route::post('/', [API\ProductController::class, 'store'])
                    ->name('store');
                Route::put('/{product}', [API\ProductController::class, 'update'])
                    ->name('update');
                Route::delete('/{product}', [API\ProductController::class, 'destroy'])
                    ->name('destroy');
                Route::patch('/{product}/restore', [API\ProductController::class, 'restore'])
                    ->name('restore');
                Route::post('/{product}/upload', [API\ProductController::class, 'upload'])
                    ->name('upload');
                Route::post('/{product}/price', [API\ProductController::class, 'addPrice'])
                    ->name('add-price');

                Route::post('/{product}/add-to-cart', [API\ProductController::class, 'addToCart'])
                    ->name('add-to-cart');
                Route::post('/{product}/remove-from-cart', [API\ProductController::class, 'removeFromCart'])
                    ->name('remove-from-cart');
            });

        Route::prefix('currencies')
            ->name('currencies.')
            ->group(function () {
                Route::get('/', [API\CurrencyController::class, 'index'])
                    ->name('index');
            });

        Route::prefix('orders')
            ->name('orders.')
            ->group(function () {
               Route::get('/', [API\OrderController::class, 'search'])
                   ->name('search');
            });

        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [API\UserController::class, 'search'])
                    ->name('search');
            });

        Route::prefix('cart')
            ->name('cart.')
            ->group(function () {
                Route::get('/', [API\CartController::class, 'list'])
                    ->name('list');
                Route::post('/{product}/add', [API\CartController::class, 'add'])
                    ->name('add');
                Route::post('/{product}/sub', [API\CartController::class, 'sub'])
                    ->name('sub');
            });
    });
