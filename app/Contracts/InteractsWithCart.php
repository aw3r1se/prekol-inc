<?php

namespace App\Contracts;

use App\Models\Product;

interface InteractsWithCart
{
    public function addProductToCart(Product $product, string $user_uuid): void;

    public function removeProductFromCart(Product $product, string $user_uuid): void;

    public function isProductInCart(Product $product, string $user_uuid): bool;
}
