<?php

use App\Http\Controllers\Client\Cart;
use App\Http\Controllers\Client\MainPage;
use App\Http\Controllers\Client\Error;
use App\Http\Controllers\Client\Product;
use Illuminate\Support\Facades\Route;

Route::name('client.')
    ->group(function () {
        Route::get('/', MainPage::class)
            ->name('main');
        Route::get('/something-went-wrong/{code}', Error::class)
            ->name('error');
        Route::get('cart', Cart::class)
            ->name('cart');
        Route::prefix('/products')
            ->name('products.')
            ->group(function () {
                Route::get('/{product}', Product::class)
                    ->name('detail');
            });
    });
