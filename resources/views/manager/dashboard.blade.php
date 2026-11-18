
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest"
                   style="color: #a47c52;">
                    CANDLE SHOP / ADMINISTRATION
                </p>
                <h2 class="text-xl font-bold mt-1" style="color: #38291f;">
                    Tổng quan quản trị
                </h2>
            </div>

            <div class="hidden sm:flex items-center gap-2 text-sm"
                 style="color: #8b7868;">
                <span>◷</span>
                {{ now()->format('H:i') }} · {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </x-slot>

    @php

        // Nhóm thống kê tổng quan kinh doanh
        $overviewGroups = [
            [
                'title' => 'Quản lý sản phẩm & kho hàng',
                'description' => 'Theo dõi tình trạng kinh doanh và số lượng tồn kho.',
                'symbol' => '01',
                'color' => '#9b7957',
                'background' => '#f4ede4',
                'stats' => [
                    [
                        'label' => 'Tổng sản phẩm',
                        'value' => number_format($totalProducts),
                        'note' => 'Tất cả sản phẩm trong hệ thống',
                        'color' => '#9b7957',
                    ],
                    [
                        'label' => 'Đang kinh doanh',
                        'value' => number_format($activeProducts),
                        'note' => 'Sản phẩm đang được bán',
                        'color' => '#62836d',
                    ],
                    [
                        'label' => 'Ngừng kinh doanh',
                        'value' => number_format($inactiveProducts),
                        'note' => 'Sản phẩm không còn hoạt động',
                        'color' => '#9b7957',
                    ],
                    [
                        'label' => 'Tồn kho thấp',
                        'value' => number_format($lowStockCount),
                        'note' => 'Sản phẩm cần nhập thêm hàng',
                        'color' => '#bd6258',
                    ],
                    [
                        'label' => 'Hết hàng',
                        'value' => number_format($outOfStockCount),
                        'note' => 'Không còn tồn khả dụng',
                        'color' => '#bd6258',
                    ],
                ],
            ],
            [
                'title' => 'Quản lý đơn hàng',
                'description' => 'Theo dõi tình hình xử lý và hoàn thành đơn hàng.',
                'symbol' => '02',
                'color' => '#c18a43',
                'background' => '#fbf2e4',
                'stats' => [
                    [
                        'label' => 'Đơn chờ xác nhận',
                        'value' => number_format($pendingOrders),
                        'note' => 'Đang chờ xử lý',
                        'color' => '#c18a43',
                    ],
                    [
                        'label' => 'Đơn hoàn thành',
                        'value' => number_format($completedOrders),
                        'note' => 'Hoàn thành trong tháng',
                        'color' => '#62836d',
                    ],
                ],
            ],
            [
                'title' => 'Chi tiêu & lợi nhuận',
                'description' => 'Theo dõi doanh thu, giá vốn, chi phí và kết quả kinh doanh.',
                'symbol' => '03',
                'color' => '#718096',
                'background' => '#edf1f5',
                'stats' => [
                    [
                        'label' => 'Doanh thu tháng này',
                        'value' => number_format($monthlyRevenue, 0, ',', '.') . ' đ',
                        'note' => 'Tiền hàng từ đơn hoàn thành',
                        'color' => '#9b7957',
                    ],
                    [
                        'label' => 'Giá vốn hàng bán',
                        'value' => number_format($monthlyCost, 0, ',', '.') . ' đ',
                        'note' => 'Giá vốn hàng đã bán',
                        'color' => '#718096',
                    ],
                    [
                        'label' => 'Chi phí phát sinh',
                        'value' => number_format($monthlyExpenses, 0, ',', '.') . ' đ',
                        'note' => 'Chi phí trong tháng',
                        'color' => '#b88749',
                    ],
                    [
                        'label' => 'Lợi nhuận tạm tính',
                        'value' => number_format($monthlyProfit, 0, ',', '.') . ' đ',
                        'note' => 'Doanh thu trừ giá vốn và chi phí',
                        'color' => $monthlyProfit >= 0 ? '#62836d' : '#bd6258',
                    ],
                ],
            ],
        ];


        // Các chức năng quản lý
        $actions = [
            [
                'title' => 'Danh mục sản phẩm',
                'description' => 'Tổ chức và quản lý các nhóm sản phẩm của cửa hàng.',
                'symbol' => '01',
                'route' => route('manager.categories.index'),
                'tag' => 'DANH MỤC',
            ],
            [
                'title' => 'Sản phẩm',
                'description' => 'Quản lý thông tin, hình ảnh và giá bán sản phẩm.',
                'symbol' => '02',
                'route' => route('manager.products.index'),
                'tag' => 'SẢN PHẨM',
            ],
            [
                'title' => 'Tồn kho',
                'description' => 'Theo dõi số lượng hàng và cảnh báo tồn kho thấp.',
                'symbol' => '03',
                'route' => route('manager.inventory.index'),
                'tag' => 'KHO HÀNG',
            ],
            [
                'title' => 'Nhập kho',
                'description' => 'Tạo phiếu nhập và cập nhật số lượng hàng hóa.',
                'symbol' => '04',
                'route' => route('manager.stock-receipts.index'),
                'tag' => 'NHẬP HÀNG',
            ],
            [
                'title' => 'Lịch sử giao dịch kho',
                'description' => 'Kiểm tra lịch sử nhập, xuất và điều chỉnh kho.',
                'symbol' => '05',
                'route' => route('manager.inventory-transactions.index'),
                'tag' => 'GIAO DỊCH',
            ],
            [
                'title' => 'Chi phí',
                'description' => 'Quản lý và theo dõi các khoản chi phí phát sinh.',
                'symbol' => '06',
                'route' => route('manager.expenses.index'),
                'tag' => 'TÀI CHÍNH',
            ],
            [
                'title' => 'Báo cáo kinh doanh',
                'description' => 'Phân tích doanh thu, giá vốn và lợi nhuận.',
                'symbol' => '07',
                'route' => route('manager.reports.index'),
                'tag' => 'BÁO CÁO',
            ],
        ];
    @endphp

    <style>
        .admin-dashboard {
            --brown: #49372b;
            --brown-light: #80654f;
            --gold: #b18a5d;
            --cream: #f7f4ef;
            --border: #ece5dc;
            background: var(--cream);
            min-height: 100vh;
            color: #45372d;
        }

        .admin-container {
            max-width: 1440px;
            margin: auto;
        }

        .admin-hero {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            border-radius: 22px;
            padding: 34px;
            color: #fffaf3;
            background:
                radial-gradient(circle at 85% 15%, rgba(219,184,133,.25), transparent 28%),
                linear-gradient(115deg, #39291f 0%, #654a37 58%, #806347 100%);
            box-shadow: 0 14px 35px rgba(63,43,29,.13);
        }

        .admin-hero::before {
            content: "";
            position: absolute;
            z-index: -1;
            width: 290px;
            height: 290px;
            right: -75px;
            bottom: -190px;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 50%;
            box-shadow:
                0 0 0 35px rgba(255,255,255,.035),
                0 0 0 70px rgba(255,255,255,.025);
        }

        .admin-hero-label {
            color: #dfc39e;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
        }

        .admin-hero-title {
            font-size: clamp(25px, 3vw, 35px);
            font-weight: 750;
            line-height: 1.3;
            margin-top: 13px;
        }

        .admin-hero-description {
            max-width: 580px;
            color: #e5d8ca;
            font-size: 14px;
            line-height: 1.8;
            margin-top: 12px;
        }

        .admin-hero-date {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-top: 23px;
            padding: 9px 14px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 9px;
            background: rgba(255,255,255,.09);
            color: #fff8ef;
            font-size: 12px;
        }

        .admin-section {
            margin-top: 34px;
        }

        .admin-eyebrow {
            color: #a17c54;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .admin-heading {
            margin-top: 5px;
            color: #45352a;
            font-size: 21px;
            font-weight: 750;
        }

        .admin-subheading {
            margin-top: 5px;
            color: #96887b;
            font-size: 13px;
        }

        .admin-stat {
            background: #ffffff;
            border: 1px solid #ece5dc;
            border-radius: 14px;
            padding: 23px;
            min-width: 0;
            transition: all .2s ease;
        }

        .admin-stat:hover {
            transform: translateY(-3px);
            border-color: #cbb69c;
            box-shadow: 0 10px 25px rgba(71,52,37,.06);
        }

        .admin-stat-label {
            color: #88796b;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1px;
        }

        .admin-stat-value {
            color: #49382c;
            font-size: clamp(23px, 2vw, 29px);
            font-weight: 750;
            letter-spacing: -.7px;
            margin-top: 20px;
            overflow-wrap: anywhere;
        }

        .admin-stat-note {
            color: #a79b90;
            font-size: 12px;
            margin-top: 8px;
            min-height: 18px;
        }

        .admin-action {
            position: relative;
            display: block;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 21px;
            text-decoration: none;
            transition: all .22s ease;
        }

        .admin-action:hover {
            transform: translateY(-4px);
            border-color: #c9aa85;
            box-shadow: 0 13px 28px rgba(71,52,37,.09);
            background: #fffdf9;
        }

        .admin-action-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: #f4eee6;
            color: #97724d;
            font-size: 13px;
            font-weight: 800;
        }

        .admin-action-tag {
            color: #ad957b;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .admin-action-title {
            color: #49372b;
            font-size: 15px;
            font-weight: 750;
            margin-top: 18px;
        }

        .admin-action-description {
            color: #978a7e;
            font-size: 12px;
            line-height: 1.7;
            margin-top: 8px;
            min-height: 40px;
        }

        .admin-action-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 17px;
            padding-top: 13px;
            border-top: 1px solid #f0ebe5;
            color: #9d8060;
            font-size: 11px;
            font-weight: 700;
        }

        .admin-action-arrow {
            font-size: 17px;
            transition: transform .2s ease;
        }

        .admin-action:hover .admin-action-arrow {
            transform: translateX(4px);
        }

        @media (max-width: 640px) {
            .admin-hero {
                padding: 25px 21px;
                border-radius: 16px;
            }

            .admin-stat {
                padding: 17px;
            }

            .admin-action {
                padding: 18px;
            }
        }
    </style>

    <div class="admin-dashboard py-8">
        <div class="admin-container px-4 sm:px-6 lg:px-8">

            {{-- KHU VỰC CHÀO MỪNG --}}
            <section class="admin-hero">
                <div class="relative z-10">
                    <p class="admin-hero-label">
                        THE ART OF SCENTED LIVING
                    </p>

                    <h1 class="admin-hero-title">
                        Xin chào, {{ auth()->user()->name }}!
                    </h1>

                    <p class="admin-hero-description">
                        Chào mừng bạn đến với hệ thống quản trị Candle Shop.
                        Theo dõi hoạt động cửa hàng, quản lý sản phẩm,
                        đơn hàng và hiệu quả kinh doanh tại đây.
                    </p>

                    <div class="admin-hero-date">
                        <span>◷</span>
                        {{ now()->format('l, d/m/Y') }}
                    </div>
                </div>
            </section>

            
            {{-- TỔNG QUAN KINH DOANH --}}
            <section class="admin-section">

                <div class="flex flex-wrap items-end justify-between gap-3 mb-6">
                    <div>
                        <p class="admin-eyebrow">Business overview</p>
                        <h2 class="admin-heading">Tổng quan kinh doanh</h2>
                        <p class="admin-subheading">
                            Theo dõi các hoạt động chính của cửa hàng trong tháng {{ now()->format('m/Y') }}.
                        </p>
                    </div>

                    <span class="text-xs font-semibold px-3 py-2 rounded-lg"
                        style="background:#eee6db;color:#86694c;">
                        THÁNG {{ now()->format('m/Y') }}
                    </span>
                </div>

                <div class="space-y-6">

                    @foreach ($overviewGroups as $group)
                        <div class="bg-white border p-5 sm:p-6"
                            style="
                                border-color: #ece5dc;
                                border-radius: 20px;
                                overflow: hidden;
                                margin-bottom: 4px;
                            ">

                            {{-- Tiêu đề nhóm --}}                            
                            <div class="mb-5 pb-4 border-b"
                                style="
                                    border-color: #eee7de;
                                    padding: 8px 12px 16px 12px;
                                    margin-top: 2px;
                                ">
                                <h3 class="text-base sm:text-lg font-bold"
                                    style="color:#49372b;">
                                    {{ $group['title'] }}
                                </h3>

                                <p class="text-xs sm:text-sm mt-1"
                                style="color:#96887b;">
                                    {{ $group['description'] }}
                                </p>
                            </div>


                            {{-- Các thẻ thống kê bên trong --}}
                            <div style="
                                display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                                gap: 16px;
                                padding: 20px;
                            ">

                                
                                @foreach ($group['stats'] as $stat)
                                    <div style="
                                        background: #FDFCFB;
                                        border: 1px solid #F0EBE5;
                                        border-radius: 12px;
                                        padding: 22px;
                                        min-height: 165px;
                                        box-sizing: border-box;
                                        display: flex;
                                        flex-direction: column;
                                    ">

                                        {{-- Tên chỉ số --}}
                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            gap: 9px;
                                        ">
                                            <span style="
                                                width: 9px;
                                                height: 9px;
                                                min-width: 9px;
                                                border-radius: 50%;
                                                background: {{ $stat['color'] }};
                                            "></span>

                                            <p style="
                                                font-size: 13px;
                                                font-weight: 600;
                                                color: #88796B;
                                                margin: 0;
                                            ">
                                                {{ $stat['label'] }}
                                            </p>
                                        </div>

                                        {{-- Số liệu --}}
                                        <h4 style="
                                            font-size: 25px;
                                            font-weight: 700;
                                            color: #49382C;
                                            margin: 22px 0 8px;
                                            line-height: 1.3;
                                            word-break: break-word;
                                        ">
                                            {{ $stat['value'] }}
                                        </h4>

                                        {{-- Mô tả --}}
                                        <p style="
                                            font-size: 12px;
                                            color: #A79B90;
                                            margin: 0 0 18px;
                                            line-height: 1.5;
                                        ">
                                            {{ $stat['note'] }}
                                        </p>

                                        {{-- Thanh màu phía dưới --}}
                                        <div style="
                                            height: 4px;
                                            width: 100%;
                                            background: #F0EBE5;
                                            border-radius: 10px;
                                            margin-top: auto;
                                            overflow: hidden;
                                        ">
                                            <div style="
                                                height: 100%;
                                                width: 100%;
                                                background: {{ $stat['color'] }};
                                                border-radius: 10px;
                                            "></div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>
            </section>

            {{-- CHỨC NĂNG QUẢN LÝ --}}
            <section class="admin-section">
                <div class="mb-5">
                    <p class="admin-eyebrow">Management workspace</p>
                    <h2 class="admin-heading">Trung tâm quản lý</h2>
                    <p class="admin-subheading">
                        Truy cập nhanh các nghiệp vụ của cửa hàng.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($actions as $action)
                        <a href="{{ $action['route'] }}" class="admin-action">
                            <div class="flex items-center justify-between">
                                <span class="admin-action-number">
                                    {{ $action['symbol'] }}
                                </span>

                                <span class="admin-action-tag">
                                    {{ $action['tag'] }}
                                </span>
                            </div>

                            <h3 class="admin-action-title">
                                {{ $action['title'] }}
                            </h3>

                            <p class="admin-action-description">
                                {{ $action['description'] }}
                            </p>

                            <div class="admin-action-bottom">
                                <span>TRUY CẬP CHỨC NĂNG</span>
                                <span class="admin-action-arrow">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- CHÂN TRANG --}}
            <footer class="text-center py-9">
                <div class="inline-flex items-center gap-3">
                    <span class="h-px w-9" style="background:#d9c9b6;"></span>
                    <span class="text-xs font-bold tracking-widest"
                          style="color:#a18a73;">
                        CANDLE SHOP
                    </span>
                    <span class="h-px w-9" style="background:#d9c9b6;"></span>
                </div>

                <p class="text-xs mt-3" style="color:#b1a396;">
                    Management System · {{ now()->year }}
                </p>
            </footer>

        </div>
    </div>
</x-app-layout>
