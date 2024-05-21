<?php

namespace App\Services;

use App\Models\Order;
use App\Enum\Order as OrderEnum;
use App\Models\Product;
use App\Models\User;
use Exception;

class OrderService
{
    /**
     * @throws Exception
     */
    public function addProduct(
        Product $product,
        ?User   $user = null,
        ?string $stamp = null,
    ): void {
        if (empty($user) && empty($stamp)) {
            throw new Exception('Can\'t define user');
        }

        if ($user) {
            $this->bindProductToLoggedUser($product, $user);

            return;
        }

        $this->bindProductToUnknownUser($product, $stamp);
    }

    public function bindProductToLoggedUser(
        Product $product,
        User    $user,
    ): void {
        /** @var Order|null $current_order */
        $current_order = $user->current_order;

        if ($current_order) {
            $current_order->products()
                ->attach($product);

            return;
        }

        $current_order = new Order([
            'status' => OrderEnum\Status::NEW,
        ]);

        $current_order->customer()
            ->associate($user)
            ->save();
    }

    public function bindProductToUnknownUser(
        Product $product,
        string  $stamp,
    ): void {
        /** @var Order $order */
        $order = Order::query()
            ->firstWhere('stamp', $stamp);

        if (empty($order)) {
            $order = new Order([
                'stamp' => $stamp,
                'status' => OrderEnum\Status::NEW,
            ]);
        }

        $order->products()
            ->attach($product);
    }

    /**
     * @throws Exception
     */
    public function isProductInOrder(
        Product $product,
        ?User $user = null,
        ?string $stamp = null,
    ): bool {
        if (empty($user) && empty($stamp)) {
            throw new Exception('Can\'t define user');
        }

        if ($user) {
            return $this->isProductInRealOrder($product, $user);
        }

        return $this->isProductInHiddenOrder($product, $stamp);
    }

    public function isProductInRealOrder(Product $product, User $user): bool
    {
        $user->load([
            'currentOrder',
            'currentOrder.products',
        ]);

        return $user->current_order
            ->products
            ->contains($product);
    }

    public function isProductInHiddenOrder(Product $product, string $stamp): bool
    {
        /** @var Order $order */
        $order = Order::query()
            ->with(['products'])
            ->where('stamp', $stamp)
            ->latest()
            ->first();

        if (empty($order)) {
            return false;
        }

        return $order->products
            ->contains($product);
    }
}
