
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {

            $productNames = [
                'YAN-XMAS-122G' =>
                    'Nến ly tròn Yankee Candle - CHRISTMAS COOKIE (122g)',

                '42342' =>
                    'Nến thơm cao cấp LAB - Lemon Leaf & Sage (425g)',

                'YAN-VANI-122G' =>
                    'Nến ly tròn Yankee Candle - VANILLA CREME BRULEE (122g)',

                'YAN-CEDAR-122G' =>
                    'Nến ly tròn Yankee Candle - BALSAM & CEDAR (122g)',
            ];

            $existingProducts = DB::table('products')
                ->whereIn('sku', array_keys($productNames))
                ->count();

            if ($existingProducts > 0) {
                throw new RuntimeException(
                    'Đã tồn tại sản phẩm có SKU cần chuyển đổi.'
                );
            }

            $variants = DB::table('product_variants as pv')
                ->join('products as p', 'p.id', '=', 'pv.product_id')
                ->leftJoin(
                    'inventories as i',
                    'i.product_variant_id',
                    '=',
                    'pv.id'
                )
                ->select(
                    'pv.id as variant_id',
                    'pv.product_id as old_product_id',
                    'pv.sku',
                    'pv.scent',
                    'pv.weight_grams',
                    'pv.price',
                    'pv.cost_price',
                    'pv.image as variant_image',
                    'pv.status as variant_status',
                    'p.category_id',
                    'p.description',
                    'p.image as old_product_image',
                    'i.on_hand_qty',
                    'i.reserved_qty',
                    'i.low_stock_threshold'
                )
                ->orderBy('pv.id')
                ->lockForUpdate()
                ->get();

            if ($variants->count() !== 4) {
                throw new RuntimeException(
                    'Số lượng biến thể không đúng 4. Dừng chuyển đổi để kiểm tra dữ liệu.'
                );
            }

            $expectedSkus = array_map(
                'strval',
                array_keys($productNames)
            );

            $actualSkus = $variants->pluck('sku')
                ->map(fn ($sku) => (string) $sku)
                ->all();

            sort($expectedSkus);
            sort($actualSkus);

            if ($expectedSkus !== $actualSkus) {
                throw new RuntimeException(
                    'Danh sách SKU hiện tại không khớp dữ liệu dự kiến.'
                );
            }

            foreach ($variants as $variant) {
                if (
                    ($variant->sku === 'YAN-XMAS-122G' && $variant->old_product_id != 1) ||
                    ($variant->sku === '42342' && $variant->old_product_id != 3)
                ) {
                    throw new RuntimeException(
                        'Sản phẩm gốc không đúng với dữ liệu đã kiểm tra.'
                    );
                }

                if (
                    $variant->on_hand_qty === null ||
                    $variant->reserved_qty === null
                ) {
                    throw new RuntimeException(
                        'Có biến thể chưa có dữ liệu tồn kho.'
                    );
                }
            }

            $productMapping = [];

            foreach ($variants as $variant) {
                $name = $productNames[$variant->sku];

                $image = $variant->variant_image
                    ?: $variant->old_product_image;

                $threshold = $variant->low_stock_threshold ?? 5;

                // Giữ lại ID sản phẩm cũ cho 2 sản phẩm đầu tiên.
                $productId = match ($variant->sku) {
                    'YAN-XMAS-122G' => 1,
                    '42342' => 3,
                    default => null,
                };

                $baseSlug = Str::slug($name . ' ' . $variant->sku);
                $slug = $baseSlug;
                $suffix = 1;

                while (
                    DB::table('products')
                        ->where('slug', $slug)
                        ->when(
                            $productId !== null,
                            fn ($query) => $query->where('id', '!=', $productId)
                        )
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $suffix;
                    $suffix++;
                }

                $productData = [
                    'category_id' => $variant->category_id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $variant->description,
                    'image' => $image,
                    'status' => $variant->variant_status,
                    'sku' => $variant->sku,
                    'scent' => $variant->scent,
                    'weight_grams' => $variant->weight_grams,
                    'price' => $variant->price,
                    'cost_price' => $variant->cost_price,
                    'low_stock_threshold' => $threshold,
                    'updated_at' => now(),
                ];

                if ($productId !== null) {
                    DB::table('products')
                        ->where('id', $productId)
                        ->update($productData);
                } else {
                    $productData['created_at'] = now();

                    $productId = DB::table('products')
                        ->insertGetId($productData);
                }

                $productMapping[$variant->variant_id] = $productId;

                DB::table('inventories')
                    ->where('product_variant_id', $variant->variant_id)
                    ->update([
                        'product_id' => $productId,
                        'low_stock_threshold' => $threshold,
                        'updated_at' => now(),
                    ]);
            }

            // Cập nhật liên kết sản phẩm trong toàn bộ lịch sử.
            $historyTables = [
                'inventory_transactions',
                'stock_receipt_details',
                'order_details',
                'stock_reservation_details',
            ];

            foreach ($productMapping as $variantId => $productId) {
                foreach ($historyTables as $table) {
                    DB::table($table)
                        ->where('product_variant_id', $variantId)
                        ->update([
                            'product_id' => $productId,
                        ]);
                }
            }
        });
    }

    public function down(): void
    {
        // Không tự động đảo ngược vì cần bảo toàn dữ liệu lịch sử.
        // Nếu cần khôi phục, sử dụng bản sao lưu database.
    }
};
