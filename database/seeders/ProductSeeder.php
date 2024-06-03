<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;
use App\Enum;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
            ->count(50)
            ->create()
            ->each(function (Product $product) {
                $product->prices()
                    ->saveMany(
                        ProductPrice::factory()
                            ->count(5)
                            ->make(),
                    );

                $product->tags()
                    ->saveMany(
                        ProductTag::factory()
                            ->count(5)
                            ->make(),
                    );

                Storage::disk('public')
                    ->copy('stub.jpg', $product->uuid);
                $product->addMediaFromDisk($product->uuid, 'public')
                    ->toMediaCollection('products', 'products');
            });
    }
}
