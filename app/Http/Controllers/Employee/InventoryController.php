<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm và tình trạng tồn kho.
     */
    public function index(Request $request)
    {
        // Thống kê số lượng sản phẩm theo từng trạng thái.
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

        // Lấy sản phẩm cùng danh mục và thông tin tồn kho.
        $query = Product::with([
            'category',
            'inventory',
        ]);

        // Tìm kiếm theo tên sản phẩm hoặc mã SKU.
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('sku', 'like', '%' . $keyword . '%');
            });
        }

        // Chỉ lọc khi người dùng chọn trạng thái.
        if ($request->filled('status')) {
            $this->applyStatusFilter(
                $query,
                $request->input('status')
            );
        }

        $products = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'employee.inventories.index',
            compact('products', 'summaryCounts')
        );
    }

    /**
     * Lọc sản phẩm theo trạng thái tồn kho.
     */
    private function applyStatusFilter(
        Builder $query,
        ?string $status
    ): Builder {

        // Sản phẩm đã ngừng kinh doanh.
        if ($status === 'discontinued') {
            return $query->whereIn('status', [
                'discontinued',
                'inactive',
            ]);
        }

        // Chỉ xét sản phẩm đang kinh doanh.
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

        // Sản phẩm còn hàng nhưng chạm ngưỡng cảnh báo.
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

        // Sản phẩm có lượng hàng khả dụng vượt ngưỡng cảnh báo.
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
