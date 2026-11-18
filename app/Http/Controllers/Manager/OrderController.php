<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách toàn bộ đơn hàng.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');

        $allowedStatuses = [
            'pending',
            'confirmed',
            'preparing',
            'shipping',
            'completed',
            'cancelled',
        ];

        $allowedPaymentStatuses = [
            'unpaid',
            'paid',
        ];

        $query = Order::with(['customer', 'staff']);

        // Tìm kiếm theo mã đơn, tên người nhận, số điện thoại.
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                    ->orWhere('customer_name', 'like', "%{$keyword}%")
                    ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        }

        // Lọc trạng thái đơn hàng.
        if (in_array($status, $allowedStatuses, true)) {
            $query->where('order_status', $status);
        } else {
            $status = '';
        }

        // Lọc trạng thái thanh toán.
        if (in_array($paymentStatus, $allowedPaymentStatuses, true)) {
            $query->where('payment_status', $paymentStatus);
        } else {
            $paymentStatus = '';
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('manager.orders.index', compact(
            'orders',
            'keyword',
            'status',
            'paymentStatus'
        ));
    }

    /**
     * Xem chi tiết đơn hàng.
     */
    public function show(Order $order)
    {
        $order->load([
            'customer',
            'staff',
            'completedBy',
            'paidBy',
            'details',
        ]);

        return view('manager.orders.show', compact('order'));
    }
}
