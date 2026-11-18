<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:1100px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom:25px;">
                <p style="font-size:12px; letter-spacing:2px; color:#9A8068; font-weight:700;">
                    PRODUCT MANAGEMENT
                </p>

                <h1 style="font-size:28px; font-weight:800; color:#30241E; margin-top:6px;">
                    Chỉnh sửa sản phẩm
                </h1>

                <p style="color:#887B70; margin-top:7px;">
                    Cập nhật thông tin: {{ $product->name }}
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

            {{-- Lỗi xác thực --}}
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

            <form action="{{ route('employee.products.update', $product->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- 01. Thông tin chung --}}
                <div class="section-card">

                    <div class="section-heading">
                        <p class="section-subtitle">GENERAL INFORMATION</p>

                        <h2 class="section-title">
                            01. Thông tin sản phẩm
                        </h2>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="notice-box" style="margin-bottom:23px;">
                        <p style="font-size:13px; color:#66574B; font-weight:700;">
                            Trạng thái kinh doanh
                        </p>

                        <div style="margin-top:9px;">
                            @if ($product->status === 'active')
                                <span class="status-active">
                                    Đang kinh doanh
                                </span>
                            @else
                                <span class="status-inactive">
                                    Ngừng kinh doanh
                                </span>
                            @endif
                        </div>

                        <p class="help-text">
                            Nhân viên chỉ được xem trạng thái.
                            Quyền thay đổi trạng thái thuộc về Quản lý cửa hàng.
                        </p>
                    </div>

                    {{-- Tên sản phẩm --}}
                    <div class="form-group">
                        <label class="field-label">
                            Tên sản phẩm <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $product->name) }}"
                               maxlength="200"
                               required
                               class="field-input">

                        @error('name')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="form-group">
                        <label class="field-label">
                            Mã SKU <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="sku"
                               value="{{ old('sku', $product->sku) }}"
                               maxlength="100"
                               required
                               class="field-input">

                        <small class="help-text">
                            Mã SKU phải là duy nhất cho từng sản phẩm.
                        </small>

                        @error('sku')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Danh mục --}}
                    <div class="form-group">
                        <label class="field-label">
                            Danh mục <span class="required">*</span>
                        </label>

                        <select name="category_id"
                                required
                                class="field-input">

                            <option value="">-- Chọn danh mục --</option>

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

                    {{-- Mô tả --}}
                    <div style="margin-top:20px;">
                        <label class="field-label">
                            Mô tả sản phẩm
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="field-input"
                                  placeholder="Nhập thông tin, công dụng, chất liệu và đặc điểm sản phẩm...">{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- 02. Giá và tồn kho --}}
                <div class="section-card">

                    <div class="section-heading">
                        <p class="section-subtitle">PRICE & INVENTORY</p>

                        <h2 class="section-title">
                            02. Giá bán và quản lý tồn kho
                        </h2>
                    </div>

                    <div class="form-grid">

                        {{-- Giá bán --}}
                        <div>
                            <label class="field-label">
                                Giá bán (VNĐ) <span class="required">*</span>
                            </label>

                            <input type="number"
                                   name="price"
                                   min="0"
                                   step="1000"
                                   value="{{ old('price', $product->price) }}"
                                   required
                                   class="field-input">

                            @error('price')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Giá vốn --}}
                        <div>
                            <label class="field-label">
                                Giá vốn (VNĐ) <span class="required">*</span>
                            </label>

                            <input type="number"
                                   name="cost_price"
                                   min="0"
                                   step="1000"
                                   value="{{ old('cost_price', $product->cost_price) }}"
                                   required
                                   class="field-input">

                            @error('cost_price')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Ngưỡng cảnh báo --}}
                    <div style="margin-top:20px;">
                        <label class="field-label">
                            Ngưỡng cảnh báo tồn kho <span class="required">*</span>
                        </label>

                        <input type="number"
                               name="low_stock_threshold"
                               min="0"
                               value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}"
                               required
                               class="field-input">

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
                        $threshold = $product->low_stock_threshold ?? 5;
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
                                   style="color:{{ $available <= $threshold ? '#B45309' : '#376342' }};">
                                    {{ number_format($available) }}
                                </p>

                                <small>sản phẩm</small>
                            </div>

                        </div>

                        <div class="notice-box" style="margin-top:18px;">
                            <p style="font-size:13px; color:#705C4C; line-height:1.7;">
                                Lưu ý: Nhân viên chỉ được thay đổi ngưỡng cảnh báo tồn kho.
                                Số lượng tồn thực tế và số lượng giữ chỗ được điều chỉnh thông qua
                                phiếu nhập kho và quy trình xử lý đơn hàng.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- 03. Hình ảnh --}}
                <div class="section-card">

                    <div class="section-heading">
                        <p class="section-subtitle">PRODUCT IMAGE</p>

                        <h2 class="section-title">
                            03. Hình ảnh sản phẩm
                        </h2>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label class="field-label">
                            Hình ảnh hiện tại
                        </label>

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
                        <label class="field-label">
                            Chọn hình ảnh mới
                        </label>

                        <input type="file"
                               name="image"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                               class="field-input">

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
                <div style="display:flex; justify-content:flex-end; flex-wrap:wrap; gap:12px;">

                    <a href="{{ route('employee.products.index') }}"
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
            background:white;
            padding:28px;
            border:1px solid #E8DED2;
            border-radius:15px;
            margin-bottom:22px;
            box-shadow:0 8px 25px rgba(48,36,30,.04);
        }

        .section-heading {
            margin-bottom:23px;
        }

        .section-subtitle {
            font-size:12px;
            letter-spacing:1.5px;
            color:#9A8068;
            font-weight:700;
        }

        .section-title {
            font-size:20px;
            font-weight:800;
            color:#49382C;
            margin-top:5px;
        }

        .form-group {
            margin-bottom:22px;
        }

        .form-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:18px;
        }

        .field-label {
            display:block;
            font-weight:700;
            color:#49382C;
            margin-bottom:9px;
            font-size:14px;
        }

        .field-input {
            width:100%;
            padding:13px;
            border:1px solid #E8DED2;
            border-radius:9px;
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
            color:#B34D42;
        }

        .help-text {
            display:block;
            font-size:12px;
            color:#887B70;
            margin-top:8px;
            line-height:1.6;
        }

        .error-text {
            color:#B34D42;
            margin-top:6px;
            font-size:13px;
        }

        .notice-box {
            background:#F7F2EA;
            padding:15px;
            border:1px solid #E8DED2;
            border-radius:10px;
        }

        .status-active,
        .status-inactive {
            display:inline-block;
            padding:7px 13px;
            border-radius:20px;
            font-size:12px;
            font-weight:700;
        }

        .status-active {
            background:#E5F1E7;
            color:#376342;
        }

        .status-inactive {
            background:#FBECEB;
            color:#994B42;
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

        .primary-button {
            background:#70452F;
            color:white;
            border:none;
            padding:13px 24px;
            border-radius:8px;
            cursor:pointer;
            font-weight:700;
        }

        .primary-button:hover {
            background:#593723;
        }

        .secondary-button {
            background:#EEE8E1;
            color:#594638;
            padding:13px 24px;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
        }

        .success-message {
            background:#EDF5ED;
            color:#315D38;
            padding:14px 18px;
            border-radius:10px;
            margin-bottom:20px;
        }

        .error-message {
            background:#FBECEB;
            color:#994B42;
            padding:16px;
            border-radius:10px;
            margin-bottom:20px;
        }
    </style>
</x-app-layout>