<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;

abstract class InteractsWithCart
{
    public function list(string $user_uuid): ?Order
    {
        /** @var Order|null */
        return Order::query()
            ->with(['products'])
            ->where('user_uuid', $user_uuid)
            ->latest()
            ->first();
    }

    public function addProductToCart(Product $product, string $user_uuid): void
    {
        if (empty($cart = $this->getCart($user_uuid))) {
            $cart = Order::query()
                ->create([
                    'user_uuid' => $user_uuid,
                    'status' => OrderEnum\Status::NEW,
                ]);
        }

        $cart->products()
            ->attach($product, ['quantity' => 1]);
    }

    public function removeProductFromCart(Product $product, string $user_uuid): void
    {
        ($cart = $this->getCart($user_uuid))
        && $cart->products()
            ->detach($product);
    }

    public function isProductInCart(Product $product, string $user_uuid): bool
    {
        return ($cart = $this->getCart($user_uuid))
            && $cart->products
                ->contains($product);
    }

    public function add(Product $product, string $user_uuid): void
    {
        $this->change($product, $user_uuid);
    }

    public function sub(Product $product, string $user_uuid): void
    {
        $this->change($product, $user_uuid, false);
    }

    protected function change(
        Product $product,
        string $user_uuid,
        bool $add = true,
    ): void {
        if (empty($cart = $this->getCart($user_uuid))) {
            $this->addProductToCart($product, $user_uuid);
        }

        $qt = $cart->products()
            ->where('product_uuid', $product->uuid)
            ->firstOrFail()
            ->pivot
            ->quantity;

        $cart->products()
            ->updateExistingPivot($product, [
                'quantity' => $qt + ($add ? 1 : (-1)),
            ]);
    }

    protected function getCart(string $user_uuid): ?Order
    {
        /** @var Order */
        return Order::query()
            ->where('user_uuid', $user_uuid)
            ->where('status', OrderEnum\Status::NEW)
            ->latest()
            ->first();
    }
}
