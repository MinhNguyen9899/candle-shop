<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:1450px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:25px;">
                <div>
                    <p style="font-size:12px; letter-spacing:2px; color:#9A8068; font-weight:700;">
                        PRODUCT MANAGEMENT
                    </p>

                    <h2 style="font-size:27px; font-weight:800; color:#30241E; margin-top:5px;">
                        Quản lý sản phẩm
                    </h2>

                    <p style="color:#887B70; margin-top:5px;">
                        Xem, thêm mới và cập nhật thông tin sản phẩm.
                    </p>
                </div>

                <a href="{{ route('employee.products.create') }}" class="add-button">
                    + Thêm sản phẩm
                </a>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Bộ lọc --}}
            <form method="GET"
                  action="{{ route('employee.products.index') }}"
                  class="filter-form">

                <input type="text"
                       name="keyword"
                       value="{{ request('keyword') }}"
                       placeholder="Tìm theo tên sản phẩm hoặc mã SKU..."
                       class="filter-input">

                <select name="category_id" class="filter-select">
                    <option value="">Tất cả danh mục</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select">
                    <option value="">Tất cả trạng thái</option>

                    <option value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}>
                        Đang kinh doanh
                    </option>

                    <option value="discontinued"
                        {{ request('status') === 'discontinued' ? 'selected' : '' }}>
                        Ngừng kinh doanh
                    </option>
                </select>

                <button type="submit" class="search-button">
                    Tìm kiếm
                </button>

                <a href="{{ route('employee.products.index') }}" class="reset-button">
                    Làm mới
                </a>
            </form>

            {{-- Bảng sản phẩm --}}
            <div style="overflow-x:auto; background:white; border:1px solid #E8DED2; border-radius:14px;">

                <table style="width:100%; border-collapse:collapse; min-width:1150px;">

                    <thead style="background:#F7F2EA;">
                        <tr>
                            <th class="table-heading text-center">ID</th>
                            <th class="table-heading text-center">Hình ảnh</th>
                            <th class="table-heading text-left">Thông tin sản phẩm</th>
                            <th class="table-heading text-left">Danh mục</th>
                            <th class="table-heading text-right">Giá bán</th>
                            <th class="table-heading text-center">Tồn thực tế</th>
                            <th class="table-heading text-center">Đang giữ chỗ</th>
                            <th class="table-heading text-center">Có thể bán</th>
                            <th class="table-heading text-center">Trạng thái</th>
                            <th class="table-heading text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)

                            @php
                                $onHand = $product->inventory->on_hand_qty ?? 0;
                                $reserved = $product->inventory->reserved_qty ?? 0;
                                $available = max(0, $onHand - $reserved);
                                $threshold = $product->low_stock_threshold ?? 5;
                                $isLowStock = $available <= $threshold;
                            @endphp

                            <tr style="border-top:1px solid #EEE7DF;">

                                {{-- ID --}}
                                <td class="table-cell text-center">
                                    {{ $product->id }}
                                </td>

                                {{-- Hình ảnh --}}
                                <td class="table-cell text-center">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             style="width:65px; height:65px; object-fit:cover; border-radius:9px;">
                                    @else
                                        <span style="color:#A3988E; font-size:12px;">
                                            Chưa có ảnh
                                        </span>
                                    @endif
                                </td>

                                {{-- Thông tin sản phẩm --}}
                                <td class="table-cell">
                                    <strong style="color:#30241E; font-size:14px;">
                                        {{ $product->name }}
                                    </strong>

                                    <div class="product-meta">
                                        SKU: {{ $product->sku }}
                                    </div>

                                    @if ($product->description)
                                        <div class="product-meta">
                                            {{ \Illuminate\Support\Str::limit($product->description, 65) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Danh mục --}}
                                <td class="table-cell">
                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                </td>

                                {{-- Giá bán --}}
                                <td class="table-cell text-right"
                                    style="font-weight:700; color:#70452F; white-space:nowrap;">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </td>

                                {{-- Tồn thực tế --}}
                                <td class="table-cell text-center">
                                    {{ number_format($onHand) }}
                                </td>

                                {{-- Đang giữ chỗ --}}
                                <td class="table-cell text-center">
                                    {{ number_format($reserved) }}
                                </td>

                                {{-- Có thể bán --}}
                                <td class="table-cell text-center">
                                    <strong style="color:{{ $isLowStock ? '#B45309' : '#376342' }};">
                                        {{ number_format($available) }}
                                    </strong>

                                    @if ($isLowStock)
                                        <div style="font-size:11px; color:#B45309; margin-top:3px;">
                                            Sắp hết hàng
                                        </div>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td class="table-cell text-center">
                                    @if ($product->status === 'active')
                                        <span class="status-active">
                                            Đang kinh doanh
                                        </span>
                                    @else
                                        <span class="status-inactive">
                                            Ngừng kinh doanh
                                        </span>
                                    @endif
                                </td>

                                {{-- Thao tác --}}
                                <td class="table-cell text-center" style="white-space:nowrap;">
                                    <a href="{{ route('employee.products.edit', $product->id) }}"
                                       class="edit-button">
                                        Sửa
                                    </a>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="10"
                                    style="padding:35px; text-align:center; color:#95877B;">
                                    Không tìm thấy sản phẩm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Phân trang --}}
            <div style="margin-top:20px;">
                {{ $products->withQueryString()->links() }}
            </div>

        </div>
    </div>

    <style>
        .add-button {
            background:#70452F;
            color:white;
            padding:12px 20px;
            border-radius:9px;
            text-decoration:none;
            font-weight:700;
        }

        .add-button:hover,
        .search-button:hover {
            background:#593723;
        }

        .filter-form {
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            background:white;
            padding:18px;
            border:1px solid #E8DED2;
            border-radius:12px;
            margin-bottom:20px;
        }

        .filter-input,
        .filter-select {
            padding:11px 13px;
            border:1px solid #E8DED2;
            border-radius:8px;
            color:#49382C;
            background:white;
            outline:none;
        }

        .filter-input {
            flex:1;
            min-width:220px;
        }

        .filter-select {
            min-width:175px;
        }

        .filter-input:focus,
        .filter-select:focus {
            border-color:#A98562;
        }

        .search-button {
            background:#70452F;
            color:white;
            border:none;
            padding:11px 20px;
            border-radius:8px;
            cursor:pointer;
            font-weight:700;
        }

        .reset-button {
            background:#EEE8E1;
            color:#594638;
            padding:11px 18px;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
        }

        .table-heading {
            padding:15px 12px;
            color:#594638;
            font-size:13px;
            font-weight:800;
            white-space:nowrap;
        }

        .table-cell {
            padding:13px 12px;
            color:#66574B;
            font-size:13px;
            vertical-align:middle;
        }

        .text-center {
            text-align:center;
        }

        .text-left {
            text-align:left;
        }

        .text-right {
            text-align:right;
        }

        .product-meta {
            font-size:12px;
            color:#9A8D81;
            margin-top:4px;
        }

        .status-active,
        .status-inactive {
            display:inline-block;
            padding:6px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:700;
            white-space:nowrap;
        }

        .status-active {
            background:#E7F2E8;
            color:#376342;
        }

        .status-inactive {
            background:#F8E9E7;
            color:#994B42;
        }

        .edit-button {
            display:inline-block;
            background:#80634B;
            color:white;
            padding:8px 15px;
            border-radius:6px;
            text-decoration:none;
            font-size:12px;
            font-weight:700;
        }

        .edit-button:hover {
            background:#634832;
        }

        .success-message {
            background:#EDF5ED;
            color:#315D38;
            padding:13px 16px;
            margin-bottom:20px;
            border-radius:9px;
        }

        .error-message {
            background:#F8E9E7;
            color:#994B42;
            padding:13px 16px;
            margin-bottom:20px;
            border-radius:9px;
        }
    </style>
</x-app-layout>