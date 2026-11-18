
<x-app-layout>
    <style>
        .order-detail-page {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 40px 22px 70px;
            font-family: 'DM Sans', sans-serif;
        }

        .order-detail-container {
            max-width: 1120px;
            margin: auto;
        }

        .order-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #8B684A;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 24px;
            transition: .2s;
        }

        .order-back:hover {
            color: #30241E;
        }

        .order-detail-heading {
            margin-bottom: 28px;
        }

        .order-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 8px;
        }

        .order-detail-heading h1 {
            color: #30241E;
            font-size: 29px;
            font-weight: 700;
            letter-spacing: -.6px;
        }

        .order-code-text {
            color: #887B70;
            font-size: 13px;
            margin-top: 8px;
        }

        .order-code-text strong {
            color: #49382C;
        }

        .order-detail-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(48, 36, 30, .03);
        }

        .order-card-title {
            color: #30241E;
            font-size: 17px;
            font-weight: 700;
            padding-bottom: 17px;
            margin-bottom: 20px;
            border-bottom: 1px solid #E8DED2;
        }

        .order-status-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-status-caption {
            color: #887B70;
            font-size: 12px;
        }

        .order-status-name {
            color: #49382C;
            font-size: 19px;
            font-weight: 700;
            margin-top: 5px;
        }

        .order-status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-pending {
            background: #FFF4E8;
            color: #B65C18;
        }

        .status-confirmed {
            background: #EEF3FA;
            color: #35618F;
        }

        .status-preparing {
            background: #F3EFFA;
            color: #7653A6;
        }

        .status-shipping {
            background: #EAF5F6;
            color: #247B83;
        }

        .status-completed {
            background: #EAF5EC;
            color: #39784A;
        }

        .status-cancelled {
            background: #FBEDEC;
            color: #B5473C;
        }

        .order-date {
            color: #887B70;
            font-size: 12px;
            margin-top: 17px;
        }

        .order-cancel-area {
            display: flex;
            justify-content: flex-end;
            margin: 0 0 20px;
        }

        .order-cancel-btn {
            padding: 11px 20px;
            border: 1px solid #E7B9B5;
            border-radius: 8px;
            background: #FFFFFF;
            color: #B5473C;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .order-cancel-btn:hover {
            background: #FBEDEC;
        }

        .order-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .order-table {
            width: 100%;
            min-width: 570px;
            border-collapse: collapse;
        }

        .order-table thead {
            background: #FBF9F5;
        }

        .order-table th {
            padding: 14px 15px;
            color: #887B70;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .order-table td {
            padding: 16px 15px;
            border-bottom: 1px solid #F0EBE5;
            color: #66594D;
            font-size: 13px;
        }

        .order-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-table .text-center {
            text-align: center;
        }

        .order-table .text-right {
            text-align: right;
            white-space: nowrap;
        }

        .order-product-name {
            color: #49382C;
            font-weight: 700;
        }

        .order-quantity {
            color: #66594D;
            font-weight: 600;
        }

        .order-line-total {
            color: #49382C !important;
            font-weight: 700;
        }

        .order-empty {
            padding: 22px;
            color: #887B70;
            text-align: center;
            font-size: 13px;
        }

        .order-payment-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
            color: #887B70;
            font-size: 13px;
        }

        .order-payment-row strong {
            color: #49382C;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding-top: 19px;
            margin-top: 20px;
            border-top: 1px solid #E8DED2;
        }

        .order-total-row span {
            color: #30241E;
            font-size: 14px;
            font-weight: 700;
        }

        .order-total-row strong {
            color: #A46D3E;
            font-size: 23px;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-cod-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 13px 15px;
            margin-top: 18px;
            border-radius: 8px;
            background: #FBF9F5;
            color: #887B70;
            font-size: 12px;
            line-height: 1.7;
        }

        .order-info-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            color: #66594D;
            font-size: 13px;
            line-height: 1.7;
        }

        .order-info-row:last-child {
            margin-bottom: 0;
        }

        .order-info-row strong {
            min-width: 115px;
            color: #49382C;
            font-weight: 700;
        }

        .order-alert {
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .order-alert-success {
            background: #EDF7EE;
            border: 1px solid #D7E9D9;
            color: #39784A;
        }

        .order-alert-error {
            background: #FBEDEC;
            border: 1px solid #F2D0CD;
            color: #B5473C;
        }

        @media (max-width: 600px) {
            .order-detail-page {
                padding: 28px 14px 45px;
            }

            .order-detail-heading h1 {
                font-size: 25px;
            }

            .order-detail-card {
                padding: 19px;
            }

            .order-status-name {
                font-size: 17px;
            }

            .order-total-row strong {
                font-size: 19px;
            }

            .order-info-row {
                flex-direction: column;
                gap: 2px;
            }
        }
    </style>

    <div class="order-detail-page">
        <div class="order-detail-container">

            {{-- Tiêu đề --}}
            <a href="{{ route('customer.orders.index') }}" class="order-back">
                ← Quay lại lịch sử đơn hàng
            </a>

            <div class="order-detail-heading">
                <p class="order-eyebrow">ORDER INFORMATION</p>
                <h1>Chi tiết đơn hàng</h1>
                <p class="order-code-text">
                    Mã đơn hàng:
                    <strong>{{ $order->order_code }}</strong>
                </p>
            </div>

            {{-- Thông báo kết quả --}}
            @if (session('success'))
                <div class="order-alert order-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="order-alert order-alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @php
                $statusLabels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'preparing' => 'Đang chuẩn bị',
                    'shipping' => 'Đang giao hàng',
                    'completed' => 'Đã giao hàng',
                    'cancelled' => 'Đã hủy',
                ];

                $status = $order->order_status;
                $statusLabel = $statusLabels[$status] ?? $status;

                $statusClass = [
                    'pending' => 'status-pending',
                    'confirmed' => 'status-confirmed',
                    'preparing' => 'status-preparing',
                    'shipping' => 'status-shipping',
                    'completed' => 'status-completed',
                    'cancelled' => 'status-cancelled',
                ][$status] ?? '';
            @endphp

            {{-- Trạng thái đơn hàng --}}
            <div class="order-detail-card">
                <div class="order-status-card">
                    <div>
                        <p class="order-status-caption">Trạng thái hiện tại</p>
                        <h2 class="order-status-name">{{ $statusLabel }}</h2>
                    </div>

                    <span class="order-status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <p class="order-date">
                    Ngày đặt hàng:
                    {{ $order->created_at->format('H:i - d/m/Y') }}
                </p>
            </div>

            {{-- Chức năng hủy đơn --}}
            @if ($order->order_status === 'pending')
                <div class="order-cancel-area">
                    <form
                        action="{{ route('customer.orders.cancel', $order->id) }}"
                        method="POST"
                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');"
                    >
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="order-cancel-btn">
                            HỦY ĐƠN HÀNG
                        </button>
                    </form>
                </div>
            @endif

            {{-- Sản phẩm trong đơn --}}
            <div class="order-detail-card">
                <h2 class="order-card-title">Sản phẩm đã đặt</h2>

                <div class="order-table-wrap">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>SẢN PHẨM</th>
                                <th class="text-center">SỐ LƯỢNG</th>
                                <th class="text-right">ĐƠN GIÁ</th>
                                <th class="text-right">THÀNH TIỀN</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($order->details as $detail)
                                <tr>
                                    <td class="order-product-name">
                                        <div>
                                            {{ $detail->product_name
                                                ?? $detail->product?->name
                                                ?? 'Sản phẩm không xác định' }}
                                        </div>

                                        @if ($detail->sku)
                                            <div style="font-size: 12px; color: #887B70; margin-top: 4px;">
                                                SKU: {{ $detail->sku }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center order-quantity">
                                        {{ $detail->quantity }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format($detail->unit_price, 0, ',', '.') }} đ
                                    </td>

                                    <td class="text-right order-line-total">
                                        {{ number_format($detail->quantity * $detail->unit_price, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="order-empty">
                                        Không có sản phẩm trong đơn hàng.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Thanh toán --}}
            <div class="order-detail-card">
                <h2 class="order-card-title">Thông tin thanh toán</h2>

                <div class="order-payment-row">
                    <span>Tiền hàng</span>
                    <strong>
                        {{ number_format($order->subtotal, 0, ',', '.') }} đ
                    </strong>
                </div>

                <div class="order-payment-row">
                    <span>Phí vận chuyển</span>
                    <strong>
                        {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} đ
                    </strong>
                </div>

                <div class="order-total-row">
                    <span>Tổng thanh toán</span>
                    <strong>
                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                    </strong>
                </div>

                <div class="order-cod-note">
                    <span>
                        Phương thức thanh toán: <strong>COD</strong> – Thanh toán trực tiếp cho nhân viên giao hàng khi nhận sản phẩm.
                    </span>
                </div>
            </div>

            {{-- Thông tin nhận hàng --}}
            <div class="order-detail-card">
                <h2 class="order-card-title">Thông tin nhận hàng</h2>

                <div class="order-info-row">
                    <strong>Người nhận</strong>
                    <span>{{ $order->customer_name ?? 'Chưa có thông tin' }}</span>
                </div>

                <div class="order-info-row">
                    <strong>Số điện thoại</strong>
                    <span>{{ $order->customer_phone ?? 'Chưa có thông tin' }}</span>
                </div>

                <div class="order-info-row">
                    <strong>Địa chỉ giao hàng</strong>
                    <span>{{ $order->shipping_address ?? 'Chưa có thông tin' }}</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
