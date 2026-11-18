<x-app-layout>

    @php
        // Đếm số loại sản phẩm khác nhau trong giỏ hàng
        $cartCount = count(session()->get('cart_products', []));
    @endphp

    <style>
        .shop-page {
            background: #F7F2EA;
            min-height: 100vh;
            padding: 40px 24px 70px;
        }

        .shop-container {
            max-width: 1320px;
            margin: auto;
        }

        .shop-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 35px;
        }

        .shop-eyebrow {
            color: #B79A73;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            margin-bottom: 12px;
        }

        .shop-title {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 500;
            color: #30241E;
        }

        .shop-subtitle {
            color: #887B70;
            font-size: 13px;
            margin-top: 10px;
        }

        .shop-cart-btn {
            background: #30241E;
            color: white;
            padding: 13px 23px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            white-space: nowrap;
        }

        .shop-cart-btn:hover {
            background: #70452F;
        }

        .shop-filter {
            background: white;
            border: 1px solid #E8DED2;
            padding: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .shop-filter input,
        .shop-filter select {
            min-height: 45px;
            border: 1px solid #E8DED2;
            border-radius: 0;
            padding: 10px 14px;
            color: #30241E;
            background: #FBF9F5;
            font-size: 13px;
        }

        .shop-filter input {
            flex: 1;
            min-width: 220px;
        }

        .shop-filter select {
            min-width: 210px;
        }

        .shop-filter input:focus,
        .shop-filter select:focus {
            outline: none;
            border-color: #B79A73;
            --tw-ring-shadow: 0 0 #0000;
        }

        .shop-filter-btn {
            background: #70452F;
            color: white;
            border: none;
            padding: 0 23px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .shop-reset-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #F0E9E0;
            color: #70452F;
            padding: 0 20px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }

        .shop-result-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .shop-result-heading h2 {
            font-family: 'Playfair Display', serif;
            font-size: 23px;
            color: #30241E;
        }

        .shop-result-count {
            color: #887B70;
            font-size: 12px;
        }

        .shop-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .shop-product {
            background: white;
            border: 1px solid #E8DED2;
            overflow: hidden;
            transition: .3s ease;
        }

        .shop-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 30px rgba(48,36,30,.09);
        }

        .shop-image-link {
            display: block;
            overflow: hidden;
            background: #F0E9E0;
            text-decoration: none;
        }

        .shop-product-image {
            width: 100%;
            height: 265px;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }

        .shop-product:hover .shop-product-image {
            transform: scale(1.045);
        }

        .shop-no-image {
            height: 265px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #A1846B;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .shop-product-info {
            padding: 19px;
        }

        .shop-category {
            color: #A1846B;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 9px;
            text-transform: uppercase;
        }

        .shop-product-name {
            color: #30241E;
            font-family: 'DM Sans', sans-serif;
            font-size: 17px;
            font-weight: 600;
            line-height: 1.5;
            letter-spacing: -0.3px;
            min-height: 53px;
            margin-bottom: 12px;
        }

        .shop-product-meta {
            color: #887B70;
            font-size: 11px;
            line-height: 1.8;
            margin-bottom: 10px;
            min-height: 40px;
        }

        .shop-price {
            color: #70452F;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .shop-stock {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            margin-bottom: 17px;
        }

        .shop-stock-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .shop-detail-btn {
            display: block;
            text-align: center;
            background: #30241E;
            color: white;
            padding: 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-decoration: none;
            transition: .25s;
        }

        .shop-detail-btn:hover {
            background: #70452F;
            color: white;
        }

        .shop-empty {
            grid-column: 1 / -1;
            background: white;
            border: 1px solid #E8DED2;
            padding: 65px 20px;
            text-align: center;
            color: #887B70;
        }

        .shop-empty h3 {
            font-family: 'Playfair Display', serif;
            color: #30241E;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .shop-alert {
            padding: 13px 17px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .shop-alert-success {
            background: #EAF2EA;
            color: #52765B;
        }

        .shop-alert-error {
            background: #F8E9E7;
            color: #A94442;
        }

        .shop-pagination {
            margin-top: 35px;
        }

        @media(max-width: 1050px) {
            .shop-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media(max-width: 700px) {
            .shop-page {
                padding: 25px 15px 45px;
            }

            .shop-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .shop-title {
                font-size: 31px;
            }

            .shop-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .shop-product-image,
            .shop-no-image {
                height: 175px;
            }

            .shop-product-info {
                padding: 13px;
            }

            .shop-product-name {
                font-size: 15px;
                font-weight: 600;
                min-height: 45px;
            }

            .shop-price {
                font-size: 13px;
            }

            .shop-filter {
                padding: 14px;
            }

            .shop-filter input,
            .shop-filter select,
            .shop-filter-btn,
            .shop-reset-btn {
                width: 100%;
                min-height: 43px;
            }
        }
    </style>

    <div class="shop-page">
        <div class="shop-container">

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="shop-alert shop-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="shop-alert shop-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tiêu đề --}}
            <div class="shop-heading">
                <div>
                    <p class="shop-eyebrow">THE CANDLE COLLECTION</p>
                    <h1 class="shop-title">Khám phá nến thơm</h1>
                    <p class="shop-subtitle">
                        Tìm kiếm hương thơm dành riêng cho không gian của bạn.
                    </p>
                </div>

                @auth
                    <a href="{{ route('customer.cart.index') }}"
                        class="shop-cart-btn"
                        style="display: inline-flex; align-items: center; gap: 11px;">
                @else
                    <a href="{{ route('login') }}"
                        class="shop-cart-btn"
                        style="display: inline-flex; align-items: center; gap: 11px;">
                @endauth

                    <span style="position: relative; display: inline-flex; align-items: center;">

                        {{-- Biểu tượng giỏ hàng --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="23"
                            height="23"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="M2 3h2.2l2.5 12.1a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 1.9-1.5L22 7H5"/>
                            <path d="M7 11h13"/>
                            <path d="M8 7l1 8"/>
                            <path d="M14 7v8"/>
                            <circle cx="9" cy="20" r="1.5"/>
                            <circle cx="18" cy="20" r="1.5"/>

                        </svg>

                        {{-- Số loại sản phẩm trong giỏ --}}
                        <span data-cart-count
                            style="
                                position: absolute;
                                top: -9px;
                                right: -10px;
                                min-width: 18px;
                                height: 18px;
                                padding: 0 4px;
                                border-radius: 50%;
                                background: #A65D3F;
                                color: white;
                                font-size: 10px;
                                font-weight: 700;
                                display: {{ $cartCount > 0 ? 'flex' : 'none' }};
                                align-items: center;
                                justify-content: center;
                            ">
                            {{ $cartCount }}
                        </span>

                    </span>

                    <span>GIỎ HÀNG</span>

                </a>
            </div>

            {{-- Tìm kiếm và lọc --}}
            <form method="GET"
                  action="{{ route('customer.products.index') }}"
                  class="shop-filter">

                <input type="text"
                        name="keyword"
                        value="{{ $keyword ?? '' }}"
                        placeholder="Tìm tên hoặc mã sản phẩm (SKU)...">

                <select name="category_id">
                    <option value="">Tất cả danh mục</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="shop-filter-btn">
                    TÌM KIẾM
                </button>

                <a href="{{ route('customer.products.index') }}"
                   class="shop-reset-btn">
                    XÓA LỌC
                </a>
            </form>

            {{-- Danh sách sản phẩm --}}
            <div class="shop-result-heading">
                <h2>Bộ sưu tập của chúng tôi</h2>
                <span class="shop-result-count">
                    Tìm thấy {{ $products->total() }} sản phẩm
                </span>
            </div>

            <div class="shop-grid">
                @forelse ($products as $product)

                    @php
                        // Số lượng sản phẩm còn có thể bán
                        $availableQuantity = $product->inventory
                            ? (int) $product->inventory->available_qty
                            : 0;
                    @endphp

                    <article class="shop-product">

                        <a href="{{ route('customer.products.show', $product->slug) }}"
                           class="shop-image-link">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="shop-product-image">
                            @else
                                <div class="shop-no-image">
                                    CHƯA CÓ HÌNH ẢNH
                                </div>
                            @endif
                        </a>

                        <div class="shop-product-info">

                            {{-- Danh mục --}}
                            <p class="shop-category">
                                {{ $product->category->name ?? 'Chưa phân loại' }}
                            </p>

                            {{-- Tên sản phẩm --}}
                            <h3 class="shop-product-name">
                                {{ $product->name }}
                            </h3>

                            {{-- Mô tả sản phẩm --}}
                            <div class="shop-product-meta">
                                {{ $product->description
                                    ? \Illuminate\Support\Str::limit(strip_tags($product->description), 100)
                                    : 'Sản phẩm mang đến hương thơm và nét tinh tế cho không gian sống của bạn.' }}
                            </div>

                            {{-- Giá bán --}}
                            <div class="shop-price">
                                {{ number_format((float) $product->price, 0, ',', '.') }} đ
                            </div>

                            {{-- Số lượng còn có thể bán --}}
                            <p class="shop-stock">
                                <span class="shop-stock-dot"
                                      style="background: {{ $availableQuantity > 0 ? '#52765B' : '#A94442' }};">
                                </span>

                                <span style="color: {{ $availableQuantity > 0 ? '#52765B' : '#A94442' }};">
                                    {{ $availableQuantity > 0
                                        ? 'Còn ' . $availableQuantity . ' sản phẩm'
                                        : 'Tạm hết hàng' }}
                                </span>
                            </p>

                            {{-- Xem chi tiết --}}
                            <a href="{{ route('customer.products.show', $product->slug) }}"
                               class="shop-detail-btn">
                                XEM CHI TIẾT →
                            </a>

                        </div>
                    </article>

                @empty
                    <div class="shop-empty">
                        <h3>Chưa tìm thấy sản phẩm</h3>
                        <p>Thử thay đổi từ khóa hoặc danh mục tìm kiếm nhé.</p>
                    </div>
                @endforelse
            </div>

            {{-- Phân trang --}}
            <div class="shop-pagination">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>