<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $allowedStatuses = [
            'pending',
            'confirmed',
            'preparing',
            'shipping',
            'completed',
            'cancelled',
        ];

        $status = in_array($request->input('status'), $allowedStatuses, true)
            ? $request->input('status')
            : '';

        $allowedPaymentStatuses = ['unpaid', 'paid'];

        $paymentStatus = in_array(
            $request->input('payment_status'),
            $allowedPaymentStatuses,
            true
        )
            ? $request->input('payment_status')
            : '';

        $orders = Order::with(['customer', 'staff'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('order_code', 'like', '%' . $keyword . '%')
                        ->orWhere('customer_name', 'like', '%' . $keyword . '%')
                        ->orWhere('customer_phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('order_status', $status);
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employee.orders.index', compact(
            'orders',
            'keyword',
            'status',
            'paymentStatus'
        ));
    }

    // Chi tiết đơn hàng
    public function show(Order $order)
    {
        $order->load([
            'customer',
            'staff',
            'completedBy',
            'paidBy',
            'details',
            'stockReservation.details.product',
        ]);

        return view('employee.orders.show', compact('order'));
    }

    // Xác nhận đơn hàng và trừ tồn kho
    public function confirm(Order $order)
    {
        $orderId = $order->id;

        DB::transaction(function () use ($orderId) {

            $lockedOrder = Order::whereKey($orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->order_status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ được xác nhận đơn hàng đang chờ xử lý.',
                ]);
            }

            $reservation = $lockedOrder->stockReservation()
                ->lockForUpdate()
                ->first();

            if (!$reservation || $reservation->status !== 'reserved') {
                throw ValidationException::withMessages([
                    'order' => 'Phiếu giữ hàng không hợp lệ hoặc đã được xử lý.',
                ]);
            }

            $reservationDetails = $reservation->details()
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get();

            if ($reservationDetails->isEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Phiếu giữ hàng không có sản phẩm.',
                ]);
            }

            foreach ($reservationDetails as $detail) {

                if (!$detail->product_id) {
                    throw ValidationException::withMessages([
                        'order' => 'Chi tiết giữ hàng chưa được liên kết với sản phẩm.',
                    ]);
                }

                $inventory = Inventory::where('product_id', $detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory) {
                    throw ValidationException::withMessages([
                        'order' => 'Không tìm thấy thông tin tồn kho của sản phẩm.',
                    ]);
                }

                if (
                    $detail->quantity <= 0 ||
                    $inventory->on_hand_qty < $detail->quantity ||
                    $inventory->reserved_qty < $detail->quantity
                ) {
                    throw ValidationException::withMessages([
                        'order' => 'Số lượng tồn kho hoặc hàng đang giữ không hợp lệ. Không thể xác nhận đơn.',
                    ]);
                }

                // Trừ tồn thực tế và giải phóng số lượng đang giữ
                $inventory->decrement('on_hand_qty', $detail->quantity);
                $inventory->decrement('reserved_qty', $detail->quantity);

                // Ghi lịch sử xuất kho bán hàng
                InventoryTransaction::create([
                    'product_id' => $detail->product_id,
                    'created_by' => auth()->id(),
                    'transaction_type' => 'sale',
                    'quantity' => $detail->quantity,
                    'reference_type' => 'order',
                    'reference_id' => $lockedOrder->id,
                    'note' => 'Xuất kho khi xác nhận đơn hàng ' . $lockedOrder->order_code,
                ]);
            }

            // Cập nhật phiếu giữ hàng
            $reservation->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // Cập nhật đơn hàng
            $lockedOrder->update([
                'order_status' => 'confirmed',
                'staff_id' => auth()->id(),
                'confirmed_at' => now(),
            ]);

        }, 3);

        return redirect()
            ->route('employee.orders.show', $orderId)
            ->with('success', 'Đã xác nhận đơn hàng và cập nhật tồn kho.');
    }

    // Hủy đơn hàng đang chờ và giải phóng hàng giữ
    public function cancel(Order $order)
    {
        $orderId = $order->id;

        DB::transaction(function () use ($orderId) {

            $lockedOrder = Order::whereKey($orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->order_status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ được hủy đơn hàng đang chờ xử lý.',
                ]);
            }

            $reservation = $lockedOrder->stockReservation()
                ->lockForUpdate()
                ->first();

            if (!$reservation || $reservation->status !== 'reserved') {
                throw ValidationException::withMessages([
                    'order' => 'Phiếu giữ hàng không hợp lệ hoặc đã được xử lý.',
                ]);
            }

            $reservationDetails = $reservation->details()
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get();
            if ($reservationDetails->isEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Phiếu giữ hàng không có sản phẩm.',
                ]);
            }

            foreach ($reservationDetails as $detail) {

                if (!$detail->product_id) {
                    throw ValidationException::withMessages([
                        'order' => 'Chi tiết giữ hàng chưa được liên kết với sản phẩm.',
                    ]);
                }

                $inventory = Inventory::where('product_id', $detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory ||
                    $detail->quantity <= 0 ||
                    $inventory->reserved_qty < $detail->quantity
                ) {
                    throw ValidationException::withMessages([
                        'order' => 'Số lượng hàng đang giữ không hợp lệ.',
                    ]);
                }

                // Chỉ giải phóng hàng giữ, không trừ tồn thực tế
                $inventory->decrement('reserved_qty', $detail->quantity);
            }

            $reservation->update([
                'status' => 'released',
                'released_at' => now(),
            ]);

            $lockedOrder->update([
                'order_status' => 'cancelled',
                'cancelled_at' => now(),
                'staff_id' => auth()->id(),
            ]);

        }, 3);

        return redirect()
            ->route('employee.orders.show', $orderId)
            ->with('success', 'Đã hủy đơn hàng và giải phóng số lượng hàng giữ.');
    }

    // Cập nhật trạng thái giao hàng
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => [
                'required',
                'in:preparing,shipping,completed',
            ],
        ]);

        $nextStatus = $validated['order_status'];
        $orderId = $order->id;

        DB::transaction(function () use ($orderId, $nextStatus) {

            $lockedOrder = Order::whereKey($orderId)
                ->lockForUpdate()
                ->firstOrFail();

            $allowedTransitions = [
                'confirmed' => 'preparing',
                'preparing' => 'shipping',
                'shipping' => 'completed',
            ];

            if (
                !isset($allowedTransitions[$lockedOrder->order_status]) ||
                $allowedTransitions[$lockedOrder->order_status] !== $nextStatus
            ) {
                throw ValidationException::withMessages([
                    'order_status' => 'Không thể chuyển đơn hàng sang trạng thái này.',
                ]);
            }

            $updateData = [
                'order_status' => $nextStatus,
                'staff_id' => auth()->id(),
            ];

            if ($nextStatus === 'completed') {
                $updateData['completed_at'] = now();
                $updateData['completed_by'] = auth()->id();
            }

            $lockedOrder->update($updateData);

        }, 3);

        return redirect()
            ->route('employee.orders.show', $orderId)
            ->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    // Xác nhận đã nhận tiền COD từ đơn vị vận chuyển
    public function markPaid(Order $order)
    {
        $orderId = $order->id;

        DB::transaction(function () use ($orderId) {

            $lockedOrder = Order::whereKey($orderId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->order_status !== 'completed') {
                throw ValidationException::withMessages([
                    'payment_status' => 'Chỉ xác nhận thanh toán khi đơn hàng đã giao thành công.',
                ]);
            }

            if ($lockedOrder->payment_status === 'paid') {
                throw ValidationException::withMessages([
                    'payment_status' => 'Đơn hàng này đã được xác nhận thanh toán.',
                ]);
            }

            if ($lockedOrder->payment_method !== 'cod') {
                throw ValidationException::withMessages([
                    'payment_status' => 'Đơn hàng này không sử dụng phương thức thanh toán COD.',
                ]);
            }

            $lockedOrder->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'paid_by' => auth()->id(),
                'staff_id' => auth()->id(),
            ]);

        }, 3);

        return redirect()
            ->route('employee.orders.show', $orderId)
            ->with('success', 'Đã xác nhận cửa hàng nhận được tiền COD từ đơn vị vận chuyển.');
    }
}
