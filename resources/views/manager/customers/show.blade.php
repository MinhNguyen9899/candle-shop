<x-app-layout>
    <div style="background:#F8F5F0; min-height:100vh; padding:35px 24px 60px;">
        <div style="max-width:1100px; margin:auto;">

            <a href="{{ route('manager.customers.index') }}"
               style="display:inline-block; color:#806D5D; text-decoration:none; margin-bottom:22px;">
                ← Quay lại danh sách khách hàng
            </a>

            @if(session('success'))
                <div style="background:#E5F2E8; color:#39734B; padding:13px 16px; border-radius:9px; margin-bottom:20px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TIÊU ĐỀ --}}
            <div style="margin-bottom:25px;">
                <p style="font-size:12px; letter-spacing:2px; color:#A18B75; font-weight:600;">
                    QUẢN LÝ KHÁCH HÀNG
                </p>
                <h1 style="font-family:Georgia,serif; font-size:30px; color:#49382D; margin-top:7px;">
                    {{ $customer->name }}
                </h1>
            </div>

            {{-- 1. THÔNG TIN CÁ NHÂN --}}
            <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:24px; margin-bottom:20px;">

                <h2 style="font-size:18px; font-weight:700; color:#594435; margin-bottom:22px;">
                    Thông tin khách hàng
                </h2>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:22px;">

                    <div>
                        <p style="font-size:12px; color:#9A8B7D;">Họ và tên</p>
                        <p style="margin-top:6px; color:#49382D; font-weight:600;">
                            {{ $customer->name }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px; color:#9A8B7D;">Email</p>
                        <p style="margin-top:6px; color:#49382D;">
                            {{ $customer->email }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px; color:#9A8B7D;">Số điện thoại</p>
                        <p style="margin-top:6px; color:#49382D;">
                            {{ $customer->phone ?: 'Chưa cập nhật' }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px; color:#9A8B7D;">Ngày đăng ký</p>
                        <p style="margin-top:6px; color:#49382D;">
                            {{ $customer->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div style="grid-column:1/-1;">
                        <p style="font-size:12px; color:#9A8B7D;">Địa chỉ mặc định</p>
                        <p style="margin-top:6px; color:#49382D;">
                            {{ $customer->default_shipping_address ?: 'Chưa cập nhật' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- 2. TRẠNG THÁI TÀI KHOẢN --}}
            <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:24px; margin-bottom:30px;">

                <div style="display:flex; align-items:center; justify-content:space-between; gap:15px; flex-wrap:wrap;">

                    <div>
                        <h2 style="font-size:18px; font-weight:700; color:#594435;">
                            Trạng thái tài khoản
                        </h2>

                        <p style="font-size:13px; color:#918174; margin-top:8px;">
                            Quản lý quyền truy cập hệ thống của khách hàng này.
                        </p>

                        <div style="margin-top:13px;">
                            @if($customer->status === 'active')
                                <span style="display:inline-block; background:#E5F2E8; color:#39734B; padding:7px 13px; border-radius:20px; font-size:12px; font-weight:700;">
                                    Đang hoạt động
                                </span>
                            @else
                                <span style="display:inline-block; background:#F5E8E5; color:#A35446; padding:7px 13px; border-radius:20px; font-size:12px; font-weight:700;">
                                    Đã khóa
                                </span>
                            @endif
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('manager.customers.toggle-status', $customer->id) }}"
                          onsubmit="return confirm('{{ $customer->status === 'active' ? 'M có chắc muốn khóa tài khoản khách hàng này?' : 'M có chắc muốn mở khóa tài khoản khách hàng này?' }}')">

                        @csrf
                        @method('PATCH')

                        @if($customer->status === 'active')
                            <button type="submit"
                                style="border:none; cursor:pointer; padding:12px 22px; border-radius:8px; font-size:13px; font-weight:700; background:#F5E8E5; color:#A35446;">
                                Khóa tài khoản
                            </button>
                        @else
                            <button type="submit"
                                style="border:none; cursor:pointer; padding:12px 22px; border-radius:8px; font-size:13px; font-weight:700; background:#E5F2E8; color:#39734B;">
                                Mở khóa tài khoản
                            </button>
                        @endif

                    </form>

                </div>
            </div>

            {{-- 3. LỊCH SỬ MUA HÀNG --}}
            <div style="margin-bottom:18px;">
                <p style="font-size:12px; letter-spacing:2px; color:#A18B75; font-weight:600;">
                    LỊCH SỬ GIAO DỊCH
                </p>
                <h2 style="font-family:Georgia,serif; font-size:24px; color:#49382D; margin-top:7px;">
                    Lịch sử mua hàng
                </h2>
            </div>

            {{-- THỐNG KÊ MUA HÀNG --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-bottom:22px;">

                <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:20px;">
                    <p style="font-size:13px; color:#918174;">Tổng số đơn hàng</p>
                    <p style="font-size:26px; font-weight:700; color:#594435; margin-top:8px;">
                        {{ number_format($totalOrders) }}
                    </p>
                </div>

                <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:20px;">
                    <p style="font-size:13px; color:#918174;">Đơn hoàn thành</p>
                    <p style="font-size:26px; font-weight:700; color:#39734B; margin-top:8px;">
                        {{ number_format($completedOrders) }}
                    </p>
                </div>

                <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:20px;">
                    <p style="font-size:13px; color:#918174;">Tổng chi tiêu</p>
                    <p style="font-size:24px; font-weight:700; color:#76563F; margin-top:8px;">
                        {{ number_format($totalSpent, 0, ',', '.') }} đ
                    </p>
                </div>

            </div>

            {{-- BẢNG LỊCH SỬ ĐƠN HÀNG --}}
            <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; overflow:hidden;">

                <div style="padding:20px; border-bottom:1px solid #EEE7DF;">
                    <h3 style="font-size:16px; font-weight:700; color:#594435;">
                        Danh sách đơn hàng
                    </h3>
                    <p style="font-size:13px; color:#9A8B7D; margin-top:5px;">
                        Hiển thị tối đa 20 đơn hàng gần nhất.
                    </p>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; min-width:750px; border-collapse:collapse;">

                        <thead style="background:#F7F2EC;">
                            <tr>
                                <th style="padding:14px 16px; text-align:left; color:#806D5D; font-size:12px;">Mã đơn</th>
                                <th style="padding:14px 16px; text-align:left; color:#806D5D; font-size:12px;">Ngày đặt</th>
                                <th style="padding:14px 16px; text-align:left; color:#806D5D; font-size:12px;">Sản phẩm</th>
                                <th style="padding:14px 16px; text-align:left; color:#806D5D; font-size:12px;">Tổng tiền</th>
                                <th style="padding:14px 16px; text-align:left; color:#806D5D; font-size:12px;">Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($customer->customerOrders as $order)
                                <tr style="border-top:1px solid #F0EAE3;">

                                    <td style="padding:15px 16px; color:#76563F; font-weight:600;">
                                        {{ $order->order_code ?? '#'.$order->id }}
                                    </td>

                                    <td style="padding:15px 16px; color:#75675B; font-size:13px;">
                                        {{ $order->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td style="padding:15px 16px; color:#594435; font-size:13px;">
                                        @foreach($order->details as $detail)
                                            <p style="margin-bottom:4px;">
                                                {{ $detail->product_name ?? 'Sản phẩm' }}
                                                × {{ $detail->quantity }}
                                            </p>
                                        @endforeach
                                    </td>

                                    <td style="padding:15px 16px; color:#76563F; font-weight:600; white-space:nowrap;">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                    </td>

                                    <td style="padding:15px 16px; color:#594435; font-size:13px;">
                                        {{ [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'preparing' => 'Đang chuẩn bị',
                                            'shipping' => 'Đang giao hàng',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                        ][$order->order_status] ?? $order->order_status }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding:35px; text-align:center; color:#9A8B7D;">
                                        Khách hàng chưa có đơn hàng nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>