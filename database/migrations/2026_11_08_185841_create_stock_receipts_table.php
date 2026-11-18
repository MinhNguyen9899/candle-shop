<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_receipts', function (Blueprint $table) {
            $table->id();

            // Mã phiếu nhập
            $table->string('receipt_code', 50)->unique();

            // Người tạo phiếu nhập
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Nhà cung cấp
            $table->string('supplier_name', 200)->nullable();

            // Tổng giá trị phiếu nhập
            $table->decimal('total_cost', 14, 2)->default(0);

            // Ghi chú
            $table->text('note')->nullable();

            // Thời gian nhập kho
            $table->dateTime('received_at');

            $table->timestamps();

            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_receipts');
    }
};
