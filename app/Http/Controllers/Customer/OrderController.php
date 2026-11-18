<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng của khách hàng đang đăng nhập.
     */
    public function index()
    {
        $orders = Order::where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng.
     */
    public function show($id)
    {
        $order = Order::where('customer_id', Auth::id())
            ->where('id', $id)
            ->with('details')
            ->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Khách hàng hủy đơn hàng đang chờ xác nhận.
     */
    public function cancel($id)
    {
        DB::transaction(function () use ($id) {

            // Khóa đơn hàng và kiểm tra quyền sở hữu.
            $order = Order::where('customer_id', Auth::id())
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // Chỉ được hủy đơn đang chờ xác nhận.
            if ($order->order_status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ có thể hủy đơn hàng đang chờ xác nhận.',
                ]);
            }

            // Lấy và khóa phiếu giữ hàng.
            $reservation = DB::table('stock_reservations')
                ->where('order_id', $order->id)
                ->lockForUpdate()
                ->first();

            if (!$reservation || $reservation->status !== 'reserved') {
                throw ValidationException::withMessages([
                    'order' => 'Không tìm thấy phiếu giữ hàng hợp lệ của đơn hàng.',
                ]);
            }

            // Lấy các chi tiết hàng đang được giữ.
            $reservationDetails = DB::table('stock_reservation_details')
                ->where('stock_reservation_id', $reservation->id)
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get();

            if ($reservationDetails->isEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Phiếu giữ hàng không có sản phẩm.',
                ]);
            }

            foreach ($reservationDetails as $detail) {

                // Kiểm tra liên kết sản phẩm theo cấu trúc mới.
                if (!$detail->product_id) {
                    throw ValidationException::withMessages([
                        'order' => 'Chi tiết giữ hàng chưa được liên kết với sản phẩm.',
                    ]);
                }

                // Khóa tồn kho theo product_id.
                $inventory = DB::table('inventories')
                    ->where('product_id', $detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$inventory ||
                    $detail->quantity <= 0 ||
                    $inventory->reserved_qty < $detail->quantity
                ) {
                    throw ValidationException::withMessages([
                        'order' => 'Số lượng hàng đang giữ không hợp lệ. Vui lòng liên hệ cửa hàng.',
                    ]);
                }

                // Chỉ giải phóng số lượng đang giữ.
                // Không thay đổi tồn kho thực tế.
                DB::table('inventories')
                    ->where('id', $inventory->id)
                    ->decrement('reserved_qty', $detail->quantity);
            }

            // Cập nhật phiếu giữ hàng.
            DB::table('stock_reservations')
                ->where('id', $reservation->id)
                ->update([
                    'status' => 'released',
                    'released_at' => now(),
                    'updated_at' => now(),
                ]);

            // Cập nhật trạng thái đơn hàng.
            $order->update([
                'order_status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

        }, 3);

        return redirect()
            ->route('customer.orders.show', $id)
            ->with(
                'success',
                'Đơn hàng đã được hủy thành công. Số lượng hàng giữ đã được hoàn lại.'
            );
    }
}
