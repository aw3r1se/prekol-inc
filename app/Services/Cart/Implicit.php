<?php

namespace App\Services\Cart;

use App\Contracts\InteractsWithCart;
use App\Enum\Order as OrderEnum;
use App\Models\Order;
use App\Models\Product;

class Implicit implements InteractsWithCart
{
    public function addProductToCart(Product $product, string $user_uuid): void
    {
        $order = Order::query()
            ->where('user_uuid', $user_uuid)
            ->latest()
            ->first();

        if (empty($order)) {
            $order = Order::query()->create([
                'user_uuid' => $user_uuid,
                'status' => OrderEnum\Status::NEW,
            ]);
        }
        /** @var Order $order */
        $order->products()
            ->attach($product);
    }

    public function removeProductFromCart(Product $product, string $user_uuid): void
    {
        /** @var Order|null $order */
        $order = Order::query()
            ->where('user_uuid', $user_uuid)
            ->latest()
            ->first();

        if (empty($order)) {
            return;
        }

        $order->products()
            ->detach($product);
    }

    public function isProductInCart(Product $product, string $user_uuid): bool
    {
        /** @var Order|null $cart */
        $cart = Order::query()
            ->where('user_uuid', $user_uuid)
            ->latest()
            ->first();

        if (empty($cart)) {
            return false;
        }

        return $cart->products
            ->contains($product);
    }
}
