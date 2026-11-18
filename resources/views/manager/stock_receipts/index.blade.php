
<x-app-layout>
    <div class="employee-inventory-page">
        <div class="inventory-container">

            {{-- Tiêu đề --}}
            <div class="inventory-header">
                <div>
                    <p class="inventory-eyebrow">INVENTORY MANAGEMENT</p>
                    <h1>Quản lý phiếu nhập kho</h1>
                    <p class="inventory-subtitle">
                        Theo dõi các lần nhập hàng, chi phí và thông tin nhà cung cấp.
                    </p>
                </div>

                <a href="{{ route('manager.stock-receipts.create') }}"
                   class="create-receipt-button">
                    <span>+</span>
                    Tạo phiếu nhập
                </a>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="inventory-alert">
                    <span class="alert-icon">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Danh sách phiếu nhập --}}
            <section class="inventory-table-card">

                <div class="table-heading">
                    <div>
                        <h2>Danh sách phiếu nhập kho</h2>
                        <p>
                            Quản lý lịch sử nhập hàng và chi phí nhập kho.
                        </p>
                    </div>

                    <span class="record-count">
                        {{ $receipts->total() }} phiếu nhập
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="inventory-table">

                        <thead>
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Nhà cung cấp</th>
                                <th>Ngày nhập</th>
                                <th class="text-center">Số dòng hàng</th>
                                <th class="text-right">Tổng tiền</th>
                                <th>Người tạo</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($receipts as $receipt)

                                <tr>
                                    {{-- Mã phiếu --}}
                                    <td>
                                        <strong class="receipt-code">
                                            {{ $receipt->receipt_code }}
                                        </strong>
                                    </td>

                                    {{-- Nhà cung cấp --}}
                                    <td>
                                        <div class="supplier-name">
                                            {{ $receipt->supplier_name ?: 'Không có thông tin' }}
                                        </div>
                                    </td>

                                    {{-- Ngày nhập --}}
                                    <td>
                                        <span class="date-text">
                                            {{ $receipt->received_at
                                                ? $receipt->received_at->format('d/m/Y H:i')
                                                : '-' }}
                                        </span>
                                    </td>

                                    {{-- Số dòng hàng --}}
                                    <td class="text-center">
                                        <span class="quantity-badge">
                                            {{ $receipt->details_count }}
                                        </span>
                                    </td>

                                    {{-- Tổng tiền --}}
                                    <td class="text-right">
                                        <strong class="receipt-total">
                                            {{ number_format((float) $receipt->total_cost, 0, ',', '.') }} đ
                                        </strong>
                                    </td>

                                    {{-- Người tạo --}}
                                    <td>
                                        {{ $receipt->createdBy->name ?? 'Không xác định' }}
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="text-center">
                                        <a href="{{ route('manager.stock-receipts.show', $receipt->id) }}"
                                           class="detail-button">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="empty-inventory">
                                        <div class="empty-title">
                                            Chưa có phiếu nhập kho
                                        </div>
                                        <p>
                                            Hãy tạo phiếu nhập mới để ghi nhận hàng hóa nhập vào cửa hàng.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Phân trang --}}
                @if ($receipts->hasPages())
                    <div class="inventory-pagination">
                        {{ $receipts->links() }}
                    </div>
                @endif

            </section>

            {{-- Chú thích --}}
            <div class="inventory-note">
                <strong>Lưu ý:</strong>
                Mỗi phiếu nhập kho ghi nhận thông tin nhà cung cấp, ngày nhập,
                danh sách sản phẩm, số lượng và tổng chi phí nhập hàng.
            </div>

        </div>
    </div>

    <style>
        .employee-inventory-page {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 38px 22px 65px;
            color: #30241E;
        }

        .employee-inventory-page * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        .inventory-container {
            max-width: 1450px;
            margin: auto;
        }

        .inventory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 27px;
        }

        .inventory-eyebrow {
            color: #A38B70;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .inventory-header h1 {
            color: #30241E;
            font-size: 29px;
            font-weight: 700;
            margin: 0;
        }

        .inventory-subtitle {
            color: #887B70;
            font-size: 14px;
            margin-top: 8px;
        }

        .create-receipt-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 18px;
            border-radius: 8px;
            background: #70452F;
            color: #fff !important;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .create-receipt-button:hover {
            background: #513322;
        }

        .create-receipt-button span {
            font-size: 20px;
            line-height: 1;
        }

        .inventory-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 17px;
            margin-bottom: 20px;
            border: 1px solid #D5E8D4;
            border-radius: 10px;
            background: #EAF3E9;
            color: #527B55;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-icon {
            width: 23px;
            height: 23px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 50%;
            background: #527B55;
            color: white;
            font-size: 13px;
        }

        .inventory-table-card {
            background: #fff;
            border: 1px solid #E8DED2;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(65, 45, 30, .035);
            overflow: hidden;
        }

        .table-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 22px 23px;
        }

        .table-heading h2 {
            color: #49382C;
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }

        .table-heading p {
            color: #9A8D81;
            font-size: 12px;
            margin-top: 5px;
        }

        .record-count {
            padding: 7px 12px;
            background: #F7F2EA;
            border-radius: 20px;
            color: #806247;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .inventory-table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .inventory-table thead {
            background: #F8F5F0;
        }

        .inventory-table th {
            padding: 15px 13px;
            color: #806B57;
            font-size: 11px;
            font-weight: 800;
            text-align: left;
            white-space: nowrap;
        }

        .inventory-table td {
            padding: 16px 13px;
            border-top: 1px solid #F0EAE3;
            color: #66584C;
            font-size: 13px;
            vertical-align: middle;
        }

        .inventory-table tbody tr:hover {
            background: #FFFCF8;
        }

        .receipt-code {
            color: #70452F;
            font-weight: 800;
            white-space: nowrap;
        }

        .supplier-name {
            color: #49382C;
            font-weight: 600;
            min-width: 150px;
        }

        .date-text {
            color: #66584C;
            white-space: nowrap;
        }

        .quantity-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            padding: 6px 10px;
            border-radius: 20px;
            background: #F2EAE0;
            color: #806247;
            font-size: 12px;
            font-weight: 700;
        }

        .receipt-total {
            color: #527B55;
            font-size: 13px;
            white-space: nowrap;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .detail-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 78px;
            min-height: 34px;
            padding: 7px 13px;
            border-radius: 7px;
            background: #70452F;
            color: #fff !important;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .detail-button:hover {
            background: #513322;
        }

        .empty-inventory {
            padding: 45px 20px !important;
            text-align: center;
        }

        .empty-title {
            color: #49382C;
            font-size: 15px;
            font-weight: 700;
        }

        .empty-inventory p {
            color: #9A8D81;
            font-size: 13px;
            margin-top: 6px;
        }

        .inventory-pagination {
            padding: 18px 22px;
            border-top: 1px solid #F0EAE3;
        }

        .inventory-note {
            margin-top: 18px;
            padding: 15px 18px;
            background: #F3EEE7;
            border: 1px solid #E8DED2;
            border-radius: 10px;
            color: #887B70;
            font-size: 12px;
            line-height: 1.8;
        }

        .inventory-note strong {
            color: #70452F;
        }

        @media (max-width: 768px) {
            .employee-inventory-page {
                padding: 25px 13px 45px;
            }

            .inventory-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .inventory-header h1 {
                font-size: 24px;
            }

            .create-receipt-button {
                width: 100%;
            }

            .table-heading {
                padding: 18px 15px;
            }
        }
    </style>
</x-app-layout>
