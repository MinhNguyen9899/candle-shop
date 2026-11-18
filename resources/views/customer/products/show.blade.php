
<x-app-layout>
    <style>
        .detail-page {
            background: #F7F2EA;
            min-height: 100vh;
            padding: 40px 24px 70px;
        }

        .detail-container {
            max-width: 1150px;
            margin: auto;
        }

        .detail-back {
            display: inline-block;
            color: #887B70;
            font-size: 12px;
            text-decoration: none;
            margin-bottom: 25px;
        }

        .detail-back:hover {
            color: #70452F;
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 55px;
            align-items: center;
            background: white;
            border: 1px solid #E8DED2;
            padding: 40px;
        }

        .detail-main-image,
        .detail-image-placeholder {
            width: 100%;
            height: 500px;
            object-fit: cover;
            background: #F0E9E0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-image-placeholder {
            color: #A1846B;
            font-size: 12px;
            letter-spacing: 2px;
        }

        .detail-category {
            color: #B79A73;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .detail-name {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            line-height: 1.35;
            font-weight: 500;
            color: #30241E;
            margin-bottom: 22px;
        }

        .detail-description {
            color: #887B70;
            font-size: 13px;
            line-height: 2;
            white-space: pre-line;
            margin-bottom: 30px;
        }

        .detail-divider {
            border: 0;
            border-top: 1px solid #E8DED2;
            margin: 25px 0;
        }

        .detail-price {
            color: #70452F;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .detail-stock {
            font-size: 12px;
            font-weight: 600;
            margin-top: -15px;
            margin-bottom: 25px;
        }

        .detail-cart-form {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .detail-quantity {
            width: 95px;
            height: 48px;
            background: white;
            border: 1px solid #E8DED2;
            padding: 8px 12px;
            color: #30241E;
            font-size: 13px;
        }

        .detail-quantity:focus {
            outline: none;
            border-color: #B79A73;
        }

        .detail-add-btn,
        .detail-disabled-btn {
            min-height: 48px;
            padding: 0 25px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .detail-add-btn {
            background: #30241E;
            color: white;
            border: none;
            cursor: pointer;
            transition: .25s;
        }

        .detail-add-btn:hover {
            background: #70452F;
        }

        .detail-disabled-btn {
            background: #D8D0C7;
            color: #786D63;
            border: none;
            cursor: not-allowed;
        }

        .detail-alert {
            padding: 13px 16px;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .detail-alert-success {
            background: #EAF2EA;
            color: #52765B;
        }

        .detail-alert-error {
            background: #F8E9E7;
            color: #A94442;
        }

        @media(max-width: 800px) {
            .detail-page {
                padding: 22px 15px 45px;
            }

            .detail-layout {
                grid-template-columns: 1fr;
                padding: 20px;
                gap: 30px;
            }

            .detail-main-image,
            .detail-image-placeholder {
                height: 350px;
            }

            .detail-name {
                font-size: 29px;
            }
        }

        @media(max-width: 450px) {
            .detail-cart-form {
                align-items: stretch;
            }

            .detail-quantity {
                width: 75px;
            }

            .detail-add-btn {
                flex: 1;
                padding: 0 12px;
            }
        }
    </style>

    <div class="detail-page">
        <div class="detail-container">

            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div class="detail-alert detail-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Thông báo lỗi --}}
            @if (session('error'))
                <div class="detail-alert detail-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Lỗi xác thực dữ liệu --}}
            @if ($errors->any())
                <div class="detail-alert detail-alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Quay lại bộ sưu tập --}}
            <a href="{{ route('customer.products.index') }}"
               class="detail-back">
                ← QUAY LẠI BỘ SƯU TẬP
            </a>

            <div class="detail-layout">

                {{-- Hình ảnh sản phẩm --}}
                <div>
                    @if ($product->image)
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="detail-main-image"
                        >
                    @else
                        <div class="detail-image-placeholder">
                            CHƯA CÓ HÌNH ẢNH
                        </div>
                    @endif
                </div>

                {{-- Thông tin sản phẩm --}}
                <div>

                    {{-- Danh mục --}}
                    <p class="detail-category">
                        {{ $product->category->name ?? 'CANDLE COLLECTION' }}
                    </p>

                    {{-- Tên sản phẩm --}}
                    <h1 class="detail-name">
                        {{ $product->name }}
                    </h1>

                    {{-- Mô tả --}}
                    <div class="detail-description">
                        {{ $product->description ?: 'Sản phẩm mang đến nét tinh tế cho không gian sống và những khoảnh khắc thư giãn của bạn.' }}
                    </div>

                    <hr class="detail-divider">

                    @php
                        // Số lượng thực tế còn có thể bán
                        $available = $product->inventory
                            ? max(0, (int) $product->inventory->available_qty)
                            : 0;
                    @endphp

                    {{-- Giá bán --}}
                    <p class="detail-price">
                        {{ number_format((float) $product->price, 0, ',', '.') }} đ
                    </p>

                    {{-- Số lượng còn có thể bán --}}
                    <p class="detail-stock"
                       style="color: {{ $available > 0 ? '#52765B' : '#A94442' }};">
                        {{ $available > 0
                            ? 'Còn ' . $available . ' sản phẩm'
                            : 'Tạm hết hàng' }}
                    </p>

                    {{-- Thêm sản phẩm vào giỏ hàng --}}
                    @if ($available > 0)

                        @auth

                            {{-- Khách hàng đã đăng nhập --}}
                            <form
                                action="{{ route('customer.cart.add', $product->id) }}"
                                method="POST"
                                class="detail-cart-form"
                            >
                                @csrf

                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ old('quantity', 1) }}"
                                    min="1"
                                    max="{{ min(100, $available) }}"
                                    required
                                    class="detail-quantity"
                                    aria-label="Số lượng sản phẩm"
                                >

                                <button type="submit" class="detail-add-btn">
                                    THÊM VÀO GIỎ HÀNG
                                </button>
                            </form>

                        @else

                            {{-- Khách chưa đăng nhập --}}
                            <div class="detail-cart-form">

                                <a
                                    href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}"
                                    class="detail-add-btn"
                                    style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;"
                                >
                                    ĐĂNG NHẬP ĐỂ MUA HÀNG
                                </a>

                            </div>

                            <p style="font-size: 11px; color: #887B70; margin-top: 12px;">
                                Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.
                            </p>

                        @endauth

                    @else

                        <button type="button"
                                class="detail-disabled-btn"
                                disabled>
                            TẠM HẾT HÀNG
                        </button>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
