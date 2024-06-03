<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;;

class Implicit extends InteractsWithCart
{
    public function list(string $user_key): ?Order
    {
        /** @var Order|null */
        return Order::query()
            ->with(['products'])
            ->where('session_id', $user_key)
            ->latest()
            ->first();
    }

    public function addProductToCart(Product $product, string $user_key): void
    {
        if (empty($cart = $this->getCart($user_key))) {
            $cart = Order::query()
                ->create([
                    'session_id' => $user_key,
                    'status' => OrderEnum\Status::NEW,
                ]);
        }

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }

    protected function getCart(string $user_key): ?Order
    {
        /** @var Order */
        return Order::query()
            ->where('session_id', $user_key)
            ->where('status', OrderEnum\Status::NEW)
            ->latest()
            ->first();
    }
}
