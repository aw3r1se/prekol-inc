<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Exceptions\Cart\CantDefineUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Enum\Order as OrderEnum;

class Explicit implements InteractsWithCart
{
    /**
     * @throws CantDefineUser
     */
    public function addProductToCart(Product $product, string $user_uuid): void
    {
        $user = $this->getUser($user_uuid);

        if (empty($cart = $user->cart)) {
            $cart = new Order([
                'status' => OrderEnum\Status::NEW,
            ]);

            $cart->customer()
                ->associate($user)
                ->save();
        }

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }

    /**
     * @throws CantDefineUser
     */
    public function removeProductFromCart(Product $product, string $user_uuid): void
    {
        $user = $this->getUser($user_uuid);

        if (empty($cart = $user->cart)) {
            return;
        }

        $cart->products()
            ->detach($product);
    }

    /**
     * @throws CantDefineUser
     */
    protected function getUser(string $user_uuid): User
    {
        /** @var User $user */
        $user = User::query()
            ->with(['cart'])
            ->find($user_uuid);

        if (empty($user)) {
            throw new CantDefineUser('User is logged in, but not found');
        }

        return $user;
    }

    public function isProductInCart(Product $product, string $user_uuid): bool
    {
        /** @var User $user */
        $user = User::query()
            ->with([
               'cart',
               'cart.products',
            ])->find($user_uuid);

        if (empty($cart = $user->cart)) {
            return false;
        }

        return $cart->products
            ->contains($product);
    }
}
