<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();

            // Biến thể sản phẩm phát sinh giao dịch
            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            // Người thực hiện
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Loại giao dịch: receipt, issue, sale, adjustment...
            $table->string('transaction_type', 30);

            // Số lượng giao dịch
            $table->unsignedInteger('quantity');

            // Loại và ID chứng từ liên quan
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            // Ghi chú
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
            $table->index(['product_variant_id', 'transaction_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
