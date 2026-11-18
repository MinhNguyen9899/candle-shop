<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            // Đơn hàng chứa sản phẩm
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Biến thể sản phẩm được mua
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // Lưu thông tin sản phẩm tại thời điểm mua
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('scent')->nullable();
            $table->unsignedInteger('weight_grams')->nullable();

            // Số lượng và giá tại thời điểm đặt hàng
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('unit_cost_snapshot', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
