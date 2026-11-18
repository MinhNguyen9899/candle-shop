<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Tổng số sản phẩm
        $totalProducts = Product::count();

        // Số sản phẩm đang kinh doanh
        $activeProducts = Product::where('status', 'active')->count();

        // Số sản phẩm ngừng kinh doanh
        $inactiveProducts = Product::whereIn('status', [
            'discontinued',
            'inactive',
        ])->count();

        // Số đơn hàng đang chờ xác nhận
        $pendingOrders = Order::where('order_status', 'pending')
            ->count();

        // Số đơn hàng hoàn thành trong tháng
        $completedOrders = Order::where('order_status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();

        // Tổng số lượng tồn kho vật lý
        $totalStock = Inventory::sum('on_hand_qty');

        // Tổng số lượng hàng đang được giữ
        $totalReserved = Inventory::sum('reserved_qty');

       // Số sản phẩm sắp hết hàng.
        $lowStockCount = Product::where('status', 'active')
            ->whereHas('inventory', function ($query) {
                $query->whereRaw('(on_hand_qty - reserved_qty) > 0')
                    ->whereRaw(
                        '(on_hand_qty - reserved_qty) <= products.low_stock_threshold'
                    );
            })
            ->count();

        // Số sản phẩm đã hết hàng, bao gồm sản phẩm chưa có bản ghi tồn kho.
        $outOfStockCount = Product::where('status', 'active')
            ->where(function ($query) {
                $query->whereDoesntHave('inventory')
                    ->orWhereHas('inventory', function ($inventoryQuery) {
                        $inventoryQuery->whereRaw(
                            '(on_hand_qty - reserved_qty) <= 0'
                        );
                    });
            })
            ->count();

        // Doanh thu tiền hàng trong tháng
        $monthlyRevenue = Order::where('order_status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('subtotal');

        // Giá vốn hàng đã bán trong tháng
        $monthlyCost = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->whereMonth('orders.completed_at', now()->month)
            ->whereYear('orders.completed_at', now()->year)
            ->selectRaw(
                'COALESCE(SUM(order_details.quantity * order_details.unit_cost_snapshot), 0) as total'
            )
            ->value('total');

        // Chi phí phát sinh trong tháng
        $monthlyExpenses = Expense::whereMonth(
            'expense_date',
            now()->month
        )->whereYear(
            'expense_date',
            now()->year
        )->sum('amount');

        // Lợi nhuận tạm tính
        $monthlyProfit = $monthlyRevenue - $monthlyCost - $monthlyExpenses;

        return view('manager.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'pendingOrders',
            'completedOrders',
            'totalStock',
            'totalReserved',
            'lowStockCount',
            'outOfStockCount',
            'monthlyRevenue',
            'monthlyCost',
            'monthlyExpenses',
            'monthlyProfit'
        ));
    }
}
