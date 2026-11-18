
<x-app-layout>
    <style>
        .cart-page {
            background: #F7F2EA;
            min-height: 100vh;
            padding: 45px 24px 75px;
        }

        .cart-container {
            max-width: 1250px;
            margin: auto;
        }

        .cart-heading {
            margin-bottom: 32px;
        }

        .cart-eyebrow {
            color: #B79A73;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 10px;
        }

        .cart-heading h1 {
            color: #30241E;
            font-family: 'DM Sans', sans-serif;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.8px;
        }

        .cart-heading span {
            display: block;
            color: #887B70;
            font-size: 14px;
            margin-top: 8px;
        }

        .cart-notice {
            padding: 14px 18px;
            margin-bottom: 16px;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .cart-notice-success {
            background: #EAF2EA;
            color: #52765B;
            border-color: #D3E5D4;
        }

        .cart-notice-error {
            background: #F8E9E7;
            color: #A94442;
            border-color: #EED1CE;
        }

        .cart-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            gap: 25px;
            align-items: start;
        }

        .cart-table-wrapper {
            background: white;
            border: 1px solid #E8DED2;
            overflow-x: auto;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 730px;
        }

        .cart-table th {
            background: #FBF9F5;
            color: #70452F;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 18px 13px;
            text-align: center;
        }

        .cart-table th:first-child {
            text-align: left;
            padding-left: 22px;
        }

        .cart-table td {
            padding: 20px 13px;
            border-top: 1px solid #F0EAE3;
            text-align: center;
            color: #66594D;
            font-size: 13px;
            vertical-align: middle;
        }

        .cart-product {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 265px;
            text-align: left;
        }

        .cart-product-image {
            width: 85px;
            height: 95px;
            flex-shrink: 0;
            object-fit: cover;
            background: #F0E9E0;
        }

        .cart-product-name {
            color: #30241E;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
            margin-bottom: 7px;
        }

        .cart-product-info {
            color: #887B70;
            font-size: 11px;
            margin-top: 4px;
        }

        .cart-price {
            white-space: nowrap;
            font-weight: 600;
            color: #70452F;
        }

        .cart-quantity-input {
            width: 72px;
            height: 39px;
            padding: 7px;
            border: 1px solid #E8DED2;
            background: #FBF9F5;
            color: #30241E;
            text-align: center;
            font-size: 13px;
            outline: none;
        }

        .cart-quantity-input:focus {
            border-color: #B79A73;
        }

        .cart-remove-button {
            background: none;
            border: none;
            color: #A94442;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .cart-remove-button:hover {
            color: #873532;
            text-decoration: underline;
        }

        .cart-summary {
            background: white;
            border: 1px solid #E8DED2;
            padding: 25px;
            position: sticky;
            top: 25px;
        }

        .cart-summary-eyebrow {
            color: #B79A73;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .cart-summary-title {
            color: #30241E;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 23px;
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #887B70;
            font-size: 13px;
            padding: 13px 0;
            border-top: 1px solid #F0EAE3;
        }

        .cart-summary-row strong {
            color: #30241E;
            font-weight: 600;
            white-space: nowrap;
        }

        .cart-total {
            border-top: 1px solid #E8DED2;
            margin-top: 8px;
            padding-top: 20px;
            margin-bottom: 22px;
        }

        .cart-total-label {
            color: #30241E;
            font-size: 13px;
            font-weight: 700;
        }

        .cart-total-price {
            display: block;
            color: #70452F;
            font-size: 25px;
            font-weight: 700;
            margin-top: 8px;
            letter-spacing: -.5px;
        }

        .checkout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: #30241E;
            color: white;
            padding: 15px 18px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: .25s ease;
        }

        .checkout-button:hover {
            background: #70452F;
            color: white;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            color: #887B70;
            font-size: 12px;
            text-decoration: none;
            margin-top: 19px;
        }

        .continue-shopping:hover {
            color: #70452F;
        }

        .cart-summary-note {
            color: #A3978B;
            font-size: 11px;
            line-height: 1.7;
            text-align: center;
            margin-top: 20px;
        }

        .cart-stock-warning {
            background: #FFF5E8;
            color: #986B32;
            border: 1px solid #EEDCC2;
            padding: 12px 15px;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .empty-cart {
            background: white;
            border: 1px solid #E8DED2;
            padding: 75px 20px;
            text-align: center;
        }

        .empty-cart h2 {
            color: #30241E;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #887B70;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .shop-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            background: #30241E;
            color: white;
            padding: 14px 25px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: .25s;
        }

        .shop-button:hover {
            background: #70452F;
            color: white;
        }

        @media(max-width: 900px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
                max-width: 100%;
            }
        }

        @media(max-width: 640px) {
            .cart-page {
                padding: 28px 14px 50px;
            }

            .cart-heading h1 {
                font-size: 26px;
            }

            .cart-product-image {
                width: 70px;
                height: 80px;
            }

            .cart-summary {
                padding: 20px;
            }
        }
    </style>

    <div class="cart-page">
        <div class="cart-container">

            {{-- Tiêu đề --}}
            <div class="cart-heading">
                <p class="cart-eyebrow">YOUR SHOPPING CART</p>
                <h1>Giỏ hàng của bạn</h1>
                <span>Kiểm tra những lựa chọn yêu thích trước khi đặt hàng.</span>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="cart-notice cart-notice-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="cart-notice cart-notice-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="cart-notice cart-notice-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (count($items) > 0)

                @php
                    // Kiểm tra sản phẩm có số lượng vượt tồn kho khả dụng
                    $hasUnavailableItems = collect($items)->contains(function ($item) {
                        return (int) $item['quantity'] > (int) $item['available_qty'];
                    });
                @endphp

                <div class="cart-layout">

                    {{-- Danh sách sản phẩm --}}
                    <div class="cart-table-wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>SẢN PHẨM</th>
                                    <th>ĐƠN GIÁ</th>
                                    <th>SỐ LƯỢNG</th>
                                    <th>THÀNH TIỀN</th>
                                    <th>THAO TÁC</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $product = $item['product'];
                                        $availableQuantity = max(0, (int) $item['available_qty']);
                                        $quantity = (int) $item['quantity'];
                                        $isOverStock = $quantity > $availableQuantity;
                                    @endphp

                                    <tr>
                                        {{-- Thông tin sản phẩm --}}
                                        <td>
                                            <div class="cart-product">

                                                @if ($product->image)
                                                    <img
                                                        src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="cart-product-image"
                                                    >
                                                @else
                                                    <div class="cart-product-image"
                                                         style="display:flex;align-items:center;justify-content:center;color:#A1846B;font-size:10px;">
                                                        KHÔNG CÓ ẢNH
                                                    </div>
                                                @endif

                                                <div>
                                                    <p class="cart-product-name">
                                                        {{ $product->name }}
                                                    </p>

                                                    <p class="cart-product-info">
                                                        {{ $product->category->name ?? 'Chưa phân loại' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Đơn giá --}}
                                        <td class="cart-price">
                                            {{ number_format((float) $product->price, 0, ',', '.') }} đ
                                        </td>

                                        {{-- Số lượng --}}
                                        <td>
                                            <form
                                                action="{{ route('customer.cart.update', $product->id) }}"
                                                method="POST"
                                                class="cart-auto-update-form"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $quantity }}"
                                                    min="1"
                                                    max="{{ max(1, min(100, $availableQuantity)) }}"
                                                    required
                                                    class="cart-quantity-input"
                                                    aria-label="Số lượng sản phẩm"
                                                    data-auto-update
                                                >
                                    
                                            </form>

                                            <p style="font-size:10px;color:#887B70;margin-top:7px;">
                                                Còn {{ $availableQuantity }} sản phẩm
                                            </p>

                                            @if ($isOverStock)
                                                <p style="font-size:11px;color:#A94442;margin-top:5px;">
                                                    Số lượng trong giỏ vượt quá tồn kho khả dụng.
                                                </p>
                                            @endif
                                        </td>

                                        {{-- Thành tiền --}}
                                        <td
                                            class="cart-price"
                                            data-product-subtotal="{{ $product->id }}"
                                            style="color:#70452F;font-weight:700;"
                                        >
                                            {{ number_format((float) $item['subtotal'], 0, ',', '.') }} đ
                                        </td>

                                        {{-- Xóa sản phẩm --}}
                                        <td>
                                            <form
                                                action="{{ route('customer.cart.remove', $product->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="cart-remove-button">
                                                    Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tổng quan giỏ hàng --}}
                    <div class="cart-summary">

                        <p class="cart-summary-eyebrow">ORDER SUMMARY</p>

                        <h2 class="cart-summary-title">
                            Tổng quan giỏ hàng
                        </h2>

                        @if ($hasUnavailableItems)
                            <div class="cart-stock-warning">
                                Một hoặc nhiều sản phẩm có số lượng vượt quá tồn kho khả dụng.
                                Vui lòng cập nhật số lượng trước khi đặt hàng.
                            </div>
                        @endif

                        <div class="cart-summary-row">
                            <span>Số lượng sản phẩm</span>
                            <strong data-cart-quantity>
                                {{ collect($items)->sum('quantity') }}
                            </strong>
                        </div>

                        <div class="cart-total">
                            <p class="cart-total-label">Tổng tiền sản phẩm</p>

                            <strong class="cart-total-price" data-cart-total>
                                {{ number_format((float) $total, 0, ',', '.') }} đ
                            </strong>
                        </div>

                        @if (!$hasUnavailableItems)
                            <a
                                href="{{ route('customer.checkout.index') }}"
                                class="checkout-button"
                            >
                                TIẾN HÀNH ĐẶT HÀNG
                                <span>→</span>
                            </a>
                        @else
                            <button type="button"
                                    disabled
                                    style="width:100%;background:#D8D0C7;color:#786D63;padding:15px 18px;border:none;font-size:12px;font-weight:700;letter-spacing:1px;cursor:not-allowed;">
                                CẬP NHẬT SỐ LƯỢNG TRƯỚC
                            </button>
                        @endif

                        <a
                            href="{{ route('customer.products.index') }}"
                            class="continue-shopping"
                        >
                            ← Tiếp tục mua hàng
                        </a>

                        <p class="cart-summary-note">
                            Phương thức thanh toán: COD<br>
                            Phí vận chuyển sẽ được xác định ở bước đặt hàng.
                        </p>
                    </div>

                </div>

            @else

                {{-- Giỏ hàng trống --}}
                <div class="empty-cart">

                    <h2>Giỏ hàng đang trống</h2>

                    <p>
                        Hãy chọn những sản phẩm nến thơm yêu thích của bạn nhé!
                    </p>

                    <a
                        href="{{ route('customer.products.index') }}"
                        class="shop-button"
                    >
                        KHÁM PHÁ SẢN PHẨM
                        <span>→</span>
                    </a>

                </div>

            @endif

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const inputs = document.querySelectorAll('[data-auto-update]');

        // Định dạng tiền Việt Nam.
        function formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
        }

        inputs.forEach(function (input) {

            const form = input.closest('form');

            let timer = null;
            let previousQuantity = input.value;
            let isUpdating = false;

            input.addEventListener('input', function () {

                clearTimeout(timer);

                const quantity = Number(input.value);
                const min = Number(input.min);
                const max = Number(input.max);

                if (
                    input.value === '' ||
                    !Number.isInteger(quantity) ||
                    quantity < min ||
                    quantity > max
                ) {
                    return;
                }

                timer = setTimeout(async function () {

                    if (isUpdating) return;

                    isUpdating = true;
                    input.readOnly = true;

                    try {

                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(
                                data.message || 'Không thể cập nhật giỏ hàng.'
                            );
                        }

                        // Ghi nhận số lượng mới.
                        previousQuantity = String(data.quantity);
                        input.value = data.quantity;

                        // Cập nhật thành tiền sản phẩm.
                        const subtotalElement = document.querySelector(
                            '[data-product-subtotal="' + data.product_id + '"]'
                        );

                        if (subtotalElement) {
                            subtotalElement.textContent = formatMoney(data.subtotal);
                        }

                        // Cập nhật tổng tiền.
                        const totalElement = document.querySelector('[data-cart-total]');

                        if (totalElement) {
                            totalElement.textContent = formatMoney(data.total);
                        }

                        // Cập nhật tổng số lượng sản phẩm.
                        const quantityElement = document.querySelector('[data-cart-quantity]');

                        if (quantityElement) {
                            quantityElement.textContent = data.total_quantity;
                        }

                        // Cập nhật số dòng sản phẩm trên nút Giỏ hàng.
                        if (typeof window.updateCartCount === 'function') {
                            window.updateCartCount(data.item_count);
                        }

                    } catch (error) {

                        input.value = previousQuantity;
                        alert(error.message);

                    } finally {

                        input.readOnly = false;
                        isUpdating = false;

                    }

                }, 500);

            });

        });

    });
    </script>
</x-app-layout>
