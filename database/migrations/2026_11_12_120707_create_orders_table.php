<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Mã đơn hàng
            $table->string('order_code')->unique();

            // Khách hàng đặt đơn
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Nhân viên phụ trách đơn hàng
            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Thông tin người nhận được lưu tại thời điểm đặt hàng
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->text('shipping_address');

            // Thông tin tiền hàng
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Phương thức và trạng thái thanh toán
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('unpaid');

            // Trạng thái xử lý đơn hàng
            $table->string('order_status')->default('pending');

            // Ghi chú của khách hàng
            $table->text('note')->nullable();

            // Thời điểm xử lý đơn
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
