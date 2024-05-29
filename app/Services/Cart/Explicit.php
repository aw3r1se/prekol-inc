<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;

class Explicit extends InteractsWithCart
{
    public function addProductToCart(Product $product, string $user_uuid): void
    {
        if (empty($cart = $this->getCart($user_uuid))) {
            $cart = Order::query()
                ->create([
                    'status' => OrderEnum\Status::NEW,
                ]);
        }

        $cart->customer()
            ->associate($user_uuid);

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }
}
