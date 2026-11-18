
<x-app-layout>
    <style>
        .checkout-page {
            min-height: 100vh;
            padding: 42px 22px 70px;
            background: #F7F2EA;
            font-family: 'DM Sans', sans-serif;
        }

        .checkout-container {
            max-width: 1180px;
            margin: auto;
        }

        .checkout-heading {
            margin-bottom: 30px;
        }

        .checkout-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 9px;
        }

        .checkout-heading h1 {
            color: #30241E;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.8px;
        }

        .checkout-heading p:last-child {
            color: #887B70;
            font-size: 14px;
            margin-top: 8px;
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(340px, .85fr);
            gap: 25px;
            align-items: start;
        }

        .checkout-card {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 15px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(48, 36, 30, .035);
        }

        .checkout-card-title {
            color: #30241E;
            font-size: 18px;
            font-weight: 700;
            padding-bottom: 19px;
            margin-bottom: 23px;
            border-bottom: 1px solid #E8DED2;
        }

        .checkout-label {
            display: block;
            color: #514238;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .checkout-input,
        .checkout-select {
            display: block;
            width: 100%;
            min-height: 46px;
            padding: 11px 14px;
            border: 1px solid #E3D9CE;
            border-radius: 8px;
            color: #49382C;
            background: #FFFFFF;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .checkout-input:focus,
        .checkout-select:focus {
            border-color: #A1846B;
            box-shadow: 0 0 0 3px rgba(161, 132, 107, .10);
        }

        .checkout-select {
            cursor: pointer;
        }

        .checkout-select:disabled {
            background: #F5F2EE;
            color: #A99E93;
            cursor: not-allowed;
        }

        textarea.checkout-input {
            resize: vertical;
            min-height: 95px;
        }

        .checkout-field {
            margin-bottom: 20px;
        }

        .checkout-field-error {
            color: #B5473C;
            font-size: 12px;
            margin-top: 6px;
        }

        .checkout-save-address {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 14px 15px;
            background: #FBF9F5;
            border: 1px solid #E8DED2;
            border-radius: 9px;
            cursor: pointer;
        }

        .checkout-save-address input {
            width: 16px;
            height: 16px;
            margin-top: 3px;
            accent-color: #70452F;
            cursor: pointer;
        }

        .checkout-save-address span {
            color: #66594D;
            font-size: 12px;
            line-height: 1.7;
        }

        .checkout-payment {
            padding: 18px;
            margin: 25px 0;
            background: #FBF9F5;
            border: 1px solid #E8DED2;
            border-radius: 10px;
        }

        .checkout-payment-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .checkout-payment-heading strong {
            color: #30241E;
            font-size: 14px;
            font-weight: 700;
        }

        .checkout-payment-method {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px;
            background: #FFFFFF;
            border: 1px solid #D8C4AC;
            border-radius: 8px;
        }

        .checkout-payment-radio {
            width: 16px;
            height: 16px;
            accent-color: #70452F;
        }

        .checkout-payment-method strong {
            display: block;
            color: #49382C;
            font-size: 13px;
        }

        .checkout-payment-method small {
            display: block;
            color: #887B70;
            font-size: 11px;
            margin-top: 4px;
        }

        .checkout-submit {
            width: 100%;
            min-height: 49px;
            padding: 13px 18px;
            border: none;
            border-radius: 8px;
            background: #49382C;
            color: #FFFFFF;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .25s;
        }

        .checkout-submit:hover {
            background: #70452F;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(73, 56, 44, .15);
        }

        .checkout-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            padding: 17px 0;
            border-bottom: 1px solid #F0EBE5;
        }

        .checkout-item-name {
            color: #49382C;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.6;
        }

        .checkout-item-info {
            color: #928477;
            font-size: 11px;
            margin-top: 5px;
        }

        .checkout-item-price {
            color: #66594D;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .checkout-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            color: #887B70;
            font-size: 13px;
            margin-top: 17px;
        }

        .checkout-summary-row strong {
            color: #49382C;
            font-weight: 600;
            white-space: nowrap;
        }

        .checkout-grand-total {
            border-top: 1px solid #E8DED2;
            margin-top: 22px;
            padding-top: 21px;
        }

        .checkout-grand-total span {
            color: #30241E;
            font-size: 14px;
            font-weight: 700;
        }

        .checkout-grand-total strong {
            color: #A46D3E;
            font-size: 23px;
            font-weight: 700;
            white-space: nowrap;
        }

        .checkout-total-note {
            color: #928477;
            font-size: 11px;
            line-height: 1.7;
            margin-top: 10px;
        }

        .checkout-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #8B684A;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 25px;
            transition: .2s;
        }

        .checkout-back:hover {
            color: #30241E;
        }

        .checkout-error {
            background: #FCEBEA;
            color: #A33F36;
            border: 1px solid #F2D0CD;
            padding: 15px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .checkout-error p + p {
            margin-top: 5px;
        }

        .checkout-summary-card {
            position: sticky;
            top: 25px;
        }

        @media (max-width: 850px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }

            .checkout-summary-card {
                position: static;
            }
        }

        @media (max-width: 480px) {
            .checkout-page {
                padding: 28px 14px 45px;
            }

            .checkout-card {
                padding: 19px;
            }

            .checkout-heading h1 {
                font-size: 25px;
            }

            .checkout-grand-total strong {
                font-size: 19px;
            }
        }
    </style>

    <div class="checkout-page">
        <div class="checkout-container">

            {{-- Tiêu đề --}}
            <div class="checkout-heading">
                <p class="checkout-eyebrow">ORDER CHECKOUT</p>
                <h1>Xác nhận đặt hàng</h1>
                <p>Kiểm tra thông tin giao hàng và hoàn tất đơn hàng của bạn.</p>
            </div>

            {{-- Thông báo lỗi --}}
            @if ($errors->any())
                <div class="checkout-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="checkout-layout">

                {{-- Thông tin người nhận --}}
                <div class="checkout-card">
                    <h2 class="checkout-card-title">Thông tin người nhận</h2>

                    <form action="{{ route('customer.checkout.store') }}" method="POST">
                        @csrf

                        <div class="checkout-field">
                            <label class="checkout-label">Họ và tên người nhận *</label>
                            <input
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name', auth()->user()->name) }}"
                                required
                                maxlength="255"
                                autocomplete="name"
                                class="checkout-input"
                                placeholder="Nhập họ và tên người nhận"
                            >
                            @error('customer_name')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label">Số điện thoại *</label>
                            <input
                                type="tel"
                                name="customer_phone"
                                value="{{ old('customer_phone', auth()->user()->phone) }}"
                                required
                                maxlength="20"
                                autocomplete="tel"
                                class="checkout-input"
                                placeholder="Nhập số điện thoại"
                            >
                            @error('customer_phone')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Địa chỉ nhận hàng --}}
                        <div class="checkout-field">
                            <label class="checkout-label">Tỉnh / Thành phố *</label>
                            <select name="province_id" id="province" required class="checkout-select">
                                <option value="">-- Chọn tỉnh / thành phố --</option>
                                @foreach ($provinces as $province)
                                    <option
                                        value="{{ $province['id'] }}"
                                        {{ old('province_id', $defaultAddress['province_id']) == $province['id'] ? 'selected' : '' }}
                                    >
                                        {{ $province['name_local'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label">Phường / Xã *</label>
                            <select name="ward_id" id="ward" required class="checkout-select" disabled>
                                <option value="">-- Chọn phường / xã --</option>
                            </select>
                            @error('ward_id')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label">Số nhà, tên đường *</label>
                            <input
                                type="text"
                                name="street_address"
                                value="{{ old('street_address', $defaultAddress['street_address']) }}"
                                required
                                maxlength="500"
                                autocomplete="street-address"
                                class="checkout-input"
                                placeholder="Ví dụ: Số 25, đường Nguyễn Trãi"
                            >
                            @error('street_address')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lưu địa chỉ mặc định --}}
                        <div class="checkout-field">
                            <label class="checkout-save-address">
                                <input
                                    type="checkbox"
                                    name="save_default_address"
                                    value="1"
                                    {{ old('save_default_address') ? 'checked' : '' }}
                                >
                                <span>
                                    Lưu địa chỉ này làm địa chỉ giao hàng mặc định cho lần mua tiếp theo.
                                </span>
                            </label>
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label">Ghi chú đơn hàng</label>
                            <textarea
                                name="note"
                                rows="3"
                                maxlength="1000"
                                class="checkout-input"
                                placeholder="Ghi chú thêm cho cửa hàng (nếu có)"
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <p class="checkout-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="checkout-payment">
                            <div class="checkout-payment-heading">
                                <strong>Phương thức thanh toán</strong>
                            </div>

                            <div class="checkout-payment-method">
                                <input
                                    type="radio"
                                    checked
                                    disabled
                                    class="checkout-payment-radio"
                                >
                                <div>
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <small>Thanh toán trực tiếp cho nhân viên giao hàng khi nhận sản phẩm.</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="checkout-submit">
                            XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </form>
                </div>

                {{-- Tóm tắt đơn hàng --}}
                <div class="checkout-card checkout-summary-card">
                    <h2 class="checkout-card-title">Đơn hàng của bạn</h2>
                    
                    @foreach ($items as $item)
                        @php
                            $product = $item['product'];
                        @endphp

                        <div class="checkout-item">
                            <div>
                                <p class="checkout-item-name">
                                    {{ $product->name }}
                                </p>

                                @if ($product->category)
                                    <p class="checkout-item-info">
                                        Danh mục: {{ $product->category->name }}
                                    </p>
                                @endif

                                <p class="checkout-item-info">
                                    Đơn giá:
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </p>

                                <p class="checkout-item-info">
                                    Số lượng: {{ $item['quantity'] }}
                                </p>
                            </div>

                            <strong class="checkout-item-price">
                                {{ number_format($item['subtotal'], 0, ',', '.') }} đ
                            </strong>
                        </div>
                    @endforeach

                    <div class="checkout-summary-row">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($subtotal, 0, ',', '.') }} đ</strong>
                    </div>

                    <div class="checkout-summary-row">
                        <span>Phí vận chuyển</span>
                        <strong>{{ number_format($shippingFee, 0, ',', '.') }} đ</strong>
                    </div>

                    <div class="checkout-grand-total">
                        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
                            <span>Tổng thanh toán</span>
                            <strong>{{ number_format($total, 0, ',', '.') }} đ</strong>
                        </div>

                        <p class="checkout-total-note">
                            Tổng tiền đã bao gồm phí vận chuyển. Bạn thanh toán khi nhận hàng.
                        </p>
                    </div>

                    <a href="{{ route('customer.cart.index') }}" class="checkout-back">
                        ← Quay lại giỏ hàng
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province');
            const wardSelect = document.getElementById('ward');

            // Danh sách phường/xã được truyền từ Controller
            const wards = @json($wards);

            // Phường/xã đã chọn trước đó nếu form có lỗi
            const oldWardId = @json(old('ward_id', $defaultAddress['ward_id']));

            function loadWards(provinceId, selectedWardId = '') {
                wardSelect.innerHTML =
                    '<option value="">-- Chọn phường / xã --</option>';

                if (!provinceId) {
                    wardSelect.disabled = true;
                    return;
                }

                const filteredWards = wards.filter(function (ward) {
                    return String(ward.parent_id) === String(provinceId);
                });

                filteredWards.forEach(function (ward) {
                    const option = document.createElement('option');

                    option.value = ward.id;
                    option.textContent = ward.name_local;

                    if (String(ward.id) === String(selectedWardId)) {
                        option.selected = true;
                    }

                    wardSelect.appendChild(option);
                });

                wardSelect.disabled = filteredWards.length === 0;
            }

            // Khi người dùng thay đổi tỉnh/thành phố
            provinceSelect.addEventListener('change', function () {
                loadWards(this.value);
            });

            // Khôi phục danh sách phường/xã khi tải lại trang
            loadWards(provinceSelect.value, oldWardId);
        });
    </script>
</x-app-layout>
