<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Product;
use App\Enum\Order as OrderEnum;
use Illuminate\Database\Eloquent\Builder;

abstract class InteractsWithCart
{
    abstract public function addProductToCart(Product $product, string $user_key): void;

    abstract public function list(string $user_key): ?Order;

    public function removeProductFromCart(Product $product, string $user_key): void
    {
        ($cart = $this->getCart($user_key))
        && $cart->products()
            ->detach($product);
    }

    public function isProductInCart(Product $product, string $user_key): bool
    {
        return ($cart = $this->getCart($user_key))
            && $cart->products
                ->contains($product);
    }

    public function add(Product $product, string $user_key): void
    {
        $this->change($product, $user_key);
    }

    public function sub(Product $product, string $user_key): void
    {
        $this->change($product, $user_key, false);
    }

    protected function change(
        Product $product,
        string $user_key,
        bool $add = true,
    ): void {
        if (empty($cart = $this->getCart($user_key))) {
            $this->addProductToCart($product, $user_key);
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

    abstract protected function getCart(string $user_key): ?Order;
}
