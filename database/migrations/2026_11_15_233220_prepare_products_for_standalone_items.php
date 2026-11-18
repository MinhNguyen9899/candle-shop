<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Bổ sung thông tin vào bảng products
        |--------------------------------------------------------------------------
        | price tạm thời cho phép NULL để chuyển dữ liệu cũ an toàn.
        */

        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 100)->nullable()->unique();

            $table->string('scent', 150)->nullable();

            $table->unsignedInteger('weight_grams')->nullable();

            $table->decimal('price', 12, 2)->nullable();

            $table->decimal('cost_price', 12, 2)
                ->default(0.00);
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Bổ sung product_id vào inventories
        |--------------------------------------------------------------------------
        */

        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')
                ->nullable()
                ->unique();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Bổ sung product_id vào inventory_transactions
        |--------------------------------------------------------------------------
        */

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Bổ sung product_id vào stock_receipt_details
        |--------------------------------------------------------------------------
        */

        Schema::table('stock_receipt_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Bổ sung product_id vào order_details
        |--------------------------------------------------------------------------
        */

        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Bổ sung product_id vào stock_reservation_details
        |--------------------------------------------------------------------------
        */

        Schema::table('stock_reservation_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Xóa các quan hệ product_id mới
        |--------------------------------------------------------------------------
        */

        Schema::table('stock_reservation_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        Schema::table('stock_receipt_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropUnique(['product_id']);
            $table->dropColumn('product_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Xóa thông tin bổ sung trong products
        |--------------------------------------------------------------------------
        */

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);

            $table->dropColumn([
                'sku',
                'scent',
                'weight_grams',
                'price',
                'cost_price',
            ]);
        });
    }
};
