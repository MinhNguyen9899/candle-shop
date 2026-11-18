<x-app-layout>
    <div style="background: #faf8f5; min-height: 100vh; padding: 35px 20px 60px;">

        <div style="max-width: 1200px; margin: auto;">

            {{-- Banner chào mừng --}}
            <div style="
                background: linear-gradient(120deg, #49382c, #80634b);
                border-radius: 18px;
                padding: 45px 40px;
                color: white;
                margin-bottom: 35px;
            ">
                <p style="
                    font-size: 12px;
                    letter-spacing: 3px;
                    font-weight: 600;
                    color: #e6d3bd;
                    margin-bottom: 14px;
                ">
                    WELCOME TO CANDLE SHOP
                </p>

                <h1 style="
                    font-size: 32px;
                    font-weight: 750;
                    margin-bottom: 12px;
                ">
                    Xin chào, {{ auth()->user()->name }}!
                </h1>

                <p style="
                    color: #eee2d5;
                    font-size: 15px;
                    line-height: 1.8;
                    max-width: 650px;
                    margin-bottom: 25px;
                ">
                    Khám phá thế giới nến thơm và lựa chọn
                    những mùi hương phù hợp với không gian của bạn.
                </p>

                <a href="{{ route('customer.products.index') }}"
                   style="
                        display: inline-block;
                        background: #f5e9dc;
                        color: #49382c;
                        padding: 12px 25px;
                        border-radius: 7px;
                        text-decoration: none;
                        font-weight: 700;
                        font-size: 14px;
                   ">
                    Khám phá sản phẩm
                </a>
            </div>


            {{-- Truy cập nhanh --}}
            <div style="margin-bottom: 18px;">
                <p style="
                    color: #a1846b;
                    font-size: 11px;
                    font-weight: 700;
                    letter-spacing: 2px;
                    margin-bottom: 6px;
                ">
                    CUSTOMER CENTER
                </p>

                <h2 style="
                    color: #49382c;
                    font-size: 23px;
                    font-weight: 750;
                ">
                    Không gian mua sắm của bạn
                </h2>
            </div>

            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 18px;
                margin-bottom: 40px;
            ">

                {{-- Sản phẩm --}}
                <a href="{{ route('customer.products.index') }}"
                   style="
                        display: block;
                        background: white;
                        border: 1px solid #eee5db;
                        border-radius: 13px;
                        padding: 24px;
                        text-decoration: none;
                   ">

                    <p style="color: #a1846b; font-size: 12px;
                              font-weight: 700; letter-spacing: 1px;
                              margin-bottom: 12px;">
                        DISCOVER
                    </p>

                    <h3 style="color: #49382c; font-size: 18px;
                               font-weight: 700; margin-bottom: 8px;">
                        Khám phá sản phẩm
                    </h3>

                    <p style="color: #88796b; font-size: 13px; line-height: 1.7;">
                        Xem các dòng nến thơm và lựa chọn sản phẩm yêu thích.
                    </p>

                    <p style="color: #9a6844; font-size: 13px;
                              font-weight: 700; margin-top: 18px;">
                        Xem sản phẩm →
                    </p>
                </a>


                {{-- Giỏ hàng --}}
                <a href="{{ route('customer.cart.index') }}"
                   style="
                        display: block;
                        background: white;
                        border: 1px solid #eee5db;
                        border-radius: 13px;
                        padding: 24px;
                        text-decoration: none;
                   ">

                    <p style="color: #a1846b; font-size: 12px;
                              font-weight: 700; letter-spacing: 1px;
                              margin-bottom: 12px;">
                        YOUR CART
                    </p>

                    <h3 style="color: #49382c; font-size: 18px;
                               font-weight: 700; margin-bottom: 8px;">
                        Giỏ hàng
                    </h3>

                    <p style="color: #88796b; font-size: 13px; line-height: 1.7;">
                        Kiểm tra sản phẩm đã chọn và tiến hành đặt hàng.
                    </p>

                    <p style="color: #9a6844; font-size: 13px;
                              font-weight: 700; margin-top: 18px;">
                        Xem giỏ hàng →
                    </p>
                </a>


                {{-- Đơn hàng --}}
                <a href="{{ route('customer.orders.index') }}"
                   style="
                        display: block;
                        background: white;
                        border: 1px solid #eee5db;
                        border-radius: 13px;
                        padding: 24px;
                        text-decoration: none;
                   ">

                    <p style="color: #a1846b; font-size: 12px;
                              font-weight: 700; letter-spacing: 1px;
                              margin-bottom: 12px;">
                        ORDER HISTORY
                    </p>

                    <h3 style="color: #49382c; font-size: 18px;
                               font-weight: 700; margin-bottom: 8px;">
                        Đơn hàng của tôi
                    </h3>

                    <p style="color: #88796b; font-size: 13px; line-height: 1.7;">
                        Theo dõi trạng thái và xem lại lịch sử mua hàng.
                    </p>

                    <p style="color: #9a6844; font-size: 13px;
                              font-weight: 700; margin-top: 18px;">
                        Xem đơn hàng →
                    </p>
                </a>

            </div>


            {{-- Danh mục sản phẩm --}}
            <div style="margin-bottom: 35px;">

                <p style="
                    color: #a1846b;
                    font-size: 11px;
                    font-weight: 700;
                    letter-spacing: 2px;
                    margin-bottom: 6px;
                ">
                    OUR COLLECTION
                </p>

                <h2 style="
                    color: #49382c;
                    font-size: 23px;
                    font-weight: 750;
                    margin-bottom: 18px;
                ">
                    Danh mục sản phẩm
                </h2>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 14px;
                ">

                    @forelse ($categories as $category)

                        <a href="{{ route('customer.products.index', ['category_id' => $category->id]) }}"
                           style="
                                background: white;
                                border: 1px solid #eee5db;
                                border-radius: 10px;
                                padding: 20px;
                                text-decoration: none;
                           ">

                            <h3 style="
                                color: #49382c;
                                font-size: 16px;
                                font-weight: 700;
                                margin-bottom: 7px;
                            ">
                                {{ $category->name }}
                            </h3>

                            <p style="color: #9a6844; font-size: 13px;">
                                Khám phá danh mục →
                            </p>

                        </a>

                    @empty

                        <p style="color: #88796b;">
                            Hiện chưa có danh mục sản phẩm.
                        </p>

                    @endforelse

                </div>
            </div>


            {{-- Sản phẩm mới nhất --}}
            <div>

                <div style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 12px;
                    margin-bottom: 20px;
                ">

                    <div>
                        <p style="
                            color: #a1846b;
                            font-size: 11px;
                            font-weight: 700;
                            letter-spacing: 2px;
                            margin-bottom: 6px;
                        ">
                            JUST ARRIVED
                        </p>

                        <h2 style="
                            color: #49382c;
                            font-size: 23px;
                            font-weight: 750;
                        ">
                            Sản phẩm mới nhất
                        </h2>
                    </div>

                    <a href="{{ route('customer.products.index') }}"
                       style="
                            color: #9a6844;
                            text-decoration: none;
                            font-size: 14px;
                            font-weight: 700;
                       ">
                        Xem tất cả →
                    </a>

                </div>


                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
                    gap: 20px;
                ">

                    @forelse ($latestProducts as $product)

                        @php
                            $minPrice = $product->price;
                            $maxPrice = $product->price;

                            $availableQuantity = $product->inventory
                                ? $product->inventory->available_qty
                                : 0;
                        @endphp

                        <div style="
                            background: white;
                            border: 1px solid #eee5db;
                            border-radius: 12px;
                            overflow: hidden;
                        ">

                            <a href="{{ route('customer.products.show', $product->slug) }}"
                               style="display: block; text-decoration: none;">

                                @if ($product->image)

                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         style="
                                            width: 100%;
                                            height: 230px;
                                            object-fit: cover;
                                         ">

                                @else

                                    <div style="
                                        height: 230px;
                                        background: #f0e8de;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: #88796b;
                                        font-size: 13px;
                                    ">
                                        Chưa có hình ảnh
                                    </div>

                                @endif

                            </a>


                            <div style="padding: 17px;">

                                <p style="
                                    color: #a1846b;
                                    font-size: 12px;
                                    margin-bottom: 7px;
                                ">
                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                </p>

                                <h3 style="
                                    color: #49382c;
                                    font-size: 17px;
                                    font-weight: 700;
                                    margin-bottom: 10px;
                                ">
                                    {{ $product->name }}
                                </h3>


                                {{-- Giá sản phẩm --}}
                                <div style="
                                    color: #9a6844;
                                    font-size: 16px;
                                    font-weight: 750;
                                    margin-bottom: 10px;
                                ">

                                    @if ($minPrice !== null)

                                        @if ((float) $minPrice === (float) $maxPrice)

                                            {{ number_format((float) $minPrice, 0, ',', '.') }} đ

                                        @else

                                            {{ number_format((float) $minPrice, 0, ',', '.') }}
                                            -
                                            {{ number_format((float) $maxPrice, 0, ',', '.') }} đ

                                        @endif

                                    @else

                                        Chưa có giá bán

                                    @endif

                                </div>


                                {{-- Tình trạng tồn kho --}}
                                <p style="
                                    font-size: 13px;
                                    margin-bottom: 15px;
                                    color: {{ $availableQuantity > 0 ? '#15803d' : '#b91c1c' }};
                                ">

                                    {{ $availableQuantity > 0 ? 'Còn hàng' : 'Tạm hết hàng' }}

                                </p>


                                <a href="{{ route('customer.products.show', $product->slug) }}"
                                   style="
                                        display: block;
                                        text-align: center;
                                        background: #49382c;
                                        color: white;
                                        padding: 11px;
                                        border-radius: 7px;
                                        text-decoration: none;
                                        font-size: 14px;
                                        font-weight: 600;
                                   ">
                                    Xem chi tiết
                                </a>

                            </div>

                        </div>

                    @empty

                        <div style="
                            grid-column: 1 / -1;
                            background: white;
                            border: 1px solid #eee5db;
                            border-radius: 12px;
                            padding: 35px;
                            text-align: center;
                            color: #88796b;
                        ">
                            Hiện chưa có sản phẩm nào đang kinh doanh.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>
    </div>
</x-app-layout>