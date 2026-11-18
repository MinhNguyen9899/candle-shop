<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StockReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockReceiptController extends Controller
{
    // Danh sách phiếu nhập kho.
    public function index()
    {
        $receipts = StockReceipt::with('createdBy')
            ->withCount('details')
            ->latest()
            ->paginate(10);

        return view('manager.stock_receipts.index', compact('receipts'));
    }

    // Chi tiết phiếu nhập kho.
    public function show(StockReceipt $stockReceipt)
    {
        $stockReceipt->load([
            'createdBy',
            'details.product',
        ]);

        return view('manager.stock_receipts.show', compact('stockReceipt'));
    }

    // Hiển thị form tạo phiếu nhập.
    public function create()
    {
        $products = Product::with('inventory')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('manager.stock_receipts.create', compact(
            'products',
            'categories'
        ));
    }

    // Lưu phiếu nhập kho.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'nullable|string|max:200',
            'note' => 'nullable|string',

            // Danh sách sản phẩm đã tồn tại.
            'items' => 'nullable|array',
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',

            // Danh sách nhiều sản phẩm mới.
            'new_products' => 'nullable|array',

            'new_products.*.name' => 'required|string|max:255',

            'new_products.*.sku' => [
                'required',
                'string',
                'max:100',
                'distinct',
                Rule::unique('products', 'sku'),
            ],

            'new_products.*.category_id' => [
                'required',
                'exists:categories,id',
            ],

            'new_products.*.price' => 'required|numeric|min:0',
            'new_products.*.unit_cost' => 'required|numeric|min:0',
            'new_products.*.quantity' => 'required|integer|min:1',

            'new_products.*.low_stock_threshold' => 'nullable|integer|min:0',
            'new_products.*.description' => 'nullable|string',
            'new_products.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

        ], [
            'items.*.product_id.required' => 'Vui lòng chọn sản phẩm.',
            'items.*.product_id.distinct' => 'Không được chọn trùng sản phẩm trong cùng phiếu nhập.',
            'items.*.product_id.exists' => 'Sản phẩm được chọn không tồn tại.',
            'items.*.quantity.required' => 'Vui lòng nhập số lượng.',
            'items.*.quantity.min' => 'Số lượng nhập phải lớn hơn 0.',
            'items.*.unit_cost.required' => 'Vui lòng nhập giá vốn.',

            'new_products.*.name.required' => 'Vui lòng nhập tên sản phẩm mới.',
            'new_products.*.sku.required' => 'Vui lòng nhập SKU sản phẩm mới.',
            'new_products.*.sku.unique' => 'SKU sản phẩm đã tồn tại trong hệ thống.',
            'new_products.*.sku.distinct' => 'Không được nhập trùng SKU của các sản phẩm mới.',
            'new_products.*.category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'new_products.*.price.required' => 'Vui lòng nhập giá bán.',
            'new_products.*.unit_cost.required' => 'Vui lòng nhập giá vốn.',
            'new_products.*.quantity.required' => 'Vui lòng nhập số lượng nhập kho.',
            'new_products.*.image.image' => 'Tệp tải lên phải là hình ảnh.',
            'new_products.*.image.max' => 'Ảnh sản phẩm không được vượt quá 5MB.',
        ]);

        $existingItems = $validated['items'] ?? [];
        $newProducts = $validated['new_products'] ?? [];

        // Phải có ít nhất một sản phẩm trong phiếu nhập.
        if (count($existingItems) === 0 && count($newProducts) === 0) {
            throw ValidationException::withMessages([
                'items' => 'Vui lòng thêm ít nhất một sản phẩm vào phiếu nhập.',
            ]);
        }

        // Kiểm tra sản phẩm cũ có đang hoạt động không.
        if (count($existingItems) > 0) {
            $productIds = collect($existingItems)->pluck('product_id');

            $activeProductCount = Product::whereIn('id', $productIds)
                ->where('status', 'active')
                ->count();

            if ($activeProductCount !== $productIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Có sản phẩm không tồn tại hoặc đã ngừng kinh doanh.',
                ]);
            }
        }

        // Kiểm tra các danh mục của sản phẩm mới.
        if (count($newProducts) > 0) {
            $categoryIds = collect($newProducts)
                ->pluck('category_id')
                ->unique();

            $activeCategoryCount = Category::whereIn('id', $categoryIds)
                ->where('status', 'active')
                ->count();

            if ($activeCategoryCount !== $categoryIds->count()) {
                throw ValidationException::withMessages([
                    'new_products' => 'Có danh mục đã ngừng hoạt động hoặc không tồn tại.',
                ]);
            }
        }

        $uploadedFiles = [];

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $existingItems,
                $newProducts,
                &$uploadedFiles
            ) {
                // Tạo phiếu nhập chung.
                $receipt = StockReceipt::create([
                    'receipt_code' => 'PN-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'created_by' => auth()->id(),
                    'supplier_name' => $validated['supplier_name'] ?? null,
                    'total_cost' => 0,
                    'note' => $validated['note'] ?? null,
                    'received_at' => now(),
                ]);

                $totalCost = 0;

                // PHẦN 1: Xử lý các sản phẩm cũ.
                foreach ($existingItems as $item) {
                    $product = Product::whereKey($item['product_id'])
                        ->where('status', 'active')
                        ->lockForUpdate()
                        ->firstOrFail();

                    $this->addReceiptItem(
                        $receipt,
                        $product,
                        (int) $item['quantity'],
                        (float) $item['unit_cost'],
                        $totalCost
                    );
                }

                // PHẦN 2: Tạo và nhập nhiều sản phẩm mới.
                foreach ($newProducts as $index => $productData) {

                    // Tạo slug không trùng.
                    $baseSlug = Str::slug($productData['name']);

                    if ($baseSlug === '') {
                        $baseSlug = 'san-pham';
                    }

                    $slug = $baseSlug;
                    $counter = 1;

                    while (Product::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }

                    // Lưu ảnh riêng cho từng sản phẩm mới.
                    $productImage = null;

                    $image = $request->file("new_products.$index.image");

                    if ($image) {
                        $productImage = $image->store('products', 'public');
                        $uploadedFiles[] = $productImage;
                    }

                    $unitCost = (float) $productData['unit_cost'];

                    // Tạo sản phẩm mới.
                    $product = Product::create([
                        'category_id' => $productData['category_id'],
                        'name' => $productData['name'],
                        'slug' => $slug,
                        'description' => $productData['description'] ?? null,
                        'image' => $productImage,
                        'sku' => $productData['sku'],
                        'price' => $productData['price'],
                        'cost_price' => $unitCost,
                        'low_stock_threshold' => $productData['low_stock_threshold'] ?? 5,
                        'status' => 'active',
                    ]);

                    // Nhập kho sản phẩm mới vào cùng phiếu.
                    $this->addReceiptItem(
                        $receipt,
                        $product,
                        (int) $productData['quantity'],
                        $unitCost,
                        $totalCost
                    );
                }

                // Cập nhật tổng tiền của toàn bộ phiếu nhập.
                $receipt->update([
                    'total_cost' => $totalCost,
                ]);
            });
        } catch (\Throwable $exception) {

            // Nếu lưu thất bại, xóa các ảnh đã tải lên.
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $exception;
        }

        // Điều hướng theo vai trò người dùng.
        $route = auth()->user()->role === 'employee'
            ? 'employee.stock-receipts.index'
            : 'manager.stock-receipts.index';

        return redirect()
            ->route($route)
            ->with('success', 'Tạo phiếu nhập kho thành công.');
    }

    // Thêm sản phẩm vào phiếu nhập và cập nhật tồn kho.
    private function addReceiptItem(
        StockReceipt $receipt,
        Product $product,
        int $quantity,
        float $unitCost,
        float &$totalCost
    ): void {
        $subtotal = round($quantity * $unitCost, 2);

        // Lưu chi tiết phiếu nhập.
        $receipt->details()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'subtotal' => $subtotal,
        ]);

        // Lấy hoặc tạo tồn kho.
        $inventory = Inventory::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'on_hand_qty' => 0,
                'reserved_qty' => 0,
            ]
        );

        // Khóa bản ghi tồn kho trước khi cập nhật.
        $inventory = Inventory::whereKey($inventory->id)
            ->lockForUpdate()
            ->firstOrFail();

        // Cộng số lượng nhập vào tồn kho thực tế.
        $inventory->increment('on_hand_qty', $quantity);

        // Cập nhật giá vốn hiện tại.
        $product->update([
            'cost_price' => $unitCost,
        ]);

        // Ghi lịch sử giao dịch kho.
        InventoryTransaction::create([
            'product_id' => $product->id,
            'created_by' => auth()->id(),
            'transaction_type' => 'receipt',
            'quantity' => $quantity,
            'reference_type' => 'stock_receipt',
            'reference_id' => $receipt->id,
            'note' => 'Nhập kho theo phiếu ' . $receipt->receipt_code,
        ]);

        $totalCost += $subtotal;
    }
}
