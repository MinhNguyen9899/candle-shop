
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

            // Lấy dữ liệu 4 biến thể cũ.
            $variants = DB::table('product_variants as pv')
                ->join('products as p', 'p.id', '=', 'pv.product_id')
                ->leftJoin('inventories as i', 'i.product_variant_id', '=', 'pv.id')
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

            // Kiểm tra đúng 4 biến thể.
            if ($variants->count() !== 4) {
                throw new RuntimeException(
                    'Không tìm thấy đúng 4 biến thể. Đã dừng chuyển đổi.'
                );
            }

            $expectedSkus = array_keys($productNames);
            $actualSkus = $variants->pluck('sku')->all();

            sort($expectedSkus);
            sort($actualSkus);

            if ($expectedSkus !== $actualSkus) {
                throw new RuntimeException(
                    'SKU không khớp dữ liệu đã kiểm tra.'
                );
            }

            // Ánh xạ ID biến thể cũ sang ID sản phẩm mới.
            $productMapping = [];

            foreach ($variants as $variant) {

                $name = $productNames[$variant->sku];

                $image = $variant->variant_image
                    ?: $variant->old_product_image;

                $threshold = $variant->low_stock_threshold ?? 5;

                // Giữ lại ID sản phẩm cũ cho hai sản phẩm đầu.
                if ($variant->sku === 'YAN-XMAS-122G') {
                    $productId = 1;
                } elseif ($variant->sku === '42342') {
                    $productId = 3;
                } else {
                    $productId = null;
                }

                // Tạo slug không trùng.
                $baseSlug = Str::slug($name . ' ' . $variant->sku);
                $slug = $baseSlug;
                $counter = 1;

                while (
                    DB::table('products')
                        ->where('slug', $slug)
                        ->when(
                            $productId !== null,
                            fn ($query) => $query->where('id', '!=', $productId)
                        )
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
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

                // Cập nhật tồn kho.
                DB::table('inventories')
                    ->where('product_variant_id', $variant->variant_id)
                    ->update([
                        'product_id' => $productId,
                        'low_stock_threshold' => $threshold,
                        'updated_at' => now(),
                    ]);
            }

            // Chuyển các liên kết lịch sử.
            foreach ($productMapping as $variantId => $productId) {

                DB::table('inventory_transactions')
                    ->where('product_variant_id', $variantId)
                    ->update(['product_id' => $productId]);

                DB::table('stock_receipt_details')
                    ->where('product_variant_id', $variantId)
                    ->update(['product_id' => $productId]);

                DB::table('order_details')
                    ->where('product_variant_id', $variantId)
                    ->update(['product_id' => $productId]);

                DB::table('stock_reservation_details')
                    ->where('product_variant_id', $variantId)
                    ->update(['product_id' => $productId]);
            }
        });
    }

    public function down(): void
    {
        // Không tự động đảo ngược dữ liệu chuyển đổi.
        // Sử dụng bản sao lưu database nếu cần khôi phục.
    }
};
