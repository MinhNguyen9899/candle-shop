
<x-app-layout>
    <div class="employee-inventory-page">
        <div class="inventory-container">

            {{-- Tiêu đề --}}
            <div class="inventory-header receipt-header">
                <div>
                    <p class="inventory-eyebrow">INVENTORY MANAGEMENT</p>
                    <h1>Quản lý phiếu nhập kho</h1>
                    <p class="inventory-subtitle">
                        Theo dõi các lần nhập hàng và chi phí nhập kho của cửa hàng.
                    </p>
                </div>

                <a href="{{ route('employee.stock-receipts.create') }}"
                   class="create-receipt-button">
                    <span>+</span>
                    Tạo phiếu nhập
                </a>
            </div>

            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div class="inventory-alert">
                    <strong>Thành công!</strong>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Danh sách phiếu nhập --}}
            <section class="inventory-table-card">

                <div class="table-heading">
                    <div>
                        <h2>Danh sách phiếu nhập kho</h2>
                        <p>
                            Thông tin các phiếu nhập hàng đã được ghi nhận trong hệ thống.
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
                                <th class="text-center">Số sản phẩm</th>
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
                                        <div class="receipt-supplier">
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

                                    {{-- Số sản phẩm --}}
                                    <td class="text-center">
                                        <span class="quantity-badge">
                                            {{ $receipt->details_count ?? 0 }} sản phẩm
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
                                        <a href="{{ route('employee.stock-receipts.show', $receipt->id) }}"
                                           class="detail-button">
                                            Xem chi tiết
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
                                            Các phiếu nhập hàng sẽ xuất hiện tại đây sau khi được tạo.
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
                Mỗi phiếu nhập kho ghi nhận nhà cung cấp, thời gian nhập,
                số lượng hàng hóa, tổng chi phí và nhân viên thực hiện.
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
            margin-bottom: 27px;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 19px;
            background: #70452F;
            color: #fff !important;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
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
            padding: 14px 17px;
            margin-bottom: 20px;
            border-radius: 9px;
            background: #EAF3E9;
            border: 1px solid #D6E8D4;
            color: #527B55;
            font-size: 13px;
        }

        .inventory-alert strong {
            margin-right: 5px;
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
            min-width: 1000px;
            border-collapse: collapse;
        }

        .inventory-table thead {
            background: #F8F5F0;
        }

        .inventory-table th {
            padding: 15px 14px;
            color: #806B57;
            font-size: 11px;
            font-weight: 800;
            text-align: left;
            white-space: nowrap;
        }

        .inventory-table td {
            padding: 17px 14px;
            border-top: 1px solid #F0EAE3;
            color: #66584C;
            font-size: 13px;
            vertical-align: middle;
        }

        .inventory-table tbody tr:hover {
            background: #FFFCF8;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .receipt-code {
            color: #70452F;
            font-weight: 800;
            white-space: nowrap;
        }

        .receipt-supplier {
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
            padding: 6px 11px;
            background: #F3EEE7;
            color: #806247;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .receipt-total {
            color: #527B55;
            font-size: 13px;
            white-space: nowrap;
        }

        .detail-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 35px;
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

            .receipt-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .inventory-header h1 {
                font-size: 24px;
            }

            .table-heading {
                padding: 18px 15px;
            }

            .create-receipt-button {
                width: 100%;
            }
        }
    </style>
</x-app-layout>
