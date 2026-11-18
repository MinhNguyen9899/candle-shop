<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Candle Shop | Nến thơm và quà tặng</title>

    <meta name="description" content="Khám phá bộ sưu tập nến thơm và quà tặng tinh tế tại Candle Shop.">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --cream: #F7F2EA;
            --white: #FFFCF8;
            --brown: #30241E;
            --brown-light: #70452F;
            --gold: #B79A73;
            --muted: #85776B;
            --border: #E9DFD3;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--brown);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: min(1200px, 90%);
            margin: auto;
        }

        /* THANH ĐIỀU HƯỚNG */
        .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand img {
            height: 65px;
            width: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .nav-links a:hover {
            color: var(--gold);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-btn {
            font-size: 14px;
            font-weight: 600;
        }

        .account-btn {
            padding: 11px 20px;
            background: var(--brown);
            color: white;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .menu-toggle {
            display: none;
            border: 0;
            background: none;
            font-size: 26px;
            cursor: pointer;
            color: var(--brown);
        }

        /* BANNER */
        .hero {
            min-height: 570px;
            background:
                radial-gradient(ellipse at 80% 45%, rgba(183,154,115,.25), transparent 35%),
                linear-gradient(110deg, #EDE1D1, #F6EDE2 55%, #E6D6C2);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            align-items: center;
            gap: 40px;
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .hero-label {
            color: var(--brown-light);
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 5vw, 66px);
            line-height: 1.15;
            font-weight: 600;
            max-width: 650px;
            margin-bottom: 24px;
        }

        .hero h1 span {
            color: #96734E;
            font-style: italic;
        }

        .hero-description {
            max-width: 490px;
            font-size: 15px;
            line-height: 1.9;
            color: var(--muted);
            margin-bottom: 32px;
        }

        .primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--brown);
            color: white;
            padding: 15px 28px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .5px;
            transition: .25s;
        }

        .primary-btn:hover {
            background: var(--brown-light);
            transform: translateY(-2px);
        }

        .hero-art {
            min-height: 390px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .hero-circle {
            width: min(350px, 85%);
            aspect-ratio: 1;
            border-radius: 50%;
            background: linear-gradient(145deg, #D5BFA5, #B89A78);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 25px 60px rgba(74,49,31,.13);
        }

        .candle-illustration {
            width: 150px;
            height: 195px;
            border-radius: 18px 18px 25px 25px;
            background: linear-gradient(90deg, #F5EBDD, #FFFDF8 45%, #E6D7C4);
            box-shadow: 15px 20px 35px rgba(62,40,25,.2);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .candle-illustration:before {
            content: '';
            position: absolute;
            width: 7px;
            height: 30px;
            background: #49372B;
            top: -22px;
            border-radius: 50% 50% 0 0;
        }

        .candle-flame {
            position: absolute;
            width: 25px;
            height: 38px;
            background: #D99C4C;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            top: -58px;
            box-shadow: 0 0 35px rgba(217,156,76,.6);
        }

        .candle-label {
            text-align: center;
            border-top: 1px solid #B79A73;
            border-bottom: 1px solid #B79A73;
            padding: 12px 8px;
            color: var(--brown);
        }

        .candle-label strong {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            letter-spacing: 2px;
        }

        .candle-label small {
            font-size: 8px;
            letter-spacing: 2px;
        }

        /* TIÊU ĐỀ CÁC KHU VỰC */
        .section {
            padding: 85px 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 42px;
        }

        .section-heading small {
            color: var(--gold);
            font-size: 11px;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .section-heading h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 600;
            margin: 12px 0;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        /* DANH MỤC */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .category-card {
            min-height: 175px;
            background: #EDE3D7;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            overflow: hidden;
            transition: .25s;
            border: 1px solid transparent;
        }

        .category-card:nth-child(2) {
            background: #E8DFD2;
        }

        .category-card:nth-child(3) {
            background: #E6DCD0;
        }

        .category-card:nth-child(4) {
            background: #EFE4D6;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
        }

        .category-number {
            font-family: 'Playfair Display', serif;
            font-size: 50px;
            color: rgba(112,69,47,.13);
            position: absolute;
            top: 12px;
            right: 20px;
        }

        .category-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            margin-bottom: 10px;
            position: relative;
        }

        .category-card span:last-child {
            font-size: 12px;
            color: var(--brown-light);
            font-weight: 600;
        }

        /* SẢN PHẨM */
        .product-section {
            background: var(--white);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .product-card {
            background: white;
            border: 1px solid var(--border);
            transition: .25s;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(48,36,30,.08);
        }

        .product-image {
            height: 255px;
            background: #F1EAE0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .4s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .no-image {
            color: #A18B75;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
        }

        .product-info {
            padding: 19px;
        }

        .product-category {
            font-size: 11px;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .product-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 600;
            margin: 9px 0;
            min-height: 44px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 16px;
            font-weight: 800;
            color: var(--brown-light);
            margin-bottom: 14px;
        }

        .product-link {
            display: block;
            text-align: center;
            padding: 11px;
            border: 1px solid var(--brown);
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .product-link:hover {
            background: var(--brown);
            color: white;
        }

        .empty-products {
            grid-column: 1 / -1;
            text-align: center;
            color: var(--muted);
            padding: 45px 15px;
            background: var(--cream);
        }

        .view-all {
            text-align: center;
            margin-top: 40px;
        }

        /* GIỚI THIỆU */
        .about {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            align-items: center;
            gap: 65px;
        }

        .about-art {
            min-height: 340px;
            background: linear-gradient(145deg, #E5D6C3, #C7AC8C);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-art span {
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            color: rgba(72,48,33,.75);
            text-align: center;
            line-height: 1.3;
        }

        .about-content small {
            color: var(--gold);
            font-size: 11px;
            letter-spacing: 3px;
            font-weight: 700;
        }

        .about-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            line-height: 1.25;
            margin: 17px 0;
        }

        .about-content p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        /* CHÂN TRANG */
        footer {
            background: #30241E;
            color: #F7F2EA;
            padding: 55px 0 22px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 50px;
            margin-bottom: 40px;
        }

        .footer-grid h3 {
            font-family: 'Playfair Display', serif;
            font-size: 21px;
            margin-bottom: 17px;
        }

        .footer-grid p,
        .footer-grid a {
            color: #CBBBAA;
            font-size: 13px;
            line-height: 2;
        }

        .footer-grid a {
            display: block;
        }

        .footer-grid a:hover {
            color: white;
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,.15);
            padding-top: 20px;
            text-align: center;
            color: #BDAE9F;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media(max-width: 900px) {
            .nav-links {
                gap: 15px;
            }

            .hero-inner {
                grid-template-columns: 1fr 1fr;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .about {
                gap: 30px;
            }
        }

        @media(max-width: 650px) {
            .nav-inner {
                min-height: 70px;
                flex-wrap: wrap;
                padding: 8px 0;
            }

            .brand img {
                height: 52px;
            }

            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 0;
                border-top: 1px solid var(--border);
            }

            .nav-links.show {
                display: flex;
            }

            .nav-actions {
                margin-left: auto;
            }

            .login-btn {
                font-size: 12px;
            }

            .account-btn {
                padding: 9px 12px;
                font-size: 11px;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 55px;
            }

            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-art {
                min-height: 300px;
            }

            .hero-circle {
                width: 260px;
            }

            .candle-illustration {
                transform: scale(.85);
            }

            .section {
                padding: 60px 0;
            }

            .section-heading h2 {
                font-size: 29px;
            }

            .category-grid {
                gap: 12px;
            }

            .category-card {
                min-height: 145px;
                padding: 18px 13px;
            }

            .category-card h3 {
                font-size: 17px;
            }

            .product-grid {
                gap: 12px;
            }

            .product-image {
                height: 175px;
            }

            .product-info {
                padding: 12px;
            }

            .product-info h3 {
                font-size: 15px;
            }

            .about {
                grid-template-columns: 1fr;
            }

            .about-art {
                min-height: 240px;
            }

            .about-content h2 {
                font-size: 31px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }
        }
    </style>
</head>

<body>

    {{-- THANH ĐIỀU HƯỚNG --}}
    <header class="navbar">
        <div class="container nav-inner">

            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Candle Shop">
            </a>

            <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('show')" aria-label="Mở menu">
                ☰
            </button>

            <nav class="nav-links">
                <a href="{{ route('home') }}">Trang chủ</a>
                <a href="#categories">Danh mục</a>
                <a href="#products">Sản phẩm</a>
                <a href="#about">Về chúng tôi</a>
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="account-btn">Tài khoản</a>
                @else
                    <a href="{{ route('login') }}" class="login-btn">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="account-btn">Đăng ký</a>
                @endauth
            </div>

        </div>
    </header>

    {{-- BANNER CHÍNH --}}
    <section class="hero">
        <div class="container hero-inner">

            <div>
                <div class="hero-label">Candle Shop · Scented Candles</div>

                <h1>
                    Thắp sáng không gian,
                    <span>lan tỏa cảm xúc.</span>
                </h1>

                <p class="hero-description">
                    Khám phá thế giới hương thơm tinh tế tại Candle Shop.
                    Những sản phẩm nến thơm giúp bạn thư giãn, tận hưởng
                    khoảnh khắc bình yên và làm đẹp không gian sống mỗi ngày.
                </p>

                <a href="{{ route('customer.products.index') }}" class="primary-btn">
                    KHÁM PHÁ SẢN PHẨM →
                </a>
            </div>

            <div class="hero-art">
                <div class="hero-circle">
                    <div class="candle-illustration">
                        <div class="candle-flame"></div>
                        <div class="candle-label">
                            <strong>CANDLE</strong>
                            <small>LIGHT YOUR LIFE</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- DANH MỤC SẢN PHẨM --}}
    <section class="section" id="categories">
        <div class="container">

            <div class="section-heading">
                <small>Explore our collection</small>
                <h2>Khám phá danh mục</h2>
                <p>Tìm kiếm những sản phẩm phù hợp với phong cách và không gian của bạn.</p>
            </div>

            <div class="category-grid">

                @forelse($categories as $index => $category)
                    <a href="{{ route('customer.products.index', ['category_id' => $category->id]) }}"
                       class="category-card">

                        <span class="category-number">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <h3>{{ $category->name }}</h3>
                        <span>Khám phá ngay →</span>
                    </a>
                @empty
                    <div class="empty-products">
                        Danh mục sản phẩm đang được cập nhật.
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- SẢN PHẨM NỔI BẬT --}}
    <section class="section product-section" id="products">
        <div class="container">

            <div class="section-heading">
                <small>Our products</small>
                <h2>Sản phẩm nổi bật</h2>
                <p>Những sản phẩm mới nhất đang có tại Candle Shop.</p>
            </div>

            <div class="product-grid">

                @forelse($products as $product)

                    @php
                        $available = max(
                            0,
                            (int) ($product->inventory?->on_hand_qty ?? 0)
                            - (int) ($product->inventory?->reserved_qty ?? 0)
                        );
                    @endphp

                    <article class="product-card">

                        <a href="{{ route('customer.products.show', $product->slug) }}">

                            <div class="product-image">
                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                    >
                                @else
                                    <span class="no-image">Candle Shop</span>
                                @endif
                            </div>

                        </a>

                        <div class="product-info">

                            <div class="product-category">
                                {{ $product->category?->name ?? 'Sản phẩm' }}
                            </div>

                            <h3>{{ $product->name }}</h3>

                            <div class="product-price">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </div>

                            <a href="{{ route('customer.products.show', $product->slug) }}"
                               class="product-link">
                                XEM CHI TIẾT
                            </a>

                        </div>
                    </article>

                @empty
                    <div class="empty-products">
                        Hiện chưa có sản phẩm đang kinh doanh.
                    </div>
                @endforelse

            </div>

            <div class="view-all">
                <a href="{{ route('customer.products.index') }}" class="primary-btn">
                    XEM TẤT CẢ SẢN PHẨM →
                </a>
            </div>

        </div>
    </section>

    {{-- GIỚI THIỆU THƯƠNG HIỆU --}}
    <section class="section" id="about">
        <div class="container about">

            <div class="about-art">
                <span>
                    CANDLE<br>
                    SHOP
                </span>
            </div>

            <div class="about-content">

                <small>Our story</small>

                <h2>Một chút hương thơm, một khoảng trời bình yên.</h2>

                <p>
                    Candle Shop mong muốn mang đến những sản phẩm nến thơm
                    giúp mỗi người tìm thấy những phút giây thư giãn giữa
                    nhịp sống bận rộn.
                </p>

                <p>
                    Từ những mùi hương nhẹ nhàng đến những món quà tinh tế,
                    mỗi sản phẩm đều góp phần tạo nên một không gian sống
                    ấm áp và đầy cảm hứng.
                </p>

                <a href="{{ route('customer.products.index') }}" class="primary-btn">
                    TÌM HIỂU SẢN PHẨM →
                </a>

            </div>

        </div>
    </section>

    {{-- CHÂN TRANG --}}
    <footer>
        <div class="container">

            <div class="footer-grid">

                <div>
                    <h3>Candle Shop</h3>
                    <p>
                        Nến thơm và những món quà tinh tế dành cho
                        không gian sống của bạn.
                    </p>
                </div>

                <div>
                    <h3>Khám phá</h3>
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="#categories">Danh mục sản phẩm</a>
                    <a href="{{ route('customer.products.index') }}">Tất cả sản phẩm</a>
                </div>

                <div>
                    <h3>Tài khoản</h3>

                    @auth
                        <a href="{{ route('dashboard') }}">Tài khoản của tôi</a>
                    @else
                        <a href="{{ route('login') }}">Đăng nhập</a>
                        <a href="{{ route('register') }}">Đăng ký</a>
                    @endauth
                </div>

            </div>

            <div class="copyright">
                © {{ date('Y') }} CANDLE SHOP. ALL RIGHTS RESERVED.
            </div>

        </div>
    </footer>

</body>
</html>
