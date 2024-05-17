<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $products = Product::query()
            ->inRandomOrder()
            ->limit(50)
            ->get();

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->customer()
                ->associate($users->shift())
                ->save();

            for ($j = 1; $j <= 5; $j++) {
                $order->products()
                    ->attach($products->shift(), [
                        'quantity' => rand(1, $j),
                    ]);
            }
        }
    }
}
