<?php

namespace Database\Factories;

use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enum;

/**
 * @extends Factory<ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = Enum\Product\Currency::cases();
        $count = count($currencies);

        return [
            'currency' => $currencies[rand(0, $count - 1)],
            'amount' => fake()->randomFloat(2, max: pow(10, 7)),
        ];
    }
}
