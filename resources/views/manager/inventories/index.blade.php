<x-app-layout>
    <div class="employee-inventory-page">
        <div class="inventory-container">

            {{-- Tiêu đề --}}
            <div class="inventory-header">
                <div>
                    <p class="inventory-eyebrow">INVENTORY MANAGEMENT</p>
                    <h1>Quản lý tồn kho</h1>
                    <p class="inventory-subtitle">
                        Theo dõi tình trạng kinh doanh và số lượng tồn kho của từng sản phẩm.
                    </p>
                </div>
            </div>

            {{-- Tổng quan --}}
            <div class="inventory-summary">

                <div class="summary-card">
                    <div class="summary-icon icon-brown">01</div>
                    <div>
                        <span>Tổng sản phẩm</span>
                        <strong>{{ $summaryCounts['total'] }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon icon-green">02</div>
                    <div>
                        <span>Còn hàng</span>
                        <strong>{{ $summaryCounts['in_stock'] }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon icon-gold">03</div>
                    <div>
                        <span>Sắp hết hàng</span>
                        <strong>{{ $summaryCounts['low_stock'] }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon icon-red">04</div>
                    <div>
                        <span>Hết hàng</span>
                        <strong>{{ $summaryCounts['out_of_stock'] }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon icon-gray">05</div>
                    <div>
                        <span>Ngừng kinh doanh</span>
                        <strong>{{ $summaryCounts['discontinued'] }}</strong>
                    </div>
                </div>

            </div>

            {{-- Bộ lọc --}}
            <section class="inventory-filter">

                <div class="filter-heading">
                    <h2>Tìm kiếm sản phẩm</h2>
                    <p>
                        Tìm theo tên sản phẩm, SKU, mùi hương hoặc tình trạng tồn kho.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('manager.inventory.index') }}"
                      class="filter-form">

                    <div class="filter-input">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="Tên sản phẩm, SKU hoặc mùi hương..."
                        >
                    </div>

                    <div class="filter-select">
                        <select name="status">
                            <option value="">Tất cả trạng thái</option>

                            <option value="in_stock"
                                {{ request('status') === 'in_stock' ? 'selected' : '' }}>
                                Còn hàng
                            </option>

                            <option value="low_stock"
                                {{ request('status') === 'low_stock' ? 'selected' : '' }}>
                                Sắp hết hàng
                            </option>

                            <option value="out_of_stock"
                                {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>
                                Hết hàng
                            </option>

                            <option value="discontinued"
                                {{ request('status') === 'discontinued' ? 'selected' : '' }}>
                                Ngừng kinh doanh
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="filter-button">
                        Tìm kiếm
                    </button>

                    <a href="{{ route('manager.inventory.index') }}"
                       class="reset-button">
                        Làm mới
                    </a>

                </form>
            </section>

            {{-- Danh sách sản phẩm --}}
            <section class="inventory-table-card">

                <div class="table-heading">
                    <div>
                        <h2>Danh sách sản phẩm</h2>
                        <p>
                            Theo dõi tồn kho trực tiếp theo từng sản phẩm.
                        </p>
                    </div>

                    <span class="record-count">
                        {{ $products->total() }} sản phẩm
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="inventory-table">

                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Mã SKU</th>
                                <th>Danh mục</th>                              
                                <th class="text-center">Tồn thực tế</th>
                                <th class="text-center">Đang giữ</th>
                                <th class="text-center">Có thể bán</th>
                                <th class="text-center">Ngưỡng cảnh báo</th>
                                <th class="text-center">Tình trạng</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)

                                @php
                                    $inventory = $product->inventory;

                                    $onHand = $inventory?->on_hand_qty ?? 0;
                                    $reserved = $inventory?->reserved_qty ?? 0;

                                    $available = max(0, $onHand - $reserved);

                                    $threshold = $product->low_stock_threshold ?? 5;

                                    if ($product->status === 'discontinued') {
                                        $stockStatus = 'discontinued';
                                    } elseif ($available <= 0) {
                                        $stockStatus = 'out_of_stock';
                                    } elseif ($available <= $threshold) {
                                        $stockStatus = 'low_stock';
                                    } else {
                                        $stockStatus = 'in_stock';
                                    }
                                @endphp

                                <tr>
                                    {{-- Tên sản phẩm --}}
                                    <td>
                                        <div class="inventory-product">
                                            {{ $product->name }}
                                        </div>

                                        <div class="product-id">
                                            Mã sản phẩm: #{{ $product->id }}
                                        </div>
                                    </td>

                                    {{-- SKU --}}
                                    <td>
                                        <strong class="sku-text">
                                            {{ $product->sku ?? 'N/A' }}
                                        </strong>
                                    </td>

                                    {{-- Danh mục --}}
                                    <td>
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                    
                                    {{-- Tồn thực tế --}}
                                    <td class="text-center">
                                        {{ $onHand }}
                                    </td>

                                    {{-- Đang giữ --}}
                                    <td class="text-center">
                                        <span class="reserved-number">
                                            {{ $reserved }}
                                        </span>
                                    </td>

                                    {{-- Có thể bán --}}
                                    <td class="text-center">
                                        <strong class="available-number">
                                            {{ $available }}
                                        </strong>
                                    </td>

                                    {{-- Ngưỡng cảnh báo --}}
                                    <td class="text-center">
                                        {{ $threshold }}
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="text-center">
                                        @if ($stockStatus === 'discontinued')
                                            <span class="stock-badge stock-discontinued">
                                                Ngừng kinh doanh
                                            </span>
                                        @elseif ($stockStatus === 'out_of_stock')
                                            <span class="stock-badge stock-out">
                                                Hết hàng
                                            </span>
                                        @elseif ($stockStatus === 'low_stock')
                                            <span class="stock-badge stock-low">
                                                Sắp hết hàng
                                            </span>
                                        @else
                                            <span class="stock-badge stock-good">
                                                Còn hàng
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="10" class="empty-inventory">
                                        <div class="empty-title">
                                            Không tìm thấy sản phẩm
                                        </div>
                                        <p>
                                            Thử thay đổi từ khóa hoặc bộ lọc để tìm kiếm lại.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Phân trang --}}
                @if ($products->hasPages())
                    <div class="inventory-pagination">
                        {{ $products->links() }}
                    </div>
                @endif

            </section>

            {{-- Chú thích --}}
            <div class="inventory-note">
                <strong>Lưu ý:</strong>
                Tồn khả dụng = Tồn thực tế - Số lượng đang giữ cho đơn hàng.
                Ngưỡng cảnh báo được thiết lập riêng cho từng sản phẩm.
                Trạng thái ngừng kinh doanh do Manager xác nhận.
                Manager có quyền quản lý trạng thái kinh doanh của sản phẩm.
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

        .inventory-header { margin-bottom: 27px; }

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

        .inventory-summary {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 23px;
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            padding: 17px 14px;
            box-shadow: 0 4px 16px rgba(65, 45, 30, .035);
        }

        .summary-icon {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            font-size: 11px;
            font-weight: 800;
        }

        .icon-brown { background: #F2EAE0; color: #806247; }
        .icon-green { background: #EAF3E9; color: #527B55; }
        .icon-gold { background: #FBF2DE; color: #A67C36; }
        .icon-red { background: #F9EAE7; color: #AD514A; }
        .icon-gray { background: #ECEAE7; color: #77716A; }

        .summary-card span {
            display: block;
            color: #887B70;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .summary-card strong {
            color: #49382C;
            font-size: 23px;
            font-weight: 800;
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
            gap: 11px;
            flex-wrap: wrap;
            margin-top: 19px;
        }

        .filter-input { flex: 1; min-width: 250px; }

        .filter-input input,
        .filter-select select {
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
        .filter-select select:focus {
            border-color: #B79A73;
            box-shadow: 0 0 0 3px rgba(183, 154, 115, .12);
        }

        .filter-select { min-width: 200px; }

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
        }

        .filter-button { background: #70452F; color: #fff; }
        .filter-button:hover { background: #513322; }
        .reset-button { background: #F3EEE8; color: #70452F; }
        .reset-button:hover { background: #E8DED2; }

        .inventory-table-card { overflow: hidden; }

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

        .table-responsive { overflow-x: auto; }

        .inventory-table {
            width: 100%;
            min-width: 1300px;
            border-collapse: collapse;
        }

        .inventory-table thead { background: #F8F5F0; }

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

        .inventory-table tbody tr:hover { background: #FFFCF8; }

        .inventory-product {
            color: #49382C;
            font-weight: 700;
            min-width: 180px;
        }

        .product-id {
            color: #A3978B;
            font-size: 11px;
            margin-top: 5px;
        }

        .sku-text { color: #70452F; white-space: nowrap; }
        .reserved-number { color: #A67C36; font-weight: 700; }
        .available-number { color: #49382C; font-size: 15px; }
        .text-center { text-align: center !important; }

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

        .stock-out { background: #F9EAE7; color: #AD514A; }
        .stock-low { background: #FBF2DE; color: #A67C36; }
        .stock-good { background: #EAF3E9; color: #527B55; }
        .stock-discontinued { background: #ECEAE7; color: #77716A; }

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

        .inventory-note strong { color: #70452F; }

        @media (max-width: 1100px) {
            .inventory-summary {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .employee-inventory-page { padding: 25px 13px 45px; }
            .inventory-header h1 { font-size: 24px; }

            .inventory-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .summary-card { padding: 13px 10px; }
            .summary-icon { width: 34px; height: 34px; }
            .summary-card strong { font-size: 20px; }
            .inventory-filter { padding: 18px 15px; }
            .filter-form { flex-direction: column; }
            .filter-input, .filter-select { width: 100%; min-width: 0; }
            .filter-button, .reset-button { width: 100%; }
            .table-heading { padding: 18px 15px; }
        }
    </style>
</x-app-layout>
