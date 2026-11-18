<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->id();

            // Đơn hàng được giữ hàng
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            // Trạng thái giữ hàng
            $table->string('status')->default('reserved');

            // Thời điểm giữ hàng và giải phóng hàng
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};
