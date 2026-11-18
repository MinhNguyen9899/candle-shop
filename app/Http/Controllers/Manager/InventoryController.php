<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class InventoryController extends Controller
{
    /**
     * Hiển thị danh sách tồn kho.
     */
    public function index(Request $request)
    {
        // Thống kê tổng quan tồn kho.
        $summaryCounts = [
            'total' => Product::count(),

            'in_stock' => $this->applyStatusFilter(
                Product::query(),
                'in_stock'
            )->count(),

            'low_stock' => $this->applyStatusFilter(
                Product::query(),
                'low_stock'
            )->count(),

            'out_of_stock' => $this->applyStatusFilter(
                Product::query(),
                'out_of_stock'
            )->count(),

            'discontinued' => $this->applyStatusFilter(
                Product::query(),
                'discontinued'
            )->count(),
        ];

        // Truy vấn danh sách sản phẩm.
        $query = Product::with([
            'category',
            'inventory',
        ]);

        // Tìm kiếm theo tên sản phẩm hoặc mã SKU.
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái tồn kho.
        if ($request->filled('status')) {
            $query = $this->applyStatusFilter(
                $query,
                $request->status
            );
        }

        // Phân trang.
        $products = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manager.inventories.index', compact(
            'products',
            'summaryCounts'
        ));
    }

    /**
     * Lọc sản phẩm theo trạng thái tồn kho.
     */
    private function applyStatusFilter(
        Builder $query,
        ?string $status
    ): Builder {

        // Sản phẩm ngừng kinh doanh.
        if ($status === 'discontinued') {
            return $query->whereIn('status', [
                'discontinued',
                'inactive',
            ]);
        }

        // Các trạng thái tồn kho chỉ áp dụng cho sản phẩm đang kinh doanh.
        $query->where('status', 'active');

        // Sản phẩm hết hàng hoặc chưa có bản ghi tồn kho.
        if ($status === 'out_of_stock') {
            return $query->where(function ($q) {
                $q->whereDoesntHave('inventory')
                    ->orWhereHas('inventory', function ($inventoryQuery) {
                        $inventoryQuery->whereRaw(
                            '(on_hand_qty - reserved_qty) <= 0'
                        );
                    });
            });
        }

        // Sản phẩm sắp hết hàng.
        if ($status === 'low_stock') {
            return $query->whereHas('inventory', function ($inventoryQuery) {
                $inventoryQuery->whereRaw(
                    '(on_hand_qty - reserved_qty) > 0
                    AND (on_hand_qty - reserved_qty) <= (
                        SELECT low_stock_threshold
                        FROM products
                        WHERE products.id = inventories.product_id
                    )'
                );
            });
        }

        // Sản phẩm còn hàng.
        if ($status === 'in_stock') {
            return $query->whereHas('inventory', function ($inventoryQuery) {
                $inventoryQuery->whereRaw(
                    '(on_hand_qty - reserved_qty) > 0
                    AND (on_hand_qty - reserved_qty) > (
                        SELECT low_stock_threshold
                        FROM products
                        WHERE products.id = inventories.product_id
                    )'
                );
            });
        }

        return $query;
    }
}
