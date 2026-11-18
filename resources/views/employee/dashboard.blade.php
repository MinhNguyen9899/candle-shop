
<x-app-layout>
    <style>
        .employee-dashboard {
            min-height: 100vh;
            background: #F7F2EA;
            padding: 38px 22px 65px;
            font-family: 'DM Sans', sans-serif;
        }

        .employee-container {
            max-width: 1250px;
            margin: auto;
        }

        .employee-eyebrow {
            color: #A1846B;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            margin-bottom: 9px;
        }

        .employee-heading {
            margin-bottom: 28px;
        }

        .employee-heading h1 {
            color: #30241E;
            font-size: 29px;
            font-weight: 700;
        }

        .employee-heading p {
            color: #887B70;
            font-size: 13px;
            margin-top: 9px;
        }

        .employee-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(115deg, #49382C, #80634B);
            border-radius: 16px;
            padding: 38px 40px;
            margin-bottom: 28px;
            color: white;
        }

        .employee-hero::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
            right: -65px;
            top: -105px;
            box-shadow: 0 0 0 25px rgba(255,255,255,.035),
                        0 0 0 50px rgba(255,255,255,.025);
        }

        .employee-hero-label {
            color: #E2CFB9;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .employee-hero h2 {
            font-size: 27px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .employee-hero p {
            color: #E8DED2;
            font-size: 13px;
            line-height: 1.8;
            max-width: 600px;
        }

        .employee-section-title {
            color: #30241E;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 17px;
        }

        .employee-shortcuts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .employee-shortcut {
            display: flex;
            align-items: center;
            gap: 19px;
            padding: 25px;
            background: white;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            text-decoration: none;
            transition: .2s;
            box-shadow: 0 5px 20px rgba(48,36,30,.025);
        }

        .employee-shortcut:hover {
            border-color: #B79A73;
            transform: translateY(-3px);
            box-shadow: 0 9px 24px rgba(48,36,30,.07);
        }

        .employee-shortcut-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #F7F2EA;
            color: #79583F;
        }

        .employee-shortcut-icon svg {
            width: 25px;
            height: 25px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .employee-shortcut h3 {
            color: #49382C;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .employee-shortcut p {
            color: #887B70;
            font-size: 12px;
            line-height: 1.6;
        }

        .employee-shortcut-arrow {
            margin-left: auto;
            color: #A1846B;
            font-size: 20px;
            flex-shrink: 0;
        }

        .employee-note {
            background: #FFFFFF;
            border: 1px solid #E8DED2;
            border-radius: 13px;
            padding: 25px 28px;
        }

        .employee-note h3 {
            color: #49382C;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 13px;
        }

        .employee-note ul {
            padding-left: 19px;
            color: #887B70;
            font-size: 13px;
            line-height: 2;
        }

        @media (max-width: 700px) {
            .employee-dashboard {
                padding: 28px 14px 45px;
            }

            .employee-heading h1 {
                font-size: 25px;
            }

            .employee-hero {
                padding: 28px 23px;
            }

            .employee-hero h2 {
                font-size: 22px;
            }

            .employee-shortcuts {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .employee-shortcut {
                padding: 20px;
            }

            .employee-note {
                padding: 21px;
            }
        }
    </style>

    <div class="employee-dashboard">
        <div class="employee-container">

            {{-- Tiêu đề --}}
            <div class="employee-heading">
                <p class="employee-eyebrow">EMPLOYEE WORKSPACE</p>
                <h1>Trang làm việc nhân viên</h1>
                <p>Không gian quản lý và xử lý công việc tại Candle Shop.</p>
            </div>

            {{-- Khu vực chào mừng --}}
            <div class="employee-hero">
                <p class="employee-hero-label">WELCOME BACK</p>

                <h2>
                    Xin chào, {{ auth()->user()->name }}!
                </h2>

                <p>
                    Chúc bạn có một ngày làm việc hiệu quả.
                    Hãy kiểm tra đơn hàng và tình trạng tồn kho
                    để đảm bảo hoạt động cửa hàng diễn ra thuận lợi.
                </p>
            </div>

            {{-- Chức năng chính --}}
            <h2 class="employee-section-title">Khu vực làm việc</h2>

            <div class="employee-shortcuts">

                {{-- Quản lý đơn hàng --}}
                <a href="{{ route('employee.orders.index') }}"
                   class="employee-shortcut">

                    <div class="employee-shortcut-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 4H5a2 2 0 0 0-2 2v14h18V6a2 2 0 0 0-2-2h-3"/>
                            <rect x="8" y="2" width="8" height="4" rx="1"/>
                            <path d="M8 11h8M8 15h8"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Quản lý đơn hàng</h3>
                        <p>Tiếp nhận, xác nhận và cập nhật trạng thái đơn hàng.</p>
                    </div>

                    <span class="employee-shortcut-arrow">→</span>
                </a>

                {{-- Quản lý tồn kho --}}
                <a href="{{ route('employee.inventory.index') }}"
                   class="employee-shortcut">

                    <div class="employee-shortcut-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="m3 7 9-4 9 4-9 4-9-4Z"/>
                            <path d="M3 7v10l9 4 9-4V7"/>
                            <path d="M12 11v10"/>
                            <path d="m7.5 5 9 4"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Kiểm tra tồn kho</h3>
                        <p>Theo dõi số lượng thực tế, hàng đang giữ và hàng có thể bán.</p>
                    </div>

                    <span class="employee-shortcut-arrow">→</span>
                </a>

                {{-- Quản lý phiếu nhập kho --}}
                <a href="{{ route('employee.stock-receipts.index') }}"
                   class="employee-shortcut">

                    <div class="employee-shortcut-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 7h18v13H3z"/>
                            <path d="M3 7l2-4h14l2 4"/>
                            <path d="M12 10v7"/>
                            <path d="m9 14 3 3 3-3"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Quản lý phiếu nhập kho</h3>
                        <p>Xem danh sách, thông tin chi tiết và lịch sử các phiếu nhập kho.</p>
                    </div>

                    <span class="employee-shortcut-arrow">→</span>
                </a>

                {{-- Tạo phiếu nhập kho --}}
                <a href="{{ route('employee.stock-receipts.create') }}"
                   class="employee-shortcut">

                    <div class="employee-shortcut-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3v12"/>
                            <path d="m7 10 5 5 5-5"/>
                            <path d="M4 17v4h16v-4"/>
                            <path d="M4 7h4"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Tạo phiếu nhập kho</h3>
                        <p>Nhập thêm biến thể sản phẩm hoặc khai báo sản phẩm mới.</p>
                    </div>

                    <span class="employee-shortcut-arrow">→</span>
                </a>

            </div>

            {{-- Hướng dẫn công việc --}}
            <div class="employee-note">
                <h3>Lưu ý trong quá trình xử lý công việc</h3>

                <ul>
                    <li>Kiểm tra thông tin khách hàng và sản phẩm trước khi xác nhận đơn.</li>
                    <li>Chỉ xác nhận đơn khi số lượng tồn kho đáp ứng yêu cầu.</li>
                    <li>Cập nhật trạng thái đơn hàng theo đúng tiến trình xử lý và giao hàng.</li>
                    <li>Chỉ xác nhận đã thanh toán COD khi cửa hàng thực tế nhận được tiền từ đơn vị vận chuyển.</li>
                    <li>Kiểm tra kỹ số lượng và đơn giá trước khi hoàn tất phiếu nhập kho.</li>
                    <li>Thông tin phiếu nhập kho phải chính xác để đảm bảo số liệu tồn kho.</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
