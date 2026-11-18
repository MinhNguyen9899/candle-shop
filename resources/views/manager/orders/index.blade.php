
<x-app-layout>
    <style>
        .manager-orders-page {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 38px 22px 65px;
            font-family: 'DM Sans', sans-serif;
        }

        .manager-orders-container {
            max-width: 1350px;
            margin: auto;
        }

        .manager-orders-heading {
            margin-bottom: 25px;
        }

        .manager-orders-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 8px;
        }

        .manager-orders-heading h1 {
            color: #30241E;
            font-size: 28px;
            font-weight: 700;
        }

        .manager-orders-heading p {
            color: #887B70;
            font-size: 13px;
            margin-top: 8px;
        }

        .manager-filter-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            padding: 20px;
            margin-bottom: 22px;
        }

        .manager-filter-grid {
            display: grid;
            grid-template-columns: minmax(220px, 1.5fr) repeat(2, minmax(160px, 1fr)) auto auto;
            gap: 12px;
            align-items: end;
        }

        .manager-filter-field label {
            display: block;
            color: #49382C;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .manager-filter-field input,
        .manager-filter-field select {
            width: 100%;
            height: 43px;
            padding: 0 12px;
            border: 1px solid #E5DBD0;
            border-radius: 7px;
            background: #FFFEFC;
            color: #49382C;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            outline: none;
        }

        .manager-filter-field input:focus,
        .manager-filter-field select:focus {
            border-color: #A1846B;
            box-shadow: 0 0 0 3px rgba(161,132,107,.09);
        }

        .manager-filter-btn,
        .manager-reset-btn {
            display: inline-flex;
            height: 43px;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 7px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s;
        }

        .manager-filter-btn {
            border: 1px solid #654832;
            background: #654832;
            color: #FFFFFF;
        }

        .manager-filter-btn:hover {
            background: #493528;
        }

        .manager-reset-btn {
            border: 1px solid #E8DED2;
            background: #FBF9F5;
            color: #66594D;
        }

        .manager-reset-btn:hover {
            background: #F0E8DE;
        }

        .manager-table-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            overflow: hidden;
        }

        .manager-table-heading {
            padding: 20px 22px;
            border-bottom: 1px solid #E8DED2;
        }

        .manager-table-heading h2 {
            color: #30241E;
            font-size: 16px;
            font-weight: 700;
        }

        .manager-table-heading p {
            color: #887B70;
            font-size: 12px;
            margin-top: 5px;
        }

        .manager-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .manager-orders-table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
        }

        .manager-orders-table thead {
            background: #FBF9F5;
        }

        .manager-orders-table th {
            padding: 15px 13px;
            color: #887B70;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-align: left;
            white-space: nowrap;
        }

        .manager-orders-table td {
            padding: 16px 13px;
            border-top: 1px solid #F0EBE5;
            color: #66594D;
            font-size: 12px;
            vertical-align: middle;
        }

        .manager-orders-table tbody tr:hover {
            background: #FFFEFC;
        }

        .manager-order-code {
            color: #49382C;
            font-weight: 700;
            white-space: nowrap;
        }

        .manager-order-customer {
            color: #49382C;
            font-weight: 600;
        }

        .manager-order-phone,
        .manager-order-date {
            white-space: nowrap;
        }

        .manager-order-money {
            color: #A46D3E !important;
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }

        .manager-order-center {
            text-align: center;
        }

        .manager-status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .manager-status-pending {
            background: #FFF4E8;
            color: #B65C18;
        }

        .manager-status-confirmed {
            background: #EEF3FA;
            color: #35618F;
        }

        .manager-status-preparing {
            background: #F3EFFA;
            color: #7653A6;
        }

        .manager-status-shipping {
            background: #EAF5F6;
            color: #247B83;
        }

        .manager-status-completed {
            background: #EAF5EC;
            color: #39784A;
        }

        .manager-status-cancelled {
            background: #FBEDEC;
            color: #B5473C;
        }

        .manager-payment-paid {
            color: #39784A;
            font-weight: 700;
            white-space: nowrap;
        }

        .manager-payment-unpaid {
            color: #B65C18;
            font-weight: 600;
            white-space: nowrap;
        }

        .manager-detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 13px;
            border-radius: 6px;
            background: #654832;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s;
        }

        .manager-detail-btn:hover {
            background: #493528;
        }

        .manager-empty {
            padding: 38px 20px !important;
            color: #887B70 !important;
            text-align: center;
        }

        .manager-pagination {
            padding: 20px;
            border-top: 1px solid #F0EBE5;
        }

        @media (max-width: 1000px) {
            .manager-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .manager-filter-field:first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 600px) {
            .manager-orders-page {
                padding: 28px 14px 45px;
            }

            .manager-orders-heading h1 {
                font-size: 24px;
            }

            .manager-filter-card {
                padding: 15px;
            }

            .manager-filter-grid {
                grid-template-columns: 1fr;
            }

            .manager-filter-field:first-child {
                grid-column: auto;
            }

            .manager-filter-btn,
            .manager-reset-btn {
                width: 100%;
            }

            .manager-table-heading {
                padding: 17px;
            }
        }
    </style>

    <div class="manager-orders-page">
        <div class="manager-orders-container">

            {{-- Tiêu đề --}}
            <div class="manager-orders-heading">
                <p class="manager-orders-eyebrow">ORDER MANAGEMENT</p>
                <h1>Quản lý đơn hàng</h1>
                <p>Theo dõi tình hình đơn hàng và hoạt động bán hàng của cửa hàng.</p>
            </div>

            {{-- Tìm kiếm và lọc --}}
            <div class="manager-filter-card">
                <form method="GET" action="{{ route('manager.orders.index') }}">
                    <div class="manager-filter-grid">

                        <div class="manager-filter-field">
                            <label for="keyword">TÌM KIẾM ĐƠN HÀNG</label>
                            <input
                                id="keyword"
                                type="text"
                                name="keyword"
                                value="{{ $keyword ?? '' }}"
                                placeholder="Mã đơn, tên người nhận, số điện thoại..."
                            >
                        </div>

                        <div class="manager-filter-field">
                            <label for="status">TRẠNG THÁI ĐƠN HÀNG</label>
                            <select id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ ($status ?? '') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="preparing" {{ ($status ?? '') === 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                <option value="shipping" {{ ($status ?? '') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ ($status ?? '') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="manager-filter-field">
                            <label for="payment_status">TRẠNG THÁI THANH TOÁN</label>
                            <select id="payment_status" name="payment_status">
                                <option value="">Tất cả thanh toán</option>
                                <option value="unpaid" {{ ($paymentStatus ?? '') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ ($paymentStatus ?? '') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                        </div>

                        <button type="submit" class="manager-filter-btn">
                            TÌM KIẾM
                        </button>

                        <a href="{{ route('manager.orders.index') }}" class="manager-reset-btn">
                            LÀM MỚI
                        </a>

                    </div>
                </form>
            </div>

            {{-- Danh sách đơn hàng --}}
            <div class="manager-table-card">

                <div class="manager-table-heading">
                    <h2>Danh sách đơn hàng</h2>
                    <p>Các đơn hàng được ghi nhận trong hệ thống.</p>
                </div>

                <div class="manager-table-wrap">
                    <table class="manager-orders-table">
                        <thead>
                            <tr>
                                <th>MÃ ĐƠN</th>
                                <th>NGƯỜI NHẬN</th>
                                <th>SỐ ĐIỆN THOẠI</th>
                                <th style="text-align: right;">TỔNG TIỀN</th>
                                <th style="text-align: center;">THANH TOÁN</th>
                                <th style="text-align: center;">TRẠNG THÁI</th>
                                <th style="text-align: center;">NGÀY ĐẶT</th>
                                <th style="text-align: center;">THAO TÁC</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $statusLabels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'preparing' => 'Đang chuẩn bị',
                                        'shipping' => 'Đang giao hàng',
                                        'completed' => 'Đã giao hàng',
                                        'cancelled' => 'Đã hủy',
                                    ];

                                    $statusClasses = [
                                        'pending' => 'manager-status-pending',
                                        'confirmed' => 'manager-status-confirmed',
                                        'preparing' => 'manager-status-preparing',
                                        'shipping' => 'manager-status-shipping',
                                        'completed' => 'manager-status-completed',
                                        'cancelled' => 'manager-status-cancelled',
                                    ];
                                @endphp

                                <tr>
                                    <td>
                                        <span class="manager-order-code">
                                            {{ $order->order_code }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="manager-order-customer">
                                            {{ $order->customer_name }}
                                        </span>
                                    </td>

                                    <td class="manager-order-phone">
                                        {{ $order->customer_phone }}
                                    </td>

                                    <td class="manager-order-money">
                                        {{ number_format((float) $order->total_amount, 0, ',', '.') }} đ
                                    </td>

                                    <td class="manager-order-center">
                                        @if ($order->payment_status === 'paid')
                                            <span class="manager-payment-paid">Đã thanh toán</span>
                                        @elseif ($order->order_status === 'cancelled')
                                            <span style="color: #887B70; font-weight: 600;">Không áp dụng</span>
                                        @elseif ($order->payment_method === 'cod' && $order->order_status === 'completed')
                                            <span class="manager-payment-unpaid">Chờ đối soát COD</span>
                                        @else
                                            <span class="manager-payment-unpaid">Chưa thanh toán</span>
                                        @endif
                                    </td>

                                    <td class="manager-order-center">
                                        <span class="manager-status {{ $statusClasses[$order->order_status] ?? '' }}">
                                            {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                                        </span>
                                    </td>

                                    <td class="manager-order-date manager-order-center">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="manager-order-center">
                                        <a
                                            href="{{ route('manager.orders.show', $order->id) }}"
                                            class="manager-detail-btn"
                                        >
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="manager-empty">
                                        Chưa có đơn hàng nào phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="manager-pagination">
                    {{ $orders->appends(request()->query())->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
