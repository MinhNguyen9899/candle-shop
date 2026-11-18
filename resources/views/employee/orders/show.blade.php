<x-app-layout>
    @php
        $orderStatusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
        ];

        $statusColors = [
            'pending' => ['#fff7e8', '#a66a19'],
            'confirmed' => ['#eaf2ff', '#3565a8'],
            'preparing' => ['#f1edff', '#7053a5'],
            'shipping' => ['#eaf5ff', '#397ba7'],
            'completed' => ['#e9f5ec', '#39784c'],
            'cancelled' => ['#fcebea', '#ad514a'],
        ];

        $currentStatus = $order->order_status;
        $statusColor = $statusColors[$currentStatus] ?? ['#f3eee8', '#70452f'];
    @endphp

    <div class="employee-order-detail">
        <div class="detail-container">

            {{-- Quay lại --}}
            <a href="{{ route('employee.orders.index') }}" class="back-link">
                <span>←</span> Quay lại danh sách đơn hàng
            </a>

            {{-- Tiêu đề --}}
            <div class="detail-header">
                <div>
                    <p class="eyebrow">ORDER MANAGEMENT</p>
                    <h1>Chi tiết đơn hàng</h1>
                    <p class="order-code">
                        Mã đơn hàng: <strong>{{ $order->order_code }}</strong>
                    </p>
                </div>

                <div class="header-status"
                     style="background: {{ $statusColor[0] }}; color: {{ $statusColor[1] }};">
                    {{ $orderStatusLabels[$currentStatus] ?? $currentStatus }}
                </div>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="notice notice-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="notice notice-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="notice notice-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Thông tin người nhận --}}
            <section class="detail-card">
                <div class="section-heading">
                    <div class="section-icon">01</div>
                    <div>
                        <h2>Thông tin người nhận</h2>
                        <p>Thông tin liên hệ và địa chỉ giao hàng</p>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span>Họ tên người nhận</span>
                        <strong>{{ $order->customer_name }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Số điện thoại</span>
                        <strong>{{ $order->customer_phone }}</strong>
                    </div>

                    <div class="info-item info-full">
                        <span>Địa chỉ giao hàng</span>
                        <strong>{{ $order->shipping_address }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Khách hàng đăng ký</span>
                        <strong>{{ $order->customer?->name ?? 'Đơn nhập từ bên ngoài' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Nhân viên phụ trách</span>
                        <strong>{{ $order->staff?->name ?? 'Chưa phân công' }}</strong>
                    </div>
                </div>
            </section>

            {{-- Sản phẩm --}}
            <section class="detail-card">
                <div class="section-heading">
                    <div class="section-icon">02</div>
                    <div>
                        <h2>Sản phẩm trong đơn hàng</h2>
                        <p>Danh sách sản phẩm và giá trị đơn hàng</p>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Mã SKU</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->details as $detail)
                                <tr>
                                    <td>
                                        <div class="product-name">
                                            {{ $detail->product_name }}
                                        </div>
                                        <div class="product-description">
                                            Sản phẩm trong danh mục cửa hàng
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        {{ $detail->sku }}
                                    </td>

                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format((float) $detail->unit_price, 0, ',', '.') }} đ
                                    </td>

                                    <td class="text-right price-cell">
                                        {{ number_format((float) $detail->subtotal, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Tổng tiền --}}
                <div class="order-total">
                    <div class="total-row">
                        <span>Tiền hàng</span>
                        <strong>{{ number_format((float) $order->subtotal, 0, ',', '.') }} đ</strong>
                    </div>

                    <div class="total-row">
                        <span>Phí vận chuyển</span>
                        <strong>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }} đ</strong>
                    </div>

                    <div class="total-row grand-total">
                        <span>Tổng thanh toán</span>
                        <strong>{{ number_format((float) $order->total_amount, 0, ',', '.') }} đ</strong>
                    </div>
                </div>
            </section>

            {{-- Thông tin xử lý đơn hàng --}}
            <section class="detail-card">
                <div class="section-heading">
                    <div class="section-icon">03</div>
                    <div>
                        <h2>Thông tin xử lý đơn hàng</h2>
                        <p>Trạng thái, thanh toán và lịch sử xử lý</p>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span>Trạng thái đơn hàng</span>
                        <strong style="color: {{ $statusColor[1] }};">
                            {{ $orderStatusLabels[$currentStatus] ?? $currentStatus }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span>Phương thức thanh toán</span>
                        <strong>{{ strtoupper($order->payment_method) }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Trạng thái thanh toán</span>
                        @if ($order->payment_status === 'paid')
                            <strong class="text-success">Đã thanh toán</strong>
                        @else
                            <strong class="text-warning">
                                Chưa thanh toán
                                @if ($order->payment_method === 'cod' && $order->order_status === 'completed')
                                    · Chờ đối soát COD
                                @endif
                            </strong>
                        @endif
                    </div>

                    <div class="info-item">
                        <span>Ngày đặt hàng</span>
                        <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </div>

                    <div class="info-item info-full">
                        <span>Ghi chú của khách hàng</span>
                        <strong>{{ $order->note ?: 'Không có ghi chú' }}</strong>
                    </div>

                    @if ($order->confirmed_at)
                        <div class="info-item">
                            <span>Ngày xác nhận</span>
                            <strong>{{ $order->confirmed_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif

                    @if ($order->completed_at)
                        <div class="info-item">
                            <span>Ngày giao hàng thành công</span>
                            <strong>{{ $order->completed_at->format('d/m/Y H:i') }}</strong>
                        </div>

                        <div class="info-item">
                            <span>Nhân viên xác nhận giao hàng</span>
                            <strong>{{ $order->completedBy?->name ?? 'Chưa ghi nhận' }}</strong>
                        </div>
                    @endif

                    @if ($order->paid_at)
                        <div class="info-item">
                            <span>Ngày cửa hàng nhận tiền COD</span>
                            <strong>{{ $order->paid_at->format('d/m/Y H:i') }}</strong>
                        </div>

                        <div class="info-item">
                            <span>Nhân viên xác nhận thanh toán</span>
                            <strong>{{ $order->paidBy?->name ?? 'Chưa ghi nhận' }}</strong>
                        </div>
                    @endif

                    @if ($order->cancelled_at)
                        <div class="info-item">
                            <span>Ngày hủy đơn</span>
                            <strong>{{ $order->cancelled_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>

                @if (
                    $order->payment_method === 'cod' &&
                    $order->order_status === 'completed' &&
                    $order->payment_status === 'unpaid'
                )
                    <div class="cod-notice">
                        <strong>Đơn hàng đã giao thành công – đang chờ đối soát COD</strong>
                        <p>
                            Cửa hàng đang chờ đơn vị vận chuyển đối soát và chuyển tiền.
                            Chỉ xác nhận thanh toán khi cửa hàng thực tế đã nhận được tiền.
                        </p>
                    </div>
                @endif
            </section>

            {{-- Khu vực xử lý đơn hàng --}}
            <section class="action-card">
                <div class="section-heading">
                    <div class="section-icon">04</div>
                    <div>
                        <h2>Xử lý đơn hàng</h2>
                        <p>Thực hiện bước tiếp theo trong quy trình xử lý</p>
                    </div>
                </div>

                <div class="action-content">

                    {{-- Đơn đang chờ xác nhận --}}
                    @if ($order->order_status === 'pending')
                        <div class="action-description">
                            Đơn hàng đang chờ cửa hàng xác nhận. Kiểm tra thông tin và tồn kho trước khi tiếp tục.
                        </div>

                        <div class="action-buttons">
                            <form action="{{ route('employee.orders.confirm', $order->id) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="action-button button-success"
                                        onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?')">
                                    Xác nhận đơn hàng
                                </button>
                            </form>

                            <form action="{{ route('employee.orders.cancel', $order->id) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="action-button button-danger"
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                    Hủy đơn hàng
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Đã xác nhận: chuẩn bị hàng --}}
                    @if ($order->order_status === 'confirmed')
                        <div class="action-description">
                            Đơn hàng đã được xác nhận. Nhân viên có thể bắt đầu chuẩn bị sản phẩm để giao cho khách.
                        </div>

                        <form action="{{ route('employee.orders.update-status', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="preparing">

                            <button type="submit" class="action-button button-primary">
                                Bắt đầu chuẩn bị hàng →
                            </button>
                        </form>
                    @endif

                    {{-- Đang chuẩn bị: chuyển giao hàng --}}
                    @if ($order->order_status === 'preparing')
                        <div class="action-description">
                            Đơn hàng đang được chuẩn bị. Sau khi đóng gói và bàn giao cho đơn vị vận chuyển, cập nhật trạng thái giao hàng.
                        </div>

                        <form action="{{ route('employee.orders.update-status', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="shipping">

                            <button type="submit" class="action-button button-purple">
                                Chuyển sang đang giao →
                            </button>
                        </form>
                    @endif

                    {{-- Đang giao: xác nhận đã giao --}}
                    @if ($order->order_status === 'shipping')
                        <div class="action-description">
                            Chỉ xác nhận khi khách hàng đã thực tế nhận được hàng.
                        </div>

                        <form action="{{ route('employee.orders.update-status', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="completed">

                            <button type="submit" class="action-button button-success"
                                    onclick="return confirm('Xác nhận khách hàng đã nhận được hàng?')">
                                Xác nhận khách đã nhận hàng
                            </button>
                        </form>
                    @endif

                    {{-- Đã giao nhưng chưa nhận tiền COD --}}
                    @if (
                        $order->order_status === 'completed' &&
                        $order->payment_status === 'unpaid' &&
                        $order->payment_method === 'cod'
                    )
                        <div class="cod-action-notice">
                            <strong>Đơn hàng đã giao thành công</strong>
                            <p>
                                Cửa hàng đang chờ đơn vị vận chuyển đối soát và chuyển tiền COD.
                                Chỉ xác nhận sau khi đã thực tế nhận đủ tiền.
                            </p>

                            <form action="{{ route('employee.orders.mark-paid', $order->id) }}"
                                  method="POST">
                                @csrf

                                <button type="submit" class="action-button button-success"
                                        onclick="return confirm('Xác nhận cửa hàng đã thực tế nhận đủ tiền COD từ đơn vị vận chuyển?')">
                                    Xác nhận đã nhận tiền COD
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Trạng thái kết thúc --}}
                    @if ($order->order_status === 'cancelled')
                        <div class="final-state final-cancelled">
                            Đơn hàng đã bị hủy.
                        </div>
                    @elseif (
                        $order->order_status === 'completed' &&
                        $order->payment_status === 'paid'
                    )
                        <div class="final-state final-completed">
                            Đơn hàng đã giao thành công và cửa hàng đã nhận đủ tiền COD.
                        </div>
                    @elseif (
                        $order->order_status === 'completed' &&
                        $order->payment_status === 'unpaid'
                    )
                        <div class="final-state final-waiting">
                            Đơn hàng đã giao cho khách. Đang chờ cửa hàng nhận tiền COD.
                        </div>
                    @endif

                </div>
            </section>

        </div>
    </div>

    <style>
        .employee-order-detail {
            background: #F7F2EA;
            min-height: 100vh;
            padding: 35px 20px 65px;
            color: #30241E;
            font-family: 'DM Sans', sans-serif;
        }

        .employee-order-detail * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        .detail-container {
            max-width: 1180px;
            margin: auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #70452F;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .back-link:hover {
            color: #B79A73;
        }

        .back-link span {
            font-size: 20px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .eyebrow {
            color: #A38B70;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .detail-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: #30241E;
            margin: 0;
        }

        .order-code {
            color: #887B70;
            font-size: 14px;
            margin-top: 8px;
        }

        .order-code strong {
            color: #70452F;
        }

        .header-status {
            padding: 10px 17px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .notice {
            padding: 14px 17px;
            border-radius: 10px;
            margin-bottom: 17px;
            font-size: 14px;
            line-height: 1.7;
        }

        .notice-success {
            background: #edf5eb;
            color: #39784c;
            border: 1px solid #d5e8d3;
        }

        .notice-error {
            background: #fbeeed;
            color: #ad514a;
            border: 1px solid #efd5d2;
        }

        .detail-card,
        .action-card {
            background: #fff;
            border: 1px solid #E8DED2;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(65, 45, 30, .035);
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 23px;
        }

        .section-icon {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: #F7F2EA;
            color: #987653;
            font-size: 12px;
            font-weight: 800;
        }

        .section-heading h2 {
            font-size: 18px;
            font-weight: 700;
            color: #30241E;
            margin: 0;
        }

        .section-heading p {
            font-size: 12px;
            color: #9A8D81;
            margin: 4px 0 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px 30px;
        }

        .info-item {
            min-width: 0;
        }

        .info-item span {
            display: block;
            color: #9A8D81;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .info-item strong {
            display: block;
            color: #49382C;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.65;
            overflow-wrap: anywhere;
        }

        .info-full {
            grid-column: 1 / -1;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .product-table {
            width: 100%;
            min-width: 690px;
            border-collapse: collapse;
        }

        .product-table thead {
            background: #F8F5F0;
        }

        .product-table th {
            padding: 14px 12px;
            color: #806B57;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .product-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #F0EAE3;
            color: #594B40;
            font-size: 13px;
        }

        .product-name {
            color: #49382C;
            font-size: 14px;
            font-weight: 700;
        }

        .product-description {
            color: #9A8D81;
            font-size: 12px;
            margin-top: 5px;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
            white-space: nowrap;
        }

        .price-cell {
            color: #70452F !important;
            font-weight: 700;
        }

        .order-total {
            max-width: 390px;
            margin: 22px 0 0 auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 9px 0;
            color: #887B70;
            font-size: 13px;
        }

        .total-row strong {
            color: #49382C;
            font-weight: 600;
        }

        .grand-total {
            border-top: 1px solid #E8DED2;
            margin-top: 9px;
            padding-top: 17px;
            color: #49382C;
            font-size: 15px;
            font-weight: 700;
        }

        .grand-total strong {
            color: #A47748;
            font-size: 20px;
            font-weight: 800;
        }

        .text-success {
            color: #39784c !important;
        }

        .text-warning {
            color: #A66A19 !important;
        }

        .cod-notice,
        .cod-action-notice {
            margin-top: 22px;
            padding: 17px 19px;
            background: #FFFAEF;
            border: 1px solid #EAD9B5;
            border-radius: 11px;
        }

        .cod-notice strong,
        .cod-action-notice strong {
            color: #8B642F;
            font-size: 14px;
        }

        .cod-notice p,
        .cod-action-notice p {
            color: #8A765A;
            font-size: 13px;
            line-height: 1.7;
            margin-top: 7px;
        }

        .action-card {
            border-color: #E3D4C2;
        }

        .action-content {
            border-top: 1px solid #F0EAE3;
            padding-top: 20px;
        }

        .action-description {
            color: #887B70;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 17px;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 11px;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 11px 20px;
            border: none;
            border-radius: 9px;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
            filter: brightness(.94);
        }

        .button-success {
            background: #587A5D;
        }

        .button-danger {
            background: #A9544D;
        }

        .button-primary {
            background: #806247;
        }

        .button-purple {
            background: #79639B;
        }

        .final-state {
            padding: 15px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.6;
        }

        .final-cancelled {
            background: #FBEFED;
            color: #A9544D;
        }

        .final-completed {
            background: #EDF5EB;
            color: #39784c;
        }

        .final-waiting {
            background: #FFFAEF;
            color: #9A702E;
        }

        @media (max-width: 640px) {
            .employee-order-detail {
                padding: 25px 13px 45px;
            }

            .detail-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .detail-header h1 {
                font-size: 25px;
            }

            .detail-card,
            .action-card {
                padding: 18px 15px;
                border-radius: 13px;
            }

            .section-heading h2 {
                font-size: 16px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 17px;
            }

            .info-full {
                grid-column: auto;
            }

            .order-total {
                max-width: 100%;
            }

            .action-buttons {
                align-items: stretch;
                flex-direction: column;
            }

            .action-buttons form,
            .action-buttons .action-button,
            .action-content > form,
            .action-content > form .action-button,
            .cod-action-notice form,
            .cod-action-notice .action-button {
                width: 100%;
            }

            .grand-total strong {
                font-size: 17px;
            }
        }
    </style>
</x-app-layout>
