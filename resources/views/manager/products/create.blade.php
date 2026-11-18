
<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:900px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom:28px;">
                <p style="font-size:12px; letter-spacing:2px; color:#9A8068; font-weight:700;">
                    PRODUCT MANAGEMENT
                </p>

                <h1 style="font-size:30px; font-weight:800; color:#30241E; margin:5px 0;">
                    Thêm sản phẩm mới
                </h1>

                <p style="color:#887B70; font-size:14px;">
                    Khai báo thông tin, giá bán và thiết lập quản lý tồn kho cho sản phẩm.
                </p>
            </div>

            {{-- Thông báo lỗi --}}
            @if ($errors->any())
                <div style="background:#FCE8E6; color:#9B2C2C; padding:16px 20px; border-radius:12px; margin-bottom:22px;">
                    <strong>Vui lòng kiểm tra lại thông tin:</strong>

                    <ul style="margin:8px 0 0 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('manager.products.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- 01. Thông tin cơ bản --}}
                <div class="form-card">

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
                               value="{{ old('name') }}"
                               placeholder="Ví dụ: Nến thơm Yankee Candle Christmas Cookie"
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
                               value="{{ old('sku') }}"
                               placeholder="Ví dụ: YAN-XMAS-122G"
                               maxlength="100"
                               required>

                        <small class="help-text">
                            Mã định danh riêng của từng sản phẩm, không được trùng lặp.
                        </small>

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

                            <option value="">-- Chọn danh mục --</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                  placeholder="Nhập thông tin, công dụng, chất liệu và đặc điểm sản phẩm...">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- 02. Giá bán và tồn kho --}}
                <div class="form-card">

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
                                   value="{{ old('price') }}"
                                   placeholder="Ví dụ: 240000"
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
                                   value="{{ old('cost_price') }}"
                                   placeholder="Ví dụ: 140000"
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
                               value="{{ old('low_stock_threshold', 5) }}"
                               required>

                        <small class="help-text">
                            Hệ thống cảnh báo khi số lượng có thể bán nhỏ hơn hoặc bằng ngưỡng này.
                        </small>

                        @error('low_stock_threshold')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="notice-box">
                        <p style="font-weight:700; color:#49382C; margin-bottom:6px;">
                            Lưu ý về tồn kho
                        </p>

                        <p style="font-size:13px; color:#887B70; line-height:1.7; margin:0;">
                            Sản phẩm mới được tạo với tồn kho thực tế bằng 0 và số lượng giữ chỗ bằng 0.
                            Để tăng tồn kho, hãy sử dụng chức năng tạo phiếu nhập kho.
                        </p>
                    </div>

                </div>

                {{-- 03. Hình ảnh --}}
                <div class="form-card">

                    <h2 class="section-title">
                        03. Hình ảnh sản phẩm
                    </h2>

                    <label class="field-label">Ảnh đại diện</label>

                    <input class="field-input"
                           type="file"
                           name="image"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                    <small class="help-text">
                        Chấp nhận JPG, JPEG, PNG, WEBP. Dung lượng tối đa 5MB.
                    </small>

                    @error('image')
                        <p class="error-text">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Nút thao tác --}}
                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">

                    <a href="{{ route('manager.products.index') }}"
                       class="secondary-button">
                        Quay lại
                    </a>

                    <button type="submit" class="primary-button">
                        Lưu sản phẩm
                    </button>

                </div>

            </form>
        </div>
    </div>

    <style>
        .form-card {
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

        .help-text {
            display:block;
            color:#887B70;
            font-size:12px;
            margin-top:7px;
            line-height:1.5;
        }

        .error-text {
            color:#B42318;
            font-size:13px;
            margin-top:6px;
        }

        .notice-box {
            background:#FBF9F5;
            border:1px solid #E8DED2;
            padding:15px;
            border-radius:10px;
            margin-top:22px;
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

        .secondary-button:hover {
            background:#DED0C1;
        }
    </style>
</x-app-layout>
