<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->foreignUuid('order_uuid')
                ->nullable()
                ->constrained('orders', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uuid('stamp')
                ->nullable()
                ->index();

            $table->foreignUuid('product_uuid')
                ->constrained('products', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
