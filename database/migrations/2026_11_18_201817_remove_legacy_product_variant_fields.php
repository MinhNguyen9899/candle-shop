
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kiểm tra các chỉ mục hiện tại.
        $indexes = Schema::getIndexes('stock_reservation_details');

        $hasOldUnique = collect($indexes)->contains(
            fn ($index) => $index['name'] === 'reservation_variant_unique'
        );

        $hasTempIndex = collect($indexes)->contains(
            fn ($index) => $index['name'] === 'srd_reservation_id_temp_index'
        );

        // 2. Tạo chỉ mục thay thế để bảo vệ khóa ngoại stock_reservation_id.
        if (!$hasTempIndex) {
            Schema::table('stock_reservation_details', function (Blueprint $table) {
                $table->index(
                    'stock_reservation_id',
                    'srd_reservation_id_temp_index'
                );
            });
        }

        // 3. Xóa UNIQUE cũ.
        if ($hasOldUnique) {
            Schema::table('stock_reservation_details', function (Blueprint $table) {
                $table->dropUnique('reservation_variant_unique');
            });
        }

        // 4. Xóa chỉ mục cũ của product_variant_id.
        $indexes = Schema::getIndexes('stock_reservation_details');

        foreach ($indexes as $index) {
            if (
                $index['name'] ===
                'stock_reservation_details_product_variant_id_foreign'
            ) {
                Schema::table('stock_reservation_details', function (Blueprint $table) use ($index) {
                    $table->dropIndex($index['name']);
                });
            }
        }

        // 5. Xóa cột product_variant_id.
        if (Schema::hasColumn('stock_reservation_details', 'product_variant_id')) {
            Schema::table('stock_reservation_details', function (Blueprint $table) {
                $table->dropColumn('product_variant_id');
            });
        }

        // 6. Tạo UNIQUE mới theo sản phẩm độc lập.
        $indexes = Schema::getIndexes('stock_reservation_details');

        $hasNewUnique = collect($indexes)->contains(
            fn ($index) => $index['name'] === 'reservation_product_unique'
        );

        if (!$hasNewUnique) {
            Schema::table('stock_reservation_details', function (Blueprint $table) {
                $table->unique(
                    ['stock_reservation_id', 'product_id'],
                    'reservation_product_unique'
                );
            });
        }

        // 7. Xóa chỉ mục tạm sau khi đã có UNIQUE mới.
        $indexes = Schema::getIndexes('stock_reservation_details');

        $hasTempIndex = collect($indexes)->contains(
            fn ($index) => $index['name'] === 'srd_reservation_id_temp_index'
        );

        if ($hasTempIndex) {
            Schema::table('stock_reservation_details', function (Blueprint $table) {
                $table->dropIndex('srd_reservation_id_temp_index');
            });
        }

        // 8. Xóa scent và weight_grams khỏi products.
        $productColumns = [];

        if (Schema::hasColumn('products', 'scent')) {
            $productColumns[] = 'scent';
        }

        if (Schema::hasColumn('products', 'weight_grams')) {
            $productColumns[] = 'weight_grams';
        }

        if (!empty($productColumns)) {
            Schema::table('products', function (Blueprint $table) use ($productColumns) {
                $table->dropColumn($productColumns);
            });
        }

        // 9. Xóa bảng product_variants cũ.
        if (Schema::hasTable('product_variants')) {
            Schema::drop('product_variants');
        }
    }

    public function down(): void
    {
        throw new RuntimeException(
            'Không thể rollback migration này. Hãy khôi phục database từ bản sao lưu nếu cần.'
        );
    }
};
