<x-app-layout>
    <style>
        .customer-home {
            background: #F7F2EA;
            min-height: 100vh;
            padding: 35px 24px 70px;
            color: #30241E;
        }

        .customer-inner {
            max-width: 1320px;
            margin: auto;
        }

        .luxury-hero {
            position: relative;
            overflow: hidden;
            min-height: 390px;
            display: flex;
            align-items: center;
            padding: 65px;
            border-radius: 5px;
            background: linear-gradient(115deg, #30241E 0%, #59402F 60%, #80634B 100%);
            color: white;
            margin-bottom: 65px;
        }

        .luxury-hero::before {
            content: '';
            position: absolute;
            width: 430px;
            height: 430px;
            border: 1px solid rgba(216,196,172,.25);
            border-radius: 50%;
            right: 8%;
            top: -160px;
        }

        .luxury-hero::after {
            content: '';
            position: absolute;
            width: 310px;
            height: 310px;
            border: 1px solid rgba(216,196,172,.2);
            border-radius: 50%;
            right: 13%;
            bottom: -190px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 650px;
        }

        .hero-eyebrow {
            color: #D8C4AC;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 4px;
            margin-bottom: 22px;
        }

        .hero-title {
            color: #FFF !important;
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 4vw, 49px);
            font-weight: 500;
            line-height: 1.25;
            margin-bottom: 20px;
        }

        .hero-description {
            color: #E8DCCF;
            font-size: 14px;
            line-height: 2;
            max-width: 520px;
            margin-bottom: 30px;
        }

        .luxury-primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #D8C4AC;
            color: #30241E;
            padding: 14px 27px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            transition: .25s;
        }

        .luxury-primary-btn:hover {
            background: #F7F2EA;
            transform: translateY(-2px);
        }

        .section-heading {
            margin-bottom: 25px;
        }

        .section-eyebrow {
            color: #A1846B;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            margin-bottom: 9px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 29px;
            font-weight: 500;
            color: #30241E;
        }

        .section-description {
            color: #887B70;
            font-size: 13px;
            margin-top: 8px;
        }

        .customer-shortcuts {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 65px;
        }

        .shortcut-card {
            display: block;
            padding: 30px;
            background: #FFF;
            border: 1px solid #E8DED2;
            text-decoration: none;
            transition: .3s;
        }

        .shortcut-card:hover {
            border-color: #B79A73;
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(48,36,30,.07);
        }

        .shortcut-number {
            color: #B79A73;
            font-family: 'Playfair Display', serif;
            font-size: 27px;
            margin-bottom: 22px;
        }

        .shortcut-label {
            color: #A1846B;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .shortcut-title {
            color: #30241E;
            font-family: 'Playfair Display', serif;
            font-size: 21px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .shortcut-description {
            color: #887B70;
            font-size: 13px;
            line-height: 1.8;
            min-height: 48px;
        }

        .shortcut-link {
            color: #70452F;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 23px;
        }

        .customer-promo {
            background: #EDE3D7;
            padding: 40px 45px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .promo-title {
            color: #30241E;
            font-family: 'Playfair Display', serif;
            font-size: 25px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .promo-description {
            color: #786858;
            font-size: 13px;
            line-height: 1.8;
        }

        .promo-btn {
            display: inline-block;
            flex-shrink: 0;
            background: #70452F;
            color: white;
            padding: 14px 25px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            transition: .25s;
        }

        .promo-btn:hover {
            background: #30241E;
        }

        @media (max-width: 768px) {
            .customer-home {
                padding: 20px 15px 45px;
            }

            .luxury-hero {
                min-height: 350px;
                padding: 35px 25px;
                margin-bottom: 45px;
            }

            .customer-shortcuts {
                grid-template-columns: 1fr;
                gap: 13px;
                margin-bottom: 45px;
            }

            .shortcut-card {
                padding: 23px;
            }

            .shortcut-number {
                margin-bottom: 12px;
            }

            .customer-promo {
                padding: 28px 23px;
                align-items: flex-start;
                flex-direction: column;
            }

            .section-title {
                font-size: 25px;
            }
        }
    </style>

    <div class="customer-home">
        <div class="customer-inner">

            {{-- Banner chào mừng --}}
            <section class="luxury-hero">
                <div class="hero-content">
                    <p class="hero-eyebrow">THE ART OF SCENTED LIVING</p>

                    <h1 class="hero-title">
                        Xin chào,<br>
                        {{ auth()->user()->name }}.
                    </h1>

                    <p class="hero-description">
                        Hương thơm không chỉ làm đẹp không gian,
                        mà còn lưu giữ những khoảnh khắc đáng nhớ.
                        Khám phá thế giới nến thơm và tìm ra mùi hương
                        dành riêng cho bạn.
                    </p>

                    <a href="{{ route('customer.products.index') }}"
                       class="luxury-primary-btn">
                        KHÁM PHÁ BỘ SƯU TẬP &nbsp; →
                    </a>
                </div>
            </section>

            {{-- Truy cập nhanh --}}
            <section>
                <div class="section-heading">
                    <p class="section-eyebrow">YOUR PERSONAL SPACE</p>
                    <h2 class="section-title">Không gian mua sắm của bạn</h2>
                    <p class="section-description">
                        Tất cả trải nghiệm mua sắm được sắp xếp dành riêng cho bạn.
                    </p>
                </div>

                <div class="customer-shortcuts">

                    <a href="{{ route('customer.products.index') }}"
                       class="shortcut-card">
                        <p class="shortcut-number">01</p>
                        <p class="shortcut-label">DISCOVER</p>
                        <h3 class="shortcut-title">Bộ sưu tập nến thơm</h3>
                        <p class="shortcut-description">
                            Khám phá các dòng nến thơm với hương
                            sắc và phong cách đa dạng.
                        </p>
                        <p class="shortcut-link">KHÁM PHÁ NGAY →</p>
                    </a>

                    <a href="{{ route('customer.cart.index') }}"
                       class="shortcut-card">
                        <p class="shortcut-number">02</p>
                        <p class="shortcut-label">YOUR SELECTION</p>
                        <h3 class="shortcut-title">Giỏ hàng của bạn</h3>
                        <p class="shortcut-description">
                            Xem lại những sản phẩm đã lựa chọn
                            và hoàn tất đơn hàng.
                        </p>
                        <p class="shortcut-link">XEM GIỎ HÀNG →</p>
                    </a>

                    <a href="{{ route('customer.orders.index') }}"
                       class="shortcut-card">
                        <p class="shortcut-number">03</p>
                        <p class="shortcut-label">YOUR JOURNEY</p>
                        <h3 class="shortcut-title">Đơn hàng của tôi</h3>
                        <p class="shortcut-description">
                            Theo dõi trạng thái giao hàng và
                            quản lý lịch sử mua sắm.
                        </p>
                        <p class="shortcut-link">THEO DÕI ĐƠN HÀNG →</p>
                    </a>

                </div>
            </section>


            {{-- SẢN PHẨM NỔI BẬT --}}
            <section style="margin-bottom: 65px;">
                <div class="section-heading">
                    <p class="section-eyebrow">OUR COLLECTION</p>
                    <h2 class="section-title">Sản phẩm nổi bật</h2>
                    <p class="section-description">
                        Khám phá những sản phẩm được giới thiệu từ Candle Shop.
                    </p>
                </div>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
                    gap: 22px;
                ">
                    @forelse ($featuredProducts as $product)
                        @php
                            $availableQuantity = $product->inventory
                                ? $product->inventory->available_qty
                                : 0;
                        @endphp

                        <a href="{{ route('customer.products.show', $product->slug) }}"
                        style="
                                display: block;
                                background: white;
                                border: 1px solid #E8DED2;
                                text-decoration: none;
                                color: inherit;
                        ">

                            <div style="
                                height: 240px;
                                background: #F1EAE1;
                                overflow: hidden;
                            ">
                                @if ($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        style="width:100%; height:100%; object-fit:cover;"
                                    >
                                @else
                                    <div style="
                                        height:100%;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        color:#A1846B;
                                        font-size:13px;
                                    ">
                                        CANDLE SHOP
                                    </div>
                                @endif
                            </div>

                            <div style="padding:20px;">
                                <p style="
                                    color:#A1846B;
                                    font-size:10px;
                                    letter-spacing:2px;
                                    margin-bottom:8px;
                                ">
                                    {{ $product->category?->name ?? 'SẢN PHẨM' }}
                                </p>

                                <h3 style="
                                    color:#30241E;
                                    font-size:17px;
                                    font-weight:600;
                                    margin-bottom:10px;
                                ">
                                    {{ $product->name }}
                                </h3>

                                <p style="
                                    color:#70452F;
                                    font-size:16px;
                                    font-weight:700;
                                ">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </p>

                                <p style="
                                    color:#887B70;
                                    font-size:12px;
                                    margin-top:8px;
                                ">
                                    {{ $availableQuantity > 0
                                        ? 'Còn hàng: ' . $availableQuantity
                                        : 'Tạm hết hàng' }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p style="color:#887B70;">
                            Hiện chưa có sản phẩm nổi bật.
                        </p>
                    @endforelse
                </div>

                <div style="text-align:center; margin-top:28px;">
                    <a href="{{ route('customer.products.index') }}"
                    class="luxury-primary-btn">
                        XEM TẤT CẢ SẢN PHẨM →
                    </a>
                </div>
            </section>

            {{-- Banner cuối trang --}}
            <section class="customer-promo">
                <div>
                    <p class="section-eyebrow">A MOMENT OF TRANQUILITY</p>

                    <h2 class="promo-title">
                        Biến mỗi ngày thành một trải nghiệm đáng nhớ
                    </h2>

                    <p class="promo-description">
                        Một chút ánh nến, một mùi hương dịu nhẹ,
                        một không gian mang dấu ấn của riêng bạn.
                    </p>
                </div>

                <a href="{{ route('customer.products.index') }}"
                   class="promo-btn">
                    MUA SẮM NGAY →
                </a>
            </section>

        </div>
    </div>
</x-app-layout>
