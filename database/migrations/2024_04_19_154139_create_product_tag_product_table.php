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
        Schema::create('product_tag_product', function (Blueprint $table) {
            $table->foreignUuid('product_uuid')
                ->constrained('products', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('product_tag_id')
                ->constrained('product_tags')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag_product');
    }
};
