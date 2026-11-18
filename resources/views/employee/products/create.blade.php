<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:1000px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom:25px;">
                <p style="font-size:12px; letter-spacing:2px; color:#9A8068; font-weight:700;">
                    PRODUCT MANAGEMENT
                </p>

                <h1 style="font-size:28px; font-weight:800; color:#30241E; margin-top:6px;">
                    Thêm sản phẩm mới
                </h1>

                <p style="color:#887B70; margin-top:7px;">
                    Khai báo thông tin sản phẩm và thiết lập giá bán, giá vốn, tồn kho.
                </p>
            </div>

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

            {{-- Form thêm sản phẩm --}}
            <form action="{{ route('employee.products.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

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
                               value="{{ old('name') }}"
                               placeholder="Ví dụ: Yankee Candle Christmas Cookie"
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
                            Mã SKU dùng để phân biệt và quản lý từng sản phẩm trong kho. Mỗi sản phẩm phải có mã SKU riêng.
                        </small>

                        @error('sku')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="field-label">
                            Danh mục <span class="required">*</span>
                        </label>

                        <select name="category_id"
                                class="field-input"
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

                    <div style="margin-top:20px;">
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
                            Hệ thống sẽ cảnh báo khi số lượng có thể bán nhỏ hơn hoặc bằng ngưỡng này.
                        </small>

                        @error('low_stock_threshold')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="notice-box">
                        <strong style="color:#70452F;">Lưu ý về tồn kho:</strong>

                        <p style="font-size:13px; color:#705C4C; line-height:1.7; margin-top:6px;">
                            Sản phẩm mới sẽ được tạo với số lượng tồn thực tế bằng 0
                            và số lượng giữ chỗ bằng 0.
                            Muốn tăng số lượng hàng trong kho, nhân viên cần tạo phiếu nhập kho.
                        </p>
                    </div>

                </div>

                {{-- 03. Hình ảnh --}}
                <div class="section-card">

                    <h2 class="section-title">
                        03. Hình ảnh sản phẩm
                    </h2>

                    <label class="field-label">
                        Chọn hình ảnh sản phẩm
                    </label>

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

                {{-- 04. Trạng thái --}}
                <div class="section-card">

                    <h2 class="section-title">
                        04. Trạng thái sản phẩm
                    </h2>

                    <div class="notice-box">
                        <p style="font-size:13px; color:#66574B;">
                            <strong>Trạng thái ban đầu:</strong>

                            <span style="color:#376342; font-weight:700;">
                                Đang kinh doanh
                            </span>
                        </p>

                        <p style="font-size:12px; color:#887B70; margin-top:7px;">
                            Sản phẩm mới được tạo ở trạng thái mặc định.
                            Việc thay đổi trạng thái kinh doanh thuộc quyền quản lý của Quản lý cửa hàng.
                        </p>
                    </div>

                </div>

                {{-- Nút thao tác --}}
                <div style="display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px;">

                    <a href="{{ route('employee.products.index') }}"
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
        .section-card {
            background:white;
            border:1px solid #E8DED2;
            border-radius:16px;
            padding:28px;
            margin-bottom:22px;
            box-shadow:0 5px 20px rgba(48,36,30,.03);
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
            font-weight:700;
            color:#49382C;
            margin-bottom:9px;
            font-size:14px;
        }

        .field-input {
            width:100%;
            padding:13px 14px;
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
            color:#887B70;
            font-size:12px;
            margin-top:7px;
            line-height:1.6;
        }

        .error-text {
            color:#B34D42;
            margin-top:6px;
            font-size:13px;
        }

        .notice-box {
            background:#F7F2EA;
            border:1px solid #E8DED2;
            padding:16px;
            border-radius:10px;
            margin-top:20px;
        }

        .primary-button {
            background:#70452F;
            color:white;
            border:none;
            padding:13px 24px;
            border-radius:9px;
            cursor:pointer;
            font-weight:700;
        }

        .primary-button:hover {
            background:#593723;
        }

        .secondary-button {
            background:#EEE8E1;
            color:#594638;
            padding:13px 22px;
            border-radius:9px;
            text-decoration:none;
            font-weight:600;
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