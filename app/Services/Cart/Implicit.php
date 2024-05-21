<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Exceptions\Cart\CantDefineUser;
use App\Models\Product;
use App\Models\User;

class Implicit implements InteractsWithCart
{
    /**
     * @throws CantDefineUser
     */
    public function addProductToCart(Product $product, string $user_uuid): void
    {
        $user = User::query()
            ->find($user_uuid);

        if (empty($user)) {
            throw new CantDefineUser();
        }
    }

    public function isProductInCart(Product $product, string $user_uuid): bool
    {
        // TODO: Implement isProductInCart() method.
    }
}
