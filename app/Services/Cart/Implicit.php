<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;

class Implicit extends InteractsWithCart
{
    public function addProductToCart(Product $product, string $user_uuid): void
    {
        if (empty($cart = $this->getCart($user_uuid))) {
            $cart = Order::query()
                ->create([
                    'stamp' => $user_uuid,
                    'status' => OrderEnum\Status::NEW,
                ]);
        }

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }
}
