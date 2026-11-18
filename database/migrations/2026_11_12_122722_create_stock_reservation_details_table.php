<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservation_details', function (Blueprint $table) {
            $table->id();

            // Phiếu giữ hàng
            $table->foreignId('stock_reservation_id')
                ->constrained('stock_reservations')
                ->cascadeOnDelete();

            // Biến thể sản phẩm được giữ
            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            // Số lượng được giữ
            $table->unsignedInteger('quantity');

            $table->timestamps();

            // Một biến thể chỉ xuất hiện một lần trong một phiếu giữ hàng
            $table->unique(
                ['stock_reservation_id', 'product_variant_id'],
                'reservation_variant_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservation_details');
    }
};
