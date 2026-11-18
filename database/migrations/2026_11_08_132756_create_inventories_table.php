<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_variant_id')
                ->unique()
                ->constrained('product_variants')
                ->cascadeOnDelete();

            $table->unsignedInteger('on_hand_qty')->default(0);
            $table->unsignedInteger('reserved_qty')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
