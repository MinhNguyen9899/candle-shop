
<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:1100px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom:28px;">
                <p style="font-size:12px; letter-spacing:2px; color:#9A8068; font-weight:700;">
                    PRODUCT MANAGEMENT
                </p>

                <h1 style="font-size:30px; font-weight:800; color:#30241E; margin:5px 0;">
                    Chỉnh sửa sản phẩm
                </h1>

                <p style="color:#887B70; font-size:14px;">
                    Cập nhật thông tin và theo dõi tình trạng tồn kho của sản phẩm.
                </p>
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

            {{-- Thông báo lỗi --}}
            @if ($errors->any())
                <div class="error-message">
                    <strong>Vui lòng kiểm tra lại thông tin:</strong>

                    <ul style="margin:8px 0 0 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Trạng thái kinh doanh --}}
            <div class="section-card"
                 style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">

                <div>
                    <div style="font-weight:800; color:#30241E;">
                        Trạng thái kinh doanh
                    </div>

                    <p style="font-size:13px; color:#887B70; margin-top:5px;">
                        {{ $product->status === 'active'
                            ? 'Sản phẩm đang được kinh doanh.'
                            : 'Sản phẩm đã ngừng kinh doanh.' }}
                    </p>

                    <div style="margin-top:10px;">
                        @if ($product->status === 'active')
                            <span class="status-active">Đang kinh doanh</span>
                        @else
                            <span class="status-inactive">Ngừng kinh doanh</span>
                        @endif
                    </div>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">

                    <form action="{{ route('manager.products.toggle-status', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('M có chắc chắn muốn thay đổi trạng thái kinh doanh của sản phẩm này không?');">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="status-button"
                                style="background:{{ $product->status === 'active' ? '#A45A36' : '#287A50' }};">

                            {{ $product->status === 'active'
                                ? 'Ngừng kinh doanh'
                                : 'Khôi phục kinh doanh' }}
                        </button>
                    </form>

                    <form action="{{ route('manager.products.destroy', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('M có chắc chắn muốn xóa sản phẩm này? Chỉ sản phẩm chưa phát sinh giao dịch và không còn tồn kho mới được xóa.');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-button">
                            Xóa sản phẩm
                        </button>
                    </form>

                </div>
            </div>

            {{-- Form cập nhật --}}
            <form action="{{ route('manager.products.update', $product->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- 01. Thông tin sản phẩm --}}
                <div class="section-card">

                    <h2 class="section-title">
                        01. Thông tin sản phẩm
                    </h2>

                    <div class="form-group">
                        <label class="field-label">
                            Tên sản phẩm <span class="required">*</span>
                        </label>

                        <input class="field-input"
                               type="text"
                               name="name"
                               value="{{ old('name', $product->name) }}"
                               maxlength="200"
                               required>

                        @error('name')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">
                            Mã SKU <span class="required">*</span>
                        </label>

                        <input class="field-input"
                               type="text"
                               name="sku"
                               value="{{ old('sku', $product->sku) }}"
                               maxlength="100"
                               required>

                        @error('sku')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">
                            Danh mục <span class="required">*</span>
                        </label>

                        <select class="field-input"
                                name="category_id"
                                required>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('category_id')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">Mô tả sản phẩm</label>

                        <textarea class="field-input"
                                  name="description"
                                  rows="5"
                                  placeholder="Nhập thông tin, công dụng, chất liệu và đặc điểm sản phẩm...">{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- 02. Giá và tồn kho --}}
                <div class="section-card">

                    <h2 class="section-title">
                        02. Giá bán và quản lý tồn kho
                    </h2>

                    <div class="form-grid">

                        <div>
                            <label class="field-label">
                                Giá bán (VNĐ) <span class="required">*</span>
                            </label>

                            <input class="field-input"
                                   type="number"
                                   name="price"
                                   min="0"
                                   step="1000"
                                   value="{{ old('price', $product->price) }}"
                                   required>

                            @error('price')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">
                                Giá vốn (VNĐ) <span class="required">*</span>
                            </label>

                            <input class="field-input"
                                   type="number"
                                   name="cost_price"
                                   min="0"
                                   step="1000"
                                   value="{{ old('cost_price', $product->cost_price) }}"
                                   required>

                            @error('cost_price')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div style="margin-top:20px;">
                        <label class="field-label">
                            Ngưỡng cảnh báo tồn kho <span class="required">*</span>
                        </label>

                        <input class="field-input"
                               type="number"
                               name="low_stock_threshold"
                               min="0"
                               value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}"
                               required>

                        <small class="help-text">
                            Hệ thống cảnh báo khi số lượng có thể bán nhỏ hơn hoặc bằng ngưỡng này.
                        </small>

                        @error('low_stock_threshold')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tồn kho hiện tại --}}
                    @php
                        $onHand = $product->inventory->on_hand_qty ?? 0;
                        $reserved = $product->inventory->reserved_qty ?? 0;
                        $available = max(0, $onHand - $reserved);
                    @endphp

                    <div style="margin-top:28px;">

                        <h3 style="font-size:15px; font-weight:800; color:#49382C; margin-bottom:15px;">
                            Tình trạng tồn kho hiện tại
                        </h3>

                        <div class="form-grid">

                            <div class="inventory-box">
                                <p class="inventory-label">Tồn kho thực tế</p>

                                <p class="inventory-value">
                                    {{ number_format($onHand) }}
                                </p>

                                <small>sản phẩm</small>
                            </div>

                            <div class="inventory-box">
                                <p class="inventory-label">Đang giữ chỗ</p>

                                <p class="inventory-value">
                                    {{ number_format($reserved) }}
                                </p>

                                <small>sản phẩm</small>
                            </div>

                            <div class="inventory-box">
                                <p class="inventory-label">Có thể bán</p>

                                <p class="inventory-value"
                                   style="color:{{ $available <= ($product->low_stock_threshold ?? 5) ? '#B45309' : '#376342' }};">
                                    {{ number_format($available) }}
                                </p>

                                <small>sản phẩm</small>
                            </div>

                        </div>

                        <div class="notice-box">
                            <p style="font-size:13px; color:#705C4C; line-height:1.7; margin:0;">
                                Lưu ý: Tại đây chỉ được thay đổi ngưỡng cảnh báo tồn kho.
                                Số lượng tồn thực tế và số lượng đang giữ chỗ được điều chỉnh thông qua
                                phiếu nhập kho và quy trình xử lý đơn hàng.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- 03. Hình ảnh --}}
                <div class="section-card">

                    <h2 class="section-title">
                        03. Hình ảnh sản phẩm
                    </h2>

                    <div style="margin-bottom:20px;">
                        <label class="field-label">Hình ảnh hiện tại</label>

                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:160px; height:160px; object-fit:cover; border-radius:12px; border:1px solid #E8DED2;">
                        @else
                            <p style="color:#887B70; font-size:13px;">
                                Sản phẩm chưa có hình ảnh.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="field-label">Chọn hình ảnh mới</label>

                        <input class="field-input"
                               type="file"
                               name="image"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <small class="help-text">
                            Không chọn ảnh mới nếu muốn giữ nguyên ảnh hiện tại.
                            Chấp nhận JPG, JPEG, PNG, WEBP. Dung lượng tối đa 5MB.
                        </small>

                        @error('image')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Nút thao tác --}}
                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">

                    <a href="{{ route('manager.products.index') }}"
                       class="secondary-button">
                        Quay lại
                    </a>

                    <button type="submit" class="primary-button">
                        Lưu thay đổi
                    </button>

                </div>

            </form>

        </div>
    </div>

    <style>
        .section-card {
            background:#fff;
            border:1px solid #E8DED2;
            border-radius:18px;
            padding:28px;
            margin-bottom:22px;
        }

        .section-title {
            font-size:19px;
            font-weight:800;
            color:#30241E;
            margin-bottom:22px;
        }

        .form-group {
            margin-bottom:20px;
        }

        .form-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:18px;
        }

        .field-label {
            display:block;
            color:#49382C;
            font-size:14px;
            font-weight:700;
            margin-bottom:8px;
        }

        .field-input {
            width:100%;
            padding:13px 15px;
            border:1px solid #E8DED2;
            border-radius:10px;
            outline:none;
            background:white;
            color:#49382C;
            box-sizing:border-box;
        }

        .field-input:focus {
            border-color:#A98562;
            box-shadow:0 0 0 3px rgba(169,133,98,.12);
        }

        .required {
            color:#B42318;
        }

        .error-text {
            color:#B42318;
            font-size:13px;
            margin-top:6px;
        }

        .help-text {
            display:block;
            color:#887B70;
            font-size:12px;
            margin-top:7px;
            line-height:1.5;
        }

        .inventory-box {
            background:#FBF9F5;
            border:1px solid #E8DED2;
            border-radius:12px;
            padding:17px;
        }

        .inventory-label {
            color:#887B70;
            font-size:13px;
            font-weight:600;
        }

        .inventory-value {
            color:#49382C;
            font-size:27px;
            font-weight:800;
            margin:8px 0 2px;
        }

        .inventory-box small {
            color:#887B70;
            font-size:12px;
        }

        .notice-box {
            background:#FBF9F5;
            border:1px solid #E8DED2;
            padding:14px;
            border-radius:10px;
            margin-top:18px;
        }

        .status-active {
            display:inline-block;
            background:#E7F2E8;
            color:#376342;
            padding:6px 11px;
            border-radius:20px;
            font-size:12px;
            font-weight:700;
        }

        .status-inactive {
            display:inline-block;
            background:#F8E9E7;
            color:#994B42;
            padding:6px 11px;
            border-radius:20px;
            font-size:12px;
            font-weight:700;
        }

        .status-button,
        .delete-button {
            color:white;
            border:0;
            padding:11px 16px;
            border-radius:9px;
            font-weight:700;
            cursor:pointer;
        }

        .delete-button {
            background:#FCE8E6;
            color:#A33A32;
            border:1px solid #F2C7C3;
        }

        .primary-button {
            background:#70452F;
            color:white;
            border:0;
            padding:13px 24px;
            border-radius:10px;
            font-weight:700;
            cursor:pointer;
        }

        .primary-button:hover {
            background:#593723;
        }

        .secondary-button {
            background:#E8DED2;
            color:#49382C;
            padding:13px 22px;
            border-radius:10px;
            text-decoration:none;
            font-weight:700;
        }

        .success-message {
            background:#EDF5ED;
            color:#315D38;
            padding:14px 18px;
            border-radius:10px;
            margin-bottom:20px;
        }

        .error-message {
            background:#FCE8E6;
            color:#9B2C2C;
            padding:16px 20px;
            border-radius:12px;
            margin-bottom:22px;
        }
    </style>
</x-app-layout>
