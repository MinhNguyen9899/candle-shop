<nav x-data="{
    open: false,
    inventoryOpen: false,
    mobileInventoryOpen: false
}" class="candle-nav">

    <style>
        .candle-nav {
            background: #fff;
            border-bottom: 1px solid #eee6dc;
            position: relative;
            z-index: 50;
        }

        .candle-nav-inner {
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 28px;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .candle-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        .candle-brand img {
            height: 70px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .candle-desktop-menu {
            display: flex;
            align-items: center;
            gap: 5px;
            flex: 1;
            min-width: 0;
            overflow: visible;
        }

        .candle-desktop-menu::-webkit-scrollbar {
            display: none;
        }

        .candle-desktop-menu a {
            white-space: nowrap;
            font-size: 13px !important;
            padding: 10px 12px !important;
            color: #76685e !important;
            border: none !important;
            border-radius: 8px;
            transition: .2s;
        }

        .candle-desktop-menu a:hover {
            background: #f8f3ed;
            color: #70452f !important;
        }

        .candle-desktop-menu a[aria-current="page"] {
            color: #70452f !important;
            background: #f5eee6;
            font-weight: 700;
        }

        .candle-account-button {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 13px;
            border: 1px solid #eee5da;
            border-radius: 10px;
            background: #fff;
            color: #594537;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .candle-account-button:hover {
            background: #faf6f0;
        }

        .candle-account-avatar {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee2d4;
            color: #70452f;
            font-weight: 700;
        }

        .candle-dropdown {
            background: white;
            border: 1px solid #eee5da;
            border-radius: 11px;
            box-shadow: 0 12px 30px rgba(65, 43, 27, .10);
            padding: 6px;
        }

        .candle-dropdown a {
            border-radius: 7px;
            color: #594537 !important;
            font-size: 13px;
        }

        .candle-dropdown a:hover {
            background: #f8f3ed !important;
        }

        /* Menu Quản lý kho trên máy tính */
        .candle-inventory-trigger {
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
            font-size: 13px;
            padding: 10px 12px;
            color: #76685e;
            border: none;
            border-radius: 8px;
            background: transparent;
            cursor: pointer;
            transition: .2s;
        }

        .candle-inventory-trigger:hover {
            background: #f8f3ed;
            color: #70452f;
        }

        .candle-inventory-trigger.active {
            color: #70452f;
            background: #f5eee6;
            font-weight: 700;
        }

        .candle-inventory-dropdown {
            position: relative;
            z-index: 9999;
        }

        .candle-inventory-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 220px;
            padding: 7px;
            margin-top: 5px;
            background: #fff;
            border: 1px solid #eee5da;
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(65, 43, 27, .15);
            z-index: 99999;
        }

        .candle-inventory-menu a {
            display: block;
            padding: 11px 13px !important;
            border-radius: 7px;
            color: #594537 !important;
            font-size: 13px !important;
            text-decoration: none;
        }

        .candle-inventory-menu a:hover {
            background: #f8f3ed;
        }

        .candle-inventory-menu a[aria-current="page"] {
            color: #70452f !important;
            background: #f5eee6;
            font-weight: 700;
        }

        /* Menu Quản lý kho trên điện thoại */
        .candle-mobile-inventory-button {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 11px 12px;
            border-radius: 8px;
            background: transparent;
            color: #655348;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
        }

        .candle-mobile-inventory-button:hover {
            background: #faf5ef;
        }

        .candle-mobile-inventory-submenu {
            margin: 4px 0 8px 12px;
            padding-left: 12px;
            border-left: 2px solid #eadfd2;
        }

        .candle-mobile-inventory-submenu a {
            display: block;
            padding: 10px 12px !important;
            font-size: 13px !important;
        }

        .candle-mobile-toggle {
            display: none;
            border: 1px solid #eee5da;
            border-radius: 9px;
            padding: 8px;
            color: #70452f;
            background: white;
        }

        .candle-mobile-menu {
            border-top: 1px solid #eee6dc;
            background: #fff;
            padding: 12px 20px 18px;
        }

        .candle-mobile-menu a {
            border-radius: 8px;
            font-size: 14px !important;
            color: #655348 !important;
        }

        .candle-mobile-menu a:hover {
            background: #faf5ef;
        }

        .candle-mobile-menu a[aria-current="page"] {
            color: #70452f !important;
            background: #f5eee6;
            font-weight: 700;
        }

        .candle-mobile-user {
            border-top: 1px solid #eee6dc;
            margin-top: 12px;
            padding-top: 16px;
        }

        @media (max-width: 1100px) {
            .candle-nav-inner {
                padding: 0 18px;
                gap: 12px;
            }

            .candle-brand-name {
                font-size: 20px;
            }

            .candle-desktop-menu a {
                padding: 9px 8px !important;
                font-size: 12px !important;
            }
        }

        @media (max-width: 767px) {
            .candle-nav-inner {
                min-height: 68px;
                padding: 0 17px;
            }

            .candle-desktop-menu,
            .candle-desktop-account {
                display: none !important;
            }

            .candle-mobile-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .candle-brand-name {
                font-size: 21px;
            }
        }
    </style>

    {{-- Thanh điều hướng trên máy tính --}}
    <div class="candle-nav-inner">

        {{-- Logo thương hiệu --}}
        <a href="{{ route('home') }}" class="candle-brand">
            <img src="{{ asset('images/logo.png') }}"
                alt="Candle Shop">
        </a>

        {{-- Menu chính --}}
        <div class="candle-desktop-menu hidden sm:flex">

            {{-- Trang tổng quan --}}
            @if (Auth::check() && Auth::user()->role === 'customer')

                <x-nav-link
                    :href="route('customer.dashboard')"
                    :active="request()->routeIs('customer.dashboard')">
                    Trang chủ
                </x-nav-link>

            @else

                <x-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')">
                    Tổng quan
                </x-nav-link>

            @endif

            {{-- Quản lý --}}
            @if (Auth::check() && Auth::user()->role === 'manager')

                <x-nav-link :href="route('manager.categories.index')"
                    :active="request()->routeIs('manager.categories.*')">
                    Danh mục
                </x-nav-link>

                <x-nav-link :href="route('manager.products.index')"
                    :active="request()->routeIs('manager.products.*')">
                    Sản phẩm
                </x-nav-link>

                 {{-- Quản lý đơn hàng --}}
                <x-nav-link
                    :href="route('manager.orders.index')"
                    :active="request()->routeIs('manager.orders.*')">
                    Quản lý đơn hàng
                </x-nav-link>


                {{-- Nhóm quản lý kho --}} 
                <div class="candle-inventory-dropdown"
                    @click.outside="inventoryOpen = false">

                    <button type="button"
                            @click.stop="inventoryOpen = !inventoryOpen"
                            class="candle-inventory-trigger
                                {{ request()->routeIs('manager.inventory.*')
                                    || request()->routeIs('manager.stock-receipts.*')
                                    || request()->routeIs('manager.inventory-transactions.*')
                                    ? 'active' : '' }}">

                        Quản lý kho

                        <svg style="width: 14px; height: 14px;"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="inventoryOpen"
                        x-cloak
                        class="candle-inventory-menu">

                        <a href="{{ route('manager.inventory.index') }}"
                        @if(request()->routeIs('manager.inventory.*'))
                            aria-current="page"
                        @endif>
                            Tồn kho
                        </a>

                        <a href="{{ route('manager.stock-receipts.index') }}"
                        @if(request()->routeIs('manager.stock-receipts.*'))
                            aria-current="page"
                        @endif>
                            Phiếu nhập kho
                        </a>

                        <a href="{{ route('manager.inventory-transactions.index') }}"
                        @if(request()->routeIs('manager.inventory-transactions.*'))
                            aria-current="page"
                        @endif>
                            Giao dịch kho
                        </a>

                    </div>
                </div>

                <x-nav-link :href="route('manager.expenses.index')"
                    :active="request()->routeIs('manager.expenses.*')">
                    Chi phí
                </x-nav-link>

                <x-nav-link :href="route('manager.reports.index')"
                    :active="request()->routeIs('manager.reports.*')">
                    Báo cáo
                </x-nav-link>

                <x-nav-link
                    :href="route('manager.customers.index')"
                    :active="request()->routeIs('manager.customers.*')"
                >
                    Quản lý khách hàng
                </x-nav-link>

            @endif

            

            
            {{-- Nhân viên --}}
            @if (Auth::check() && Auth::user()->role === 'employee')

                <x-nav-link :href="route('employee.products.index')"
                    :active="request()->routeIs('employee.products.*')">
                    Sản phẩm
                </x-nav-link>

                <x-nav-link :href="route('employee.orders.index')"
                    :active="request()->routeIs('employee.orders.*')">
                    Đơn hàng
                </x-nav-link>

                {{-- Nhóm quản lý kho --}}
                <div class="candle-inventory-dropdown"
                    @click.outside="inventoryOpen = false">

                    <button type="button"
                            @click.stop="inventoryOpen = !inventoryOpen"
                            class="candle-inventory-trigger
                                {{ request()->routeIs('employee.inventory.*')
                                    || request()->routeIs('employee.stock-receipts.*')
                                    || request()->routeIs('employee.inventory-transactions.*')
                                    ? 'active' : '' }}">

                        Quản lý kho

                        <svg style="width: 14px; height: 14px;"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="inventoryOpen"
                        x-cloak
                        class="candle-inventory-menu">

                        <a href="{{ route('employee.inventory.index') }}"
                        @if(request()->routeIs('employee.inventory.*'))
                            aria-current="page"
                        @endif>
                            Tồn kho
                        </a>

                        <a href="{{ route('employee.stock-receipts.index') }}"
                        @if(request()->routeIs('employee.stock-receipts.*'))
                            aria-current="page"
                        @endif>
                            Phiếu nhập kho
                        </a>

                        <a href="{{ route('employee.inventory-transactions.index') }}"
                        @if(request()->routeIs('employee.inventory-transactions.*'))
                            aria-current="page"
                        @endif>
                            Giao dịch kho
                        </a>

                    </div>
                </div>

            @endif


            {{-- Khách hàng --}}
            @if (Auth::check() && Auth::user()->role === 'customer')

                <x-nav-link :href="route('customer.products.index')"
                    :active="request()->routeIs('customer.products.*')">
                    Sản phẩm
                </x-nav-link>

                <x-nav-link
                    :href="route('customer.cart.index')"
                    :active="request()->routeIs('customer.cart.*')">
                    Giỏ hàng
                </x-nav-link>

                <x-nav-link :href="route('customer.orders.index')"
                    :active="request()->routeIs('customer.orders.*')">
                    Đơn hàng
                </x-nav-link>
                
            @endif

        </div>

        {{-- Tài khoản trên máy tính --}}
        <div class="candle-desktop-account hidden sm:flex items-center">

            @auth
                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="candle-account-button">

                            <span class="candle-account-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>

                            <span>{{ Auth::user()->name }}</span>

                            <svg class="w-4 h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="m6 9 6 6 6-6"/>
                            </svg>

                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="candle-dropdown">

                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ Auth::user()->name }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                Thông tin cá nhân
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.password')">
                                Đổi mật khẩu
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Đăng xuất
                                </x-dropdown-link>
                            </form>

                        </div>
                    </x-slot>

                </x-dropdown>
            @else
                <a href="{{ route('login') }}"
                    class="candle-account-button"
                    style="text-decoration:none;">
                    Đăng nhập
                </a>
            @endauth

        </div>

        {{-- Nút menu điện thoại --}}
        <button
            @click="open = !open"
            class="candle-mobile-toggle"
            aria-label="Mở menu">

            <svg x-show="!open" class="w-6 h-6"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>

            <svg x-show="open" x-cloak class="w-6 h-6"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>

        </button>

    </div>

    {{-- Menu trên điện thoại --}}
    <div x-show="open" x-cloak class="candle-mobile-menu sm:hidden">

        {{-- Trang tổng quan --}}
        @if (Auth::check() && Auth::user()->role === 'customer')

            <x-responsive-nav-link
                :href="route('customer.dashboard')"
                :active="request()->routeIs('customer.dashboard')">
                Trang chủ
            </x-responsive-nav-link>

        @else

            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">
                Tổng quan
            </x-responsive-nav-link>

        @endif

        {{-- Menu quản lý --}}
        @if (Auth::check() && Auth::user()->role === 'manager')

            <x-responsive-nav-link :href="route('manager.categories.index')"
                :active="request()->routeIs('manager.categories.*')">
                Danh mục
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('manager.products.index')"
                :active="request()->routeIs('manager.products.*')">
                Sản phẩm
            </x-responsive-nav-link>

            {{-- Quản lý đơn hàng --}}
            <x-responsive-nav-link
                :href="route('manager.orders.index')"
                :active="request()->routeIs('manager.orders.*')">
                Quản lý đơn hàng
            </x-responsive-nav-link>

            {{-- Nhóm quản lý kho trên điện thoại --}}
            <div>
                <button type="button"
                        @click="mobileInventoryOpen = !mobileInventoryOpen"
                        class="candle-mobile-inventory-button">

                    <span>Quản lý kho</span>

                    <svg style="width: 16px; height: 16px;"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div x-show="mobileInventoryOpen"
                    x-cloak
                    class="candle-mobile-inventory-submenu">

                    <x-responsive-nav-link
                        :href="route('manager.inventory.index')"
                        :active="request()->routeIs('manager.inventory.*')">
                        Tồn kho
                    </x-responsive-nav-link>

                    <x-responsive-nav-link
                        :href="route('manager.stock-receipts.index')"
                        :active="request()->routeIs('manager.stock-receipts.*')">
                        Phiếu nhập kho
                    </x-responsive-nav-link>

                    <x-responsive-nav-link
                        :href="route('manager.inventory-transactions.index')"
                        :active="request()->routeIs('manager.inventory-transactions.*')">
                        Giao dịch kho
                    </x-responsive-nav-link>

                </div>
            </div>

            <x-responsive-nav-link :href="route('manager.expenses.index')"
                :active="request()->routeIs('manager.expenses.*')">
                Chi phí
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('manager.reports.index')"
                :active="request()->routeIs('manager.reports.*')">
                Báo cáo
            </x-responsive-nav-link>

            
            <x-responsive-nav-link :href="route('manager.customers.index')"
                :active="request()->routeIs('manager.customers.*')">
                Quản lý khách hàng
            </x-responsive-nav-link>

        @endif

        
        {{-- Menu nhân viên --}}
        @if (Auth::check() && Auth::user()->role === 'employee')

            <x-responsive-nav-link :href="route('employee.products.index')"
                :active="request()->routeIs('employee.products.*')">
                Sản phẩm
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('employee.orders.index')"
                :active="request()->routeIs('employee.orders.*')">
                Đơn hàng
            </x-responsive-nav-link>

            {{-- Nhóm quản lý kho trên điện thoại --}}
            <div>
                <button type="button"
                        @click="mobileInventoryOpen = !mobileInventoryOpen"
                        class="candle-mobile-inventory-button">

                    <span>Quản lý kho</span>

                    <svg style="width: 16px; height: 16px;"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div x-show="mobileInventoryOpen"
                    x-cloak
                    class="candle-mobile-inventory-submenu">

                    <x-responsive-nav-link
                        :href="route('employee.inventory.index')"
                        :active="request()->routeIs('employee.inventory.*')">
                        Tồn kho
                    </x-responsive-nav-link>

                    <x-responsive-nav-link
                        :href="route('employee.stock-receipts.index')"
                        :active="request()->routeIs('employee.stock-receipts.*')">
                        Phiếu nhập kho
                    </x-responsive-nav-link>

                    <x-responsive-nav-link
                        :href="route('employee.inventory-transactions.index')"
                        :active="request()->routeIs('employee.inventory-transactions.*')">
                        Giao dịch kho
                    </x-responsive-nav-link>

                </div>
            </div>

        @endif


        {{-- Menu khách hàng --}}
        @if (Auth::check() && Auth::user()->role === 'customer')

            <x-responsive-nav-link :href="route('customer.products.index')"
                :active="request()->routeIs('customer.products.*')">
                Sản phẩm
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('customer.cart.index')"
                :active="request()->routeIs('customer.cart.*')">
                Giỏ hàng
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('customer.orders.index')"
                :active="request()->routeIs('customer.orders.*')">
                Đơn hàng
            </x-responsive-nav-link>

        @endif

       {{-- Thông tin tài khoản --}}
        <div class="candle-mobile-user">

            @auth
                <div class="px-3 mb-3">
                    <p class="font-semibold text-sm text-gray-800">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')">
                    Thông tin cá nhân
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.password')">
                    Đổi mật khẩu
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Đăng xuất
                    </x-responsive-nav-link>
                </form>
            @else
                <x-responsive-nav-link :href="route('login')">
                    Đăng nhập
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('register')">
                    Đăng ký tài khoản
                </x-responsive-nav-link>
            @endauth

        </div>

    </div>  
</nav>
