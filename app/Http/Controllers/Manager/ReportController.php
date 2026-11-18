<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Hiển thị báo cáo doanh thu và lợi nhuận.
     */
    public function index(Request $request)
    {
        // Kiểm tra bộ lọc ngày.
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ], [
            'date_from.date' => 'Ngày bắt đầu không hợp lệ.',
            'date_to.date' => 'Ngày kết thúc không hợp lệ.',
            'date_to.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
        ]);

        // Mặc định xem báo cáo từ đầu tháng đến hiện tại.
        $dateFrom = !empty($validated['date_from'])
            ? Carbon::parse($validated['date_from'])->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $dateTo = !empty($validated['date_to'])
            ? Carbon::parse($validated['date_to'])->endOfDay()
            : now()->endOfDay();

        // Chỉ lấy các đơn hàng đã hoàn thành.
        $ordersQuery = Order::where('order_status', 'completed')
            ->whereBetween('completed_at', [$dateFrom, $dateTo]);

        // Tổng doanh thu tiền hàng của các đơn đã hoàn thành.
        $revenue = (clone $ordersQuery)->sum('subtotal');

        // Tổng số đơn hoàn thành.
        $completedOrders = (clone $ordersQuery)->count();

        // Số đơn đã thanh toán.
        $paidOrders = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->count();

        // Số đơn chưa thanh toán.
        $unpaidOrders = (clone $ordersQuery)
            ->where('payment_status', 'unpaid')
            ->count();

        // Tổng giá vốn của sản phẩm đã bán.
        $costOfGoods = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 'completed')
            ->whereBetween('orders.completed_at', [$dateFrom, $dateTo])
            ->selectRaw(
                'COALESCE(SUM(order_details.quantity * order_details.unit_cost_snapshot), 0) as total'
            )
            ->value('total');

        // Tổng chi phí vận hành trong kỳ.
        $expenses = Expense::whereBetween('expense_date', [
            $dateFrom->toDateString(),
            $dateTo->toDateString(),
        ])->sum('amount');

        // Tính lợi nhuận.
        $grossProfit = $revenue - $costOfGoods;
        $netProfit = $grossProfit - $expenses;

        // Doanh thu theo ngày.
        $dailyRevenue = (clone $ordersQuery)
            ->selectRaw('DATE(completed_at) as report_date')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->orderBy('report_date')
            ->get();

        // Top 5 sản phẩm bán chạy theo số lượng.
        $topProducts = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 'completed')
            ->whereBetween('orders.completed_at', [$dateFrom, $dateTo])
            ->select(
                'order_details.product_id',
                'order_details.product_name'
            )
            ->selectRaw('SUM(order_details.quantity) as total_quantity')
            ->selectRaw('SUM(order_details.subtotal) as total_revenue')
            ->groupBy(
                'order_details.product_id',
                'order_details.product_name'
            )
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('manager.reports.index', compact(
            'dateFrom',
            'dateTo',
            'revenue',
            'costOfGoods',
            'expenses',
            'grossProfit',
            'netProfit',
            'completedOrders',
            'paidOrders',
            'unpaidOrders',
            'dailyRevenue',
            'topProducts'
        ));
    }

    public function export(\Illuminate\Http\Request $request)
    {
        // Kiểm tra khoảng thời gian
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        // Mặc định lấy từ đầu tháng đến hiện tại
        $dateFrom = $validated['date_from']
            ?? now()->startOfMonth()->toDateString();

        $dateTo = $validated['date_to']
            ?? now()->toDateString();

        $startDate = \Carbon\Carbon::parse($dateFrom)->startOfDay();
        $endDate = \Carbon\Carbon::parse($dateTo)->endOfDay();

        // Lấy các đơn hàng đã hoàn thành
        $orders = \App\Models\Order::where('order_status', 'completed')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->orderBy('completed_at')
            ->get();

        // Tính doanh thu tiền hàng
        $revenue = $orders->sum('subtotal');

        // Tính giá vốn hàng đã bán
        $costOfGoods = \Illuminate\Support\Facades\DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->whereBetween('orders.completed_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(SUM(order_details.quantity * order_details.unit_cost_snapshot), 0) as total')
            ->value('total') ?? 0;

        // Tính tổng chi phí phát sinh
        $expenses = \App\Models\Expense::whereBetween(
            'expense_date',
            [$dateFrom, $dateTo]
        )->sum('amount');

        $grossProfit = $revenue - $costOfGoods;
        $netProfit = $grossProfit - $expenses;

        // Tạo tên file
        $fileName = 'bao-cao-doanh-thu-' . $dateFrom . '-den-' . $dateTo . '.csv';

        // Xuất dữ liệu CSV
        return response()->streamDownload(function () use (
            $orders,
            $revenue,
            $costOfGoods,
            $expenses,
            $grossProfit,
            $netProfit,
            $dateFrom,
            $dateTo
        ) {
            $file = fopen('php://output', 'w');

            // BOM giúp Excel hiển thị tiếng Việt đúng
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tiêu đề báo cáo
            fputcsv($file, ['BÁO CÁO DOANH THU CỬA HÀNG NẾN THƠM']);
            fputcsv($file, ['Từ ngày', $dateFrom]);
            fputcsv($file, ['Đến ngày', $dateTo]);
            fputcsv($file, []);

            // Tổng hợp tài chính
            fputcsv($file, ['CHỈ TIÊU', 'GIÁ TRỊ']);
            fputcsv($file, ['Doanh thu bán hàng', $revenue]);
            fputcsv($file, ['Giá vốn hàng bán', $costOfGoods]);
            fputcsv($file, ['Lợi nhuận gộp', $grossProfit]);
            fputcsv($file, ['Chi phí phát sinh', $expenses]);
            fputcsv($file, ['Lợi nhuận ròng', $netProfit]);
            fputcsv($file, ['Số đơn hàng hoàn thành', $orders->count()]);
            fputcsv($file, []);

            // Chi tiết đơn hàng
            fputcsv($file, [
                'Mã đơn hàng',
                'Khách hàng',
                'Số điện thoại',
                'Ngày hoàn thành',
                'Tổng tiền hàng',
                'Phương thức thanh toán',
                'Trạng thái thanh toán',
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->completed_at,
                    $order->subtotal,
                    $order->payment_method,
                    $order->payment_status,
                ]);
            }

            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}