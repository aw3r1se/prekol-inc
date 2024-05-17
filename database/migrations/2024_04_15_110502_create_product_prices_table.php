<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('product_uuid')
                ->constrained('products', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('currency')
                ->default(Enum\Product\Currency::RUB);

            $table->decimal('amount', 10)
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
