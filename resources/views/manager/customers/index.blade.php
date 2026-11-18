
<x-app-layout>
    <div style="background:#F8F5F0; min-height:100vh; padding:35px 24px 60px;">
        <div style="max-width:1250px; margin:auto;">

            <div style="margin-bottom:28px;">
                <p style="font-size:12px; letter-spacing:2px; color:#A18B75; font-weight:600;">
                    QUẢN LÝ CỬA HÀNG
                </p>
                <h1 style="font-family:Georgia,serif; font-size:30px; color:#49382D; margin-top:7px;">
                    Quản lý khách hàng
                </h1>
                <p style="color:#8C7D70; font-size:14px; margin-top:7px;">
                    Theo dõi thông tin và lịch sử mua hàng của khách hàng.
                </p>
            </div>

            @if(session('success'))
                <div style="background:#E5F2E8; color:#39734B; padding:13px 16px; border-radius:9px; margin-bottom:20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background:#F5E8E5; color:#A35446; padding:13px 16px; border-radius:9px; margin-bottom:20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Thống kê --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:25px;">
                @foreach([
                    ['Tổng khách hàng', $summary['total']],
                    ['Đang hoạt động', $summary['active']],
                    ['Ngừng hoạt động', $summary['inactive']],
                ] as $item)
                    <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; padding:20px;">
                        <p style="font-size:13px; color:#918174;">{{ $item[0] }}</p>
                        <p style="font-size:27px; font-weight:700; color:#594435; margin-top:8px;">
                            {{ number_format($item[1]) }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Tìm kiếm --}}
            <form method="GET" action="{{ route('manager.customers.index') }}"
                  style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
                <input
                    type="text"
                    name="keyword"
                    value="{{ $keyword }}"
                    placeholder="Tìm theo tên, email hoặc số điện thoại..."
                    style="flex:1; min-width:220px; border:1px solid #DED3C7; border-radius:8px; padding:12px 15px; background:white;"
                >
                <button type="submit"
                        style="background:#76563F; color:white; padding:11px 23px; border-radius:8px; font-weight:600;">
                    Tìm kiếm
                </button>
                <a href="{{ route('manager.customers.index') }}"
                   style="background:#E9E0D6; color:#594435; padding:11px 20px; border-radius:8px; text-decoration:none;">
                    Làm mới
                </a>
            </form>

            {{-- Bảng khách hàng --}}
            <div style="background:white; border:1px solid #E9E0D6; border-radius:12px; overflow:hidden;">
                <div style="padding:18px 20px; border-bottom:1px solid #EEE7DF;">
                    <h3 style="font-weight:700; color:#594435;">Danh sách khách hàng</h3>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; min-width:850px;">
                        <thead style="background:#F7F2EC;">
                            <tr>
                                @foreach(['Khách hàng','Liên hệ','Số đơn hàng','Tổng chi tiêu','Trạng thái',''] as $heading)
                                    <th style="padding:14px 16px; text-align:left; font-size:12px; color:#806D5D; white-space:nowrap;">
                                        {{ $heading }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($customers as $customer)
                                <tr style="border-top:1px solid #F0EAE3;">
                                    <td style="padding:15px 16px;">
                                        <p style="font-weight:600; color:#49382D;">
                                            {{ $customer->name }}
                                        </p>
                                        <p style="font-size:12px; color:#A18F80; margin-top:4px;">
                                            Mã KH: #{{ $customer->id }}
                                        </p>
                                    </td>

                                    <td style="padding:15px 16px; font-size:13px; color:#75675B;">
                                        <p>{{ $customer->email }}</p>
                                        <p style="margin-top:4px;">{{ $customer->phone ?: 'Chưa cập nhật' }}</p>
                                    </td>

                                    <td style="padding:15px 16px; color:#594435; font-weight:600;">
                                        {{ $customer->customer_orders_count }}
                                    </td>

                                    <td style="padding:15px 16px; color:#76563F; font-weight:600; white-space:nowrap;">
                                        {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }} đ
                                    </td>

                                    <td style="padding:15px 16px;">
                                        @if($customer->status === 'active')
                                            <span style="background:#E5F2E8; color:#39734B; padding:6px 10px; border-radius:20px; font-size:12px;">
                                                Hoạt động
                                            </span>
                                        @else
                                            <span style="background:#F5E8E5; color:#A35446; padding:6px 10px; border-radius:20px; font-size:12px;">
                                                Ngừng hoạt động
                                            </span>
                                        @endif
                                    </td>

                                    <td style="padding:15px 16px;">
                                        <div style="display:flex; align-items:center; gap:7px; flex-wrap:wrap;">

                                            <a href="{{ route('manager.customers.show', $customer->id) }}"
                                            style="display:inline-block; background:#76563F; color:white; padding:8px 13px; border-radius:7px; text-decoration:none; font-size:12px; white-space:nowrap;">
                                                Chi tiết
                                            </a>                                          
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding:35px; text-align:center; color:#9A8B7D;">
                                        Không tìm thấy khách hàng phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="padding:18px 20px;">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
