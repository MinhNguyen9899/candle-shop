 
<x-app-layout>
    <div style="max-width: 1450px; margin: 35px auto; padding: 0 20px;">

        {{-- Tiêu đề --}}
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
            <div>
                <p style="font-size: 12px; letter-spacing: 2px; color: #9a8068; font-weight: 700;">
                    PRODUCT MANAGEMENT
                </p>

                <h2 style="font-size: 27px; font-weight: 800; color: #30241e; margin-top: 5px;">
                    Quản lý sản phẩm
                </h2>

                <p style="color: #887b70; margin-top: 5px;">
                    Quản lý thông tin, giá bán và tình trạng kinh doanh của từng sản phẩm
                </p>
            </div>

            <a href="{{ route('manager.products.create') }}"
               style="background: #70452f; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 700;">
                + Thêm sản phẩm
            </a>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div style="background: #edf5ed; color: #315d38; padding: 13px 16px; margin-bottom: 20px; border-radius: 9px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: #f8e9e7; color: #994b42; padding: 13px 16px; margin-bottom: 20px; border-radius: 9px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Bộ lọc --}}
        <form method="GET"
              action="{{ route('manager.products.index') }}"
              style="display: flex; flex-wrap: wrap; gap: 10px; background: #fff; padding: 18px; border: 1px solid #e8ded2; border-radius: 12px; margin-bottom: 20px;">

            <input type="text"
                   name="keyword"
                   value="{{ request('keyword') }}"
                   placeholder="Tìm theo tên sản phẩm hoặc SKU..."
                   style="padding: 11px 13px; border: 1px solid #e8ded2; border-radius: 7px; flex: 1; min-width: 220px;">

            <select name="category_id"
                    style="padding: 11px 13px; border: 1px solid #e8ded2; border-radius: 7px; min-width: 180px;">
                <option value="">Tất cả danh mục</option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                    style="padding: 11px 13px; border: 1px solid #e8ded2; border-radius: 7px; min-width: 180px;">
                <option value="">Tất cả trạng thái</option>

                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                    Đang kinh doanh
                </option>

                <option value="discontinued" {{ request('status') === 'discontinued' ? 'selected' : '' }}>
                    Ngừng kinh doanh
                </option>
            </select>

            <button type="submit"
                    style="background: #70452f; color: white; border: none; padding: 11px 20px; border-radius: 7px; cursor: pointer; font-weight: 700;">
                Tìm kiếm
            </button>

            <a href="{{ route('manager.products.index') }}"
               style="background: #eee8e1; color: #594638; padding: 11px 18px; border-radius: 7px; text-decoration: none;">
                Làm mới
            </a>
        </form>

        {{-- Bảng sản phẩm --}}
        <div style="overflow-x: auto; background: white; border: 1px solid #e8ded2; border-radius: 12px;">
            <table style="width: 100%; border-collapse: collapse; min-width: 1250px;">

                <thead style="background: #f7f2ea;">
                    <tr>
                        <th style="padding: 15px; text-align: center; color: #594638;">ID</th>
                        <th style="padding: 15px; text-align: center; color: #594638;">Hình ảnh</th>
                        <th style="padding: 15px; text-align: left; color: #594638;">Thông tin sản phẩm</th>
                        <th style="padding: 15px; text-align: left; color: #594638;">Danh mục</th>
                        <th style="padding: 15px; text-align: right; color: #594638;">Giá bán</th>
                        <th style="padding: 15px; text-align: center; color: #594638;">Tồn kho</th>
                        <th style="padding: 15px; text-align: center; color: #594638;">Có thể bán</th>
                        <th style="padding: 15px; text-align: center; color: #594638;">Trạng thái</th>
                        <th style="padding: 15px; text-align: center; color: #594638;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        @php
                            $isActive = $product->status === 'active';

                            $onHand = $product->inventory->on_hand_qty ?? 0;
                            $reserved = $product->inventory->reserved_qty ?? 0;
                            $available = max(0, $onHand - $reserved);

                            $threshold = $product->low_stock_threshold ?? 5;
                            $isLowStock = $available <= $threshold;
                        @endphp

                        <tr style="border-top: 1px solid #eee7df;">

                            {{-- ID --}}
                            <td style="padding: 13px; text-align: center; color: #66574b;">
                                {{ $product->id }}
                            </td>

                            {{-- Hình ảnh --}}
                            <td style="padding: 13px; text-align: center;">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         style="width: 65px; height: 65px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div style="width: 65px; height: 65px; background: #f7f2ea; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a3988e; font-size: 11px; margin: auto;">
                                        Chưa có ảnh
                                    </div>
                                @endif
                            </td>

                            {{-- Thông tin sản phẩm --}}
                            <td style="padding: 13px; min-width: 240px;">
                                <strong style="color: #30241e; display: block;">
                                    {{ $product->name }}
                                </strong>

                                <div style="font-size: 12px; color: #9a8068; margin-top: 5px;">
                                    SKU: {{ $product->sku ?? 'Chưa có SKU' }}
                                </div>

                                @if ($product->description)
                                    <div style="font-size: 12px; color: #887b70; margin-top: 4px; max-width: 260px;">
                                        {{ \Illuminate\Support\Str::limit($product->description, 65) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Danh mục --}}
                            <td style="padding: 13px; color: #66574b;">
                                {{ $product->category->name ?? 'Chưa phân loại' }}
                            </td>

                            {{-- Giá bán --}}
                            <td style="padding: 13px; text-align: right; white-space: nowrap;">
                                <strong style="color: #70452f;">
                                    {{ number_format($product->price ?? 0, 0, ',', '.') }}đ
                                </strong>
                            </td>

                            {{-- Tồn kho thực tế --}}
                            <td style="padding: 13px; text-align: center;">
                                <strong style="color: #30241e;">
                                    {{ number_format($onHand) }}
                                </strong>

                                <div style="font-size: 11px; color: #9a8d81; margin-top: 3px;">
                                    Đang giữ: {{ number_format($reserved) }}
                                </div>
                            </td>

                            {{-- Số lượng có thể bán --}}
                            <td style="padding: 13px; text-align: center;">
                                <strong style="font-size: 15px; color: {{ $isLowStock ? '#b45309' : '#376342' }};">
                                    {{ number_format($available) }}
                                </strong>

                                @if ($isLowStock)
                                    <div style="font-size: 11px; color: #b45309; margin-top: 3px;">
                                        Sắp hết hàng
                                    </div>
                                @else
                                    <div style="font-size: 11px; color: #6b8b70; margin-top: 3px;">
                                        Đủ hàng
                                    </div>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td style="padding: 13px; text-align: center;">
                                @if ($isActive)
                                    <span style="display: inline-block; background: #e7f2e8; color: #376342; padding: 6px 11px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                        Đang kinh doanh
                                    </span>
                                @else
                                    <span style="display: inline-block; background: #f8e9e7; color: #994b42; padding: 6px 11px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                        Ngừng kinh doanh
                                    </span>
                                @endif
                            </td>

                            {{-- Thao tác --}}
                            <td style="padding: 13px; text-align: center; white-space: nowrap;">
                                <a href="{{ route('manager.products.edit', $product->id) }}"
                                   style="display: inline-block; background: #80634b; color: white; padding: 8px 13px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    Chi tiết / Sửa
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 30px; text-align: center; color: #95877b;">
                                Không tìm thấy sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>

    </div>
</x-app-layout>