
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nhân viên xác nhận giao hàng thành công
            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Thời điểm cửa hàng thực nhận tiền COD
            $table->timestamp('paid_at')->nullable();

            // Nhân viên xác nhận đã nhận tiền COD
            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['completed_by']);
            $table->dropForeign(['paid_by']);

            $table->dropColumn([
                'completed_by',
                'paid_at',
                'paid_by',
            ]);
        });
    }
};
