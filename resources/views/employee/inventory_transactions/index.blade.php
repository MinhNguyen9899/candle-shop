
<x-app-layout>
    <div class="employee-inventory-page">
        <div class="inventory-container">

            {{-- Tiêu đề --}}
            <div class="inventory-header">
                <div>
                    <p class="inventory-eyebrow">INVENTORY MANAGEMENT</p>
                    <h1>Lịch sử giao dịch kho</h1>
                    <p class="inventory-subtitle">
                        Theo dõi toàn bộ hoạt động nhập, xuất và điều chỉnh tồn kho.
                    </p>
                </div>
            </div>

            {{-- Bộ lọc --}}
            <section class="inventory-filter">

                <div class="filter-heading">
                    <h2>Tìm kiếm giao dịch</h2>
                    <p>
                        Tìm theo tên sản phẩm, SKU, loại giao dịch hoặc khoảng thời gian.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('employee.inventory-transactions.index') }}"
                      class="filter-form">

                    <div class="filter-input">
                        <input type="text"
                               name="keyword"
                               value="{{ $keyword }}"
                               placeholder="Tên sản phẩm hoặc mã SKU...">
                    </div>

                    <div class="filter-select">
                        <select name="transaction_type">
                            <option value="">Tất cả giao dịch</option>

                            <option value="receipt"
                                {{ $transactionType === 'receipt' ? 'selected' : '' }}>
                                Nhập kho
                            </option>

                            <option value="issue"
                                {{ $transactionType === 'issue' ? 'selected' : '' }}>
                                Xuất kho
                            </option>

                            <option value="adjustment"
                                {{ $transactionType === 'adjustment' ? 'selected' : '' }}>
                                Điều chỉnh kho
                            </option>
                        </select>
                    </div>

                    <div class="filter-date">
                        <label for="date_from">Từ ngày</label>
                        <input type="date"
                               id="date_from"
                               name="date_from"
                               value="{{ $dateFrom }}">
                    </div>

                    <div class="filter-date">
                        <label for="date_to">Đến ngày</label>
                        <input type="date"
                               id="date_to"
                               name="date_to"
                               value="{{ $dateTo }}">
                    </div>

                    <button type="submit" class="filter-button">
                        Tìm kiếm
                    </button>

                    <a href="{{ route('employee.inventory-transactions.index') }}"
                       class="reset-button">
                        Làm mới
                    </a>

                </form>
            </section>

            {{-- Danh sách giao dịch --}}
            <section class="inventory-table-card">

                <div class="table-heading">
                    <div>
                        <h2>Danh sách giao dịch kho</h2>
                        <p>
                            Lịch sử biến động số lượng tồn kho của từng sản phẩm.
                        </p>
                    </div>

                    <span class="record-count">
                        {{ $transactions->total() }} giao dịch
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="inventory-table">

                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Sản phẩm</th>
                                <th>Mã SKU</th>
                                <th class="text-center">Loại giao dịch</th>
                                <th class="text-center">Số lượng</th>
                                <th>Người thực hiện</th>
                                <th>Thời gian</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transactions as $index => $transaction)

                                @php
                                    $product = $transaction->product;
                                @endphp

                                <tr>
                                    {{-- STT --}}
                                    <td class="text-center">
                                        {{ $transactions->firstItem() + $index }}
                                    </td>

                                    {{-- Sản phẩm --}}
                                    <td>
                                        <div class="inventory-product">
                                            {{ $product->name ?? 'Không xác định' }}
                                        </div>

                                        <div class="product-id">
                                            Mã sản phẩm:
                                            {{ $product ? '#' . $product->id : 'Không xác định' }}
                                        </div>
                                    </td>

                                    {{-- SKU --}}
                                    <td>
                                        <strong class="sku-text">
                                            {{ $product->sku ?? '-' }}
                                        </strong>
                                    </td>

                                    {{-- Loại giao dịch --}}
                                    <td class="text-center">
                                        @if ($transaction->transaction_type === 'receipt')
                                            <span class="stock-badge stock-good">
                                                Nhập kho
                                            </span>
                                        @elseif ($transaction->transaction_type === 'issue')
                                            <span class="stock-badge stock-out">
                                                Xuất kho
                                            </span>
                                        @elseif ($transaction->transaction_type === 'adjustment')
                                            <span class="stock-badge stock-low">
                                                Điều chỉnh kho
                                            </span>
                                        @else
                                            <span class="stock-badge stock-discontinued">
                                                {{ $transaction->transaction_type }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Số lượng --}}
                                    <td class="text-center">
                                        <strong class="available-number">
                                            {{ number_format($transaction->quantity) }}
                                        </strong>
                                    </td>

                                    {{-- Người thực hiện --}}
                                    <td>
                                        {{ $transaction->createdBy->name ?? 'Không xác định' }}
                                    </td>

                                    {{-- Thời gian --}}
                                    <td>
                                        <span class="date-text">
                                            {{ $transaction->created_at
                                                ? $transaction->created_at->format('d/m/Y H:i')
                                                : '-' }}
                                        </span>
                                    </td>

                                    {{-- Ghi chú --}}
                                    <td>
                                        <div class="transaction-note">
                                            {{ $transaction->note ?: 'Không có ghi chú' }}
                                        </div>
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="text-center">
                                        <a href="{{ route('employee.inventory-transactions.show', $transaction->id) }}"
                                           class="detail-button">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="9" class="empty-inventory">
                                        <div class="empty-title">
                                            Chưa có giao dịch kho
                                        </div>
                                        <p>
                                            Chưa tìm thấy giao dịch phù hợp với điều kiện tìm kiếm.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Phân trang --}}
                @if ($transactions->hasPages())
                    <div class="inventory-pagination">
                        {{ $transactions->links() }}
                    </div>
                @endif

            </section>

            {{-- Chú thích --}}
            <div class="inventory-note">
                <strong>Lưu ý:</strong>
                Lịch sử giao dịch ghi nhận các hoạt động nhập kho, xuất kho
                và điều chỉnh tồn kho. Mỗi giao dịch được lưu lại cùng người
                thực hiện và thời gian phát sinh.
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

        .inventory-filter,
        .inventory-table-card {
            background: #fff;
            border: 1px solid #E8DED2;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(65, 45, 30, .035);
        }

        .inventory-filter {
            padding: 23px;
            margin-bottom: 22px;
        }

        .filter-heading h2,
        .table-heading h2 {
            color: #49382C;
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }

        .filter-heading p,
        .table-heading p {
            color: #9A8D81;
            font-size: 12px;
            margin-top: 5px;
        }

        .filter-form {
            display: flex;
            align-items: end;
            gap: 11px;
            flex-wrap: wrap;
            margin-top: 19px;
        }

        .filter-input {
            flex: 1;
            min-width: 220px;
        }

        .filter-input input,
        .filter-select select,
        .filter-date input {
            width: 100%;
            height: 44px;
            padding: 0 13px;
            border: 1px solid #E8DED2;
            border-radius: 8px;
            background: #FFFEFC;
            color: #49382C;
            font-size: 13px;
            outline: none;
        }

        .filter-input input:focus,
        .filter-select select:focus,
        .filter-date input:focus {
            border-color: #B79A73;
            box-shadow: 0 0 0 3px rgba(183, 154, 115, .12);
        }

        .filter-select {
            min-width: 190px;
        }

        .filter-date {
            min-width: 155px;
        }

        .filter-date label {
            display: block;
            color: #806B57;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .filter-button,
        .reset-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 19px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: .2s ease;
            white-space: nowrap;
        }

        .filter-button {
            background: #70452F;
            color: #fff;
        }

        .filter-button:hover {
            background: #513322;
        }

        .reset-button {
            background: #F3EEE8;
            color: #70452F;
        }

        .reset-button:hover {
            background: #E8DED2;
        }

        .inventory-table-card {
            overflow: hidden;
        }

        .table-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 22px 23px;
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
            min-width: 1150px;
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

        .inventory-product {
            color: #49382C;
            font-weight: 700;
            min-width: 170px;
        }

        .product-id {
            color: #A3978B;
            font-size: 11px;
            margin-top: 5px;
        }

        .sku-text {
            color: #70452F;
            white-space: nowrap;
        }

        .text-center {
            text-align: center !important;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .stock-out {
            background: #F9EAE7;
            color: #AD514A;
        }

        .stock-low {
            background: #FBF2DE;
            color: #A67C36;
        }

        .stock-good {
            background: #EAF3E9;
            color: #527B55;
        }

        .stock-discontinued {
            background: #ECEAE7;
            color: #77716A;
        }

        .available-number {
            color: #49382C;
            font-size: 15px;
        }

        .date-text {
            white-space: nowrap;
            color: #66584C;
        }

        .transaction-note {
            min-width: 130px;
            max-width: 220px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .detail-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 75px;
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

            .inventory-header h1 {
                font-size: 24px;
            }

            .inventory-filter {
                padding: 18px 15px;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-input,
            .filter-select,
            .filter-date {
                width: 100%;
                min-width: 0;
            }

            .filter-button,
            .reset-button {
                width: 100%;
            }

            .table-heading {
                padding: 18px 15px;
            }
        }
    </style>
</x-app-layout>
