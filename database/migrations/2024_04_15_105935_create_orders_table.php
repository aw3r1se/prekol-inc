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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid()
                ->primary();

            $table->foreignUuid('user_uuid')
                ->nullable()
                ->constrained('users', 'uuid')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->uuid('stamp')
                ->nullable()
                ->index();

            $table->unsignedTinyInteger('status')
                ->default(Enum\Order\Status::NEW);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
