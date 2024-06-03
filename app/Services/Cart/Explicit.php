<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;
use Illuminate\Database\Eloquent\Builder;

class Explicit extends InteractsWithCart
{
    public function list(string $user_key): ?Order
    {
        /** @var Order|null */
        return Order::query()
            ->with(['products'])
            ->where('user_uuid', $user_key)
            ->latest()
            ->first();
    }

    public function addProductToCart(Product $product, string $user_key): void
    {
        if (empty($cart = $this->getCart($user_key))) {
            $cart = Order::query()
                ->create([
                    'status' => OrderEnum\Status::NEW,
                ]);
        }

        $cart->customer()
            ->associate($user_key);

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }

    protected function getCart(string $user_key): ?Order
    {
        /** @var Order */
        return Order::query()
            ->where('user_uuid', $user_key)
            ->where('status', OrderEnum\Status::NEW)
            ->latest()
            ->first();
    }
}
