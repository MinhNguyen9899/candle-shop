<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_receipt_details', function (Blueprint $table) {
            $table->id();

            // Phiếu nhập tương ứng
            $table->foreignId('stock_receipt_id')
                ->constrained('stock_receipts')
                ->cascadeOnDelete();

            // Biến thể được nhập
            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            // Số lượng nhập
            $table->unsignedInteger('quantity');

            // Giá nhập một sản phẩm
            $table->decimal('unit_cost', 12, 2);

            // Thành tiền
            $table->decimal('subtotal', 14, 2);

            $table->timestamps();

            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_receipt_details');
    }
};
