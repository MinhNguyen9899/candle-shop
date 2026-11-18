
<x-app-layout>
    <style>
        .employee-orders-page {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 38px 22px 65px;
            font-family: 'DM Sans', sans-serif;
        }

        .employee-orders-container {
            max-width: 1350px;
            margin: auto;
        }

        .employee-orders-heading {
            margin-bottom: 25px;
        }

        .employee-orders-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 8px;
        }

        .employee-orders-heading h1 {
            color: #30241E;
            font-size: 28px;
            font-weight: 700;
        }

        .employee-orders-heading p {
            color: #887B70;
            font-size: 13px;
            margin-top: 8px;
        }

        .employee-orders-alert {
            padding: 13px 17px;
            border-radius: 8px;
            margin-bottom: 17px;
            font-size: 13px;
        }

        .employee-orders-success {
            background: #EDF7EE;
            border: 1px solid #D7E9D9;
            color: #39784A;
        }

        .employee-orders-error {
            background: #FBEDEC;
            border: 1px solid #F2D0CD;
            color: #B5473C;
        }

        .employee-filter-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            padding: 20px;
            margin-bottom: 22px;
        }

        .employee-filter-grid {
            display: grid;
            grid-template-columns: minmax(220px, 1.5fr) repeat(2, minmax(160px, 1fr)) auto auto;
            gap: 12px;
            align-items: end;
        }

        .employee-filter-field label {
            display: block;
            color: #49382C;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .employee-filter-field input,
        .employee-filter-field select {
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

        .employee-filter-field input:focus,
        .employee-filter-field select:focus {
            border-color: #A1846B;
            box-shadow: 0 0 0 3px rgba(161,132,107,.09);
        }

        .employee-filter-btn,
        .employee-reset-btn {
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

        .employee-filter-btn {
            border: 1px solid #654832;
            background: #654832;
            color: #FFFFFF;
        }

        .employee-filter-btn:hover {
            background: #493528;
        }

        .employee-reset-btn {
            border: 1px solid #E8DED2;
            background: #FBF9F5;
            color: #66594D;
        }

        .employee-reset-btn:hover {
            background: #F0E8DE;
        }

        .employee-table-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            overflow: hidden;
        }

        .employee-table-heading {
            padding: 20px 22px;
            border-bottom: 1px solid #E8DED2;
        }

        .employee-table-heading h2 {
            color: #30241E;
            font-size: 16px;
            font-weight: 700;
        }

        .employee-table-heading p {
            color: #887B70;
            font-size: 12px;
            margin-top: 5px;
        }

        .employee-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .employee-orders-table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
        }

        .employee-orders-table thead {
            background: #FBF9F5;
        }

        .employee-orders-table th {
            padding: 15px 13px;
            color: #887B70;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-align: left;
            white-space: nowrap;
        }

        .employee-orders-table td {
            padding: 16px 13px;
            border-top: 1px solid #F0EBE5;
            color: #66594D;
            font-size: 12px;
            vertical-align: middle;
        }

        .employee-orders-table tbody tr {
            transition: .15s;
        }

        .employee-orders-table tbody tr:hover {
            background: #FFFEFC;
        }

        .employee-order-code {
            color: #49382C;
            font-weight: 700;
            white-space: nowrap;
        }

        .employee-order-customer {
            color: #49382C;
            font-weight: 600;
        }

        .employee-order-phone {
            white-space: nowrap;
        }

        .employee-order-money {
            color: #A46D3E !important;
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }

        .employee-order-center {
            text-align: center;
        }

        .employee-order-date {
            white-space: nowrap;
            text-align: center;
        }

        .employee-status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .employee-status-pending {
            background: #FFF4E8;
            color: #B65C18;
        }

        .employee-status-confirmed {
            background: #EEF3FA;
            color: #35618F;
        }

        .employee-status-preparing {
            background: #F3EFFA;
            color: #7653A6;
        }

        .employee-status-shipping {
            background: #EAF5F6;
            color: #247B83;
        }

        .employee-status-completed {
            background: #EAF5EC;
            color: #39784A;
        }

        .employee-status-cancelled {
            background: #FBEDEC;
            color: #B5473C;
        }

        .employee-payment-paid {
            color: #39784A;
            font-weight: 700;
            white-space: nowrap;
        }

        .employee-payment-unpaid {
            color: #B65C18;
            font-weight: 600;
            white-space: nowrap;
        }

        .employee-detail-btn {
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

        .employee-detail-btn:hover {
            background: #493528;
        }

        .employee-empty {
            padding: 38px 20px !important;
            color: #887B70 !important;
            text-align: center;
        }

        .employee-pagination {
            padding: 20px;
            border-top: 1px solid #F0EBE5;
        }

        @media (max-width: 1000px) {
            .employee-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .employee-filter-field:first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 600px) {
            .employee-orders-page {
                padding: 28px 14px 45px;
            }

            .employee-orders-heading h1 {
                font-size: 24px;
            }

            .employee-filter-card {
                padding: 15px;
            }

            .employee-filter-grid {
                grid-template-columns: 1fr;
            }

            .employee-filter-field:first-child {
                grid-column: auto;
            }

            .employee-filter-btn,
            .employee-reset-btn {
                width: 100%;
            }

            .employee-table-heading {
                padding: 17px;
            }
        }
    </style>

    <div class="employee-orders-page">
        <div class="employee-orders-container">

            {{-- Tiêu đề --}}
            <div class="employee-orders-heading">
                <p class="employee-orders-eyebrow">ORDER MANAGEMENT</p>
                <h1>Quản lý đơn hàng</h1>
                <p>Theo dõi, xác nhận và xử lý đơn hàng của cửa hàng.</p>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="employee-orders-alert employee-orders-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="employee-orders-alert employee-orders-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tìm kiếm và lọc --}}
            <div class="employee-filter-card">
                <form method="GET" action="{{ route('employee.orders.index') }}">
                    <div class="employee-filter-grid">

                        <div class="employee-filter-field">
                            <label for="keyword">TÌM KIẾM ĐƠN HÀNG</label>
                            <input
                                id="keyword"
                                type="text"
                                name="keyword"
                                value="{{ $keyword ?? '' }}"
                                placeholder="Mã đơn, tên người nhận, số điện thoại..."
                            >
                        </div>

                        <div class="employee-filter-field">
                            <label for="status">TRẠNG THÁI ĐƠN HÀNG</label>
                            <select id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="preparing" {{ $status === 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                <option value="shipping" {{ $status === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="employee-filter-field">
                            <label for="payment_status">TRẠNG THÁI THANH TOÁN</label>
                            <select id="payment_status" name="payment_status">
                                <option value="">Tất cả thanh toán</option>
                                <option value="unpaid" {{ ($paymentStatus ?? '') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ ($paymentStatus ?? '') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                        </div>

                        <button type="submit" class="employee-filter-btn">
                            TÌM KIẾM
                        </button>

                        <a href="{{ route('employee.orders.index') }}" class="employee-reset-btn">
                            LÀM MỚI
                        </a>

                    </div>
                </form>
            </div>

            {{-- Danh sách đơn hàng --}}
            <div class="employee-table-card">

                <div class="employee-table-heading">
                    <h2>Danh sách đơn hàng</h2>
                    <p>Các đơn hàng được ghi nhận trong hệ thống.</p>
                </div>

                <div class="employee-table-wrap">
                    <table class="employee-orders-table">
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
                                        'pending' => 'employee-status-pending',
                                        'confirmed' => 'employee-status-confirmed',
                                        'preparing' => 'employee-status-preparing',
                                        'shipping' => 'employee-status-shipping',
                                        'completed' => 'employee-status-completed',
                                        'cancelled' => 'employee-status-cancelled',
                                    ];

                                    $orderStatus = $order->order_status;
                                @endphp

                                <tr>
                                    <td>
                                        <span class="employee-order-code">
                                            {{ $order->order_code }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="employee-order-customer">
                                            {{ $order->customer_name }}
                                        </span>
                                    </td>

                                    <td class="employee-order-phone">
                                        {{ $order->customer_phone }}
                                    </td>

                                    <td class="employee-order-money">
                                        {{ number_format((float) $order->total_amount, 0, ',', '.') }} đ
                                    </td>

                                    <td class="employee-order-center">
                                        @if ($order->payment_status === 'paid')
                                            <span class="employee-payment-paid">
                                                Đã thanh toán
                                            </span>
                                        @elseif ($order->order_status === 'cancelled')
                                            <span style="color: #887B70; font-weight: 600;">
                                                Không áp dụng
                                            </span>
                                        @elseif ($order->payment_method === 'cod' && $order->order_status === 'completed')
                                            <span class="employee-payment-unpaid">
                                                Chờ đối soát COD
                                            </span>
                                        @else
                                            <span class="employee-payment-unpaid">
                                                Chưa thanh toán
                                            </span>
                                        @endif
                                    </td>

                                    <td class="employee-order-center">
                                        <span class="employee-status {{ $statusClasses[$order->order_status] ?? '' }}">
                                            {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                                        </span>
                                    </td>

                                    <td class="employee-order-date">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="employee-order-center">
                                        <a
                                            href="{{ route('employee.orders.show', $order->id) }}"
                                            class="employee-detail-btn"
                                        >
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="employee-empty">
                                        Chưa có đơn hàng nào phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="employee-pagination">
                    {{ $orders->appends(request()->query())->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
