<x-app-layout>
    <style>
        .orders-page {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 42px 22px 70px;
            font-family: 'DM Sans', sans-serif;
        }

        .orders-container {
            max-width: 1200px;
            margin: auto;
        }

        .orders-heading {
            margin-bottom: 30px;
        }

        .orders-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 9px;
        }

        .orders-heading h1 {
            color: #30241E;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -.7px;
        }

        .orders-heading p:last-child {
            color: #887B70;
            font-size: 14px;
            margin-top: 8px;
        }

        .orders-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(48, 36, 30, .035);
        }

        .orders-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        .orders-table thead {
            background: #FBF9F5;
        }

        .orders-table th {
            padding: 17px 20px;
            color: #887B70;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-align: left;
            white-space: nowrap;
        }

        .orders-table th.text-right,
        .orders-table td.text-right {
            text-align: right;
        }

        .orders-table th.text-center,
        .orders-table td.text-center {
            text-align: center;
        }

        .orders-table td {
            padding: 19px 20px;
            border-top: 1px solid #F0EBE5;
            color: #66594D;
            font-size: 13px;
            vertical-align: middle;
        }

        .orders-table tbody tr {
            transition: background .2s;
        }

        .orders-table tbody tr:hover {
            background: #FDFBF8;
        }

        .order-code {
            color: #49382C !important;
            font-weight: 700;
        }

        .order-date {
            color: #887B70 !important;
            white-space: nowrap;
        }

        .order-total {
            color: #49382C !important;
            font-weight: 700;
            white-space: nowrap;
        }

        .order-status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 30px;
            font-size: 11px;
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

        .order-detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 85px;
            padding: 9px 15px;
            background: #49382C;
            color: #FFFFFF;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            transition: .2s;
        }

        .order-detail-btn:hover {
            background: #70452F;
            transform: translateY(-1px);
        }

        .orders-pagination {
            padding: 20px;
            border-top: 1px solid #F0EBE5;
        }

        .orders-empty {
            padding: 75px 20px;
            text-align: center;
        }

        .orders-empty h2 {
            color: #30241E;
            font-size: 20px;
            font-weight: 700;
        }

        .orders-empty p {
            color: #887B70;
            font-size: 13px;
            margin-top: 10px;
        }

        .orders-shop-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 23px;
            padding: 12px 23px;
            background: #49382C;
            color: #FFFFFF;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .orders-shop-btn:hover {
            background: #70452F;
        }

        .orders-success {
            padding: 14px 18px;
            margin-bottom: 20px;
            border: 1px solid #D7E9D9;
            border-radius: 9px;
            background: #EDF7EE;
            color: #39784A;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .orders-page {
                padding: 28px 14px 45px;
            }

            .orders-heading h1 {
                font-size: 25px;
            }

            .orders-empty {
                padding: 55px 18px;
            }
        }
    </style>

    <div class="orders-page">
        <div class="orders-container">

            {{-- Tiêu đề --}}
            <div class="orders-heading">
                <p class="orders-eyebrow">MY ACCOUNT</p>
                <h1>Lịch sử đơn hàng</h1>
                <p>Theo dõi và quản lý các đơn hàng bạn đã đặt.</p>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="orders-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Danh sách đơn hàng --}}
            <div class="orders-card">

                @if ($orders->count() > 0)

                    <div class="orders-table-wrap">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>MÃ ĐƠN HÀNG</th>
                                    <th>NGÀY ĐẶT</th>
                                    <th class="text-right">TỔNG TIỀN</th>
                                    <th class="text-center">TRẠNG THÁI</th>
                                    <th class="text-center">THAO TÁC</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $order)
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

                                        $statusClass = [
                                            'pending' => 'status-pending',
                                            'confirmed' => 'status-confirmed',
                                            'preparing' => 'status-preparing',
                                            'shipping' => 'status-shipping',
                                            'completed' => 'status-completed',
                                            'cancelled' => 'status-cancelled',
                                        ][$status] ?? '';
                                    @endphp

                                    <tr>
                                        <td class="order-code">
                                            {{ $order->order_code }}
                                        </td>

                                        <td class="order-date">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="order-total text-right">
                                            {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                        </td>

                                        <td class="text-center">
                                            <span class="order-status {{ $statusClass }}">
                                                {{ $statusLabels[$status] ?? $status }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a
                                                href="{{ route('customer.orders.show', $order->id) }}"
                                                class="order-detail-btn"
                                            >
                                                Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang --}}
                    <div class="orders-pagination">
                        {{ $orders->links() }}
                    </div>

                @else

                    <div class="orders-empty">
                        <h2>Bạn chưa có đơn hàng nào</h2>

                        <p>
                            Hãy khám phá các sản phẩm nến thơm của cửa hàng nhé.
                        </p>

                        <a
                            href="{{ route('customer.products.index') }}"
                            class="orders-shop-btn"
                        >
                            KHÁM PHÁ SẢN PHẨM
                        </a>
                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
