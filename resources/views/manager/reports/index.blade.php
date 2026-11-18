<x-app-layout>
    <div style="max-width: 1350px; margin: 30px auto; padding: 20px;">

        {{-- Tiêu đề --}}
        <div style="margin-bottom: 25px;">
            <h1 style="font-size: 28px; font-weight: bold; color: #1f2937;">
                Báo cáo doanh thu và lợi nhuận
            </h1>

            <p style="color: #6b7280; margin-top: 5px;">
                Theo dõi tình hình kinh doanh của cửa hàng.
            </p>
        </div>

        {{-- Bộ lọc thời gian --}}
        <form method="GET"
              action="{{ route('manager.reports.index') }}"
              style="display: flex; flex-wrap: wrap; align-items: end;
                     gap: 12px; background: white; padding: 18px;
                     border-radius: 8px; margin-bottom: 25px;
                     box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">
                    Từ ngày
                </label>

                <input type="date"
                       name="date_from"
                       value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
                       style="padding: 10px; border: 1px solid #d1d5db;
                              border-radius: 6px;">
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">
                    Đến ngày
                </label>

                <input type="date"
                       name="date_to"
                       value="{{ request('date_to', $dateTo->format('Y-m-d')) }}"
                       style="padding: 10px; border: 1px solid #d1d5db;
                              border-radius: 6px;">
            </div>

            <button type="submit"
                    style="background: #2563eb; color: white; padding: 11px 20px;
                           border: none; border-radius: 6px; cursor: pointer;">
                Lọc báo cáo
            </button>

            <a href="{{ route('manager.reports.index') }}"
               style="background: #6b7280; color: white; padding: 11px 20px;
                      border-radius: 6px; text-decoration: none;">
                Tháng hiện tại
            </a>
        </form>

        {{-- Nút xuất báo cáo --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ route('manager.reports.export', [
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_to' => $dateTo->format('Y-m-d'),
            ]) }}"
            style="display: inline-block;
                    background: #15803d;
                    color: white;
                    padding: 10px 18px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: bold;">

                Xuất báo cáo Excel
            </a>
        </div>

        {{-- Lỗi bộ lọc --}}
        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b;
                        padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Khoảng thời gian báo cáo --}}
        <p style="color: #6b7280; margin-bottom: 18px;">
            Báo cáo từ
            <strong>{{ $dateFrom->format('d/m/Y') }}</strong>
            đến
            <strong>{{ $dateTo->format('d/m/Y') }}</strong>
        </p>

        {{-- Các thẻ tổng quan --}}
        <div style="display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                    gap: 18px; margin-bottom: 25px;">

            {{-- Doanh thu --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        border-left: 5px solid #2563eb;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                <div style="color: #6b7280; margin-bottom: 10px;">
                    Tổng doanh thu
                </div>

                <div style="font-size: 23px; font-weight: bold; color: #2563eb;">
                    {{ number_format($revenue, 0, ',', '.') }} đ
                </div>
            </div>

            {{-- Giá vốn --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        border-left: 5px solid #f59e0b;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                <div style="color: #6b7280; margin-bottom: 10px;">
                    Giá vốn hàng bán
                </div>

                <div style="font-size: 23px; font-weight: bold; color: #d97706;">
                    {{ number_format($costOfGoods, 0, ',', '.') }} đ
                </div>
            </div>

            {{-- Chi phí --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        border-left: 5px solid #dc2626;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                <div style="color: #6b7280; margin-bottom: 10px;">
                    Chi phí vận hành
                </div>

                <div style="font-size: 23px; font-weight: bold; color: #dc2626;">
                    {{ number_format($expenses, 0, ',', '.') }} đ
                </div>
            </div>

            {{-- Lợi nhuận --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        border-left: 5px solid #16a34a;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                <div style="color: #6b7280; margin-bottom: 10px;">
                    Lợi nhuận
                </div>

                <div style="font-size: 23px; font-weight: bold;
                            color: {{ $netProfit >= 0 ? '#16a34a' : '#dc2626' }};">
                    {{ number_format($netProfit, 0, ',', '.') }} đ
                </div>
            </div>
        </div>

        {{-- Khu vực biểu đồ --}}
        <div style="display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                    gap: 20px; margin-bottom: 25px;">

            {{-- Biểu đồ doanh thu theo ngày --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

                <h2 style="font-size: 19px; font-weight: bold; margin-bottom: 20px;">
                    Biểu đồ doanh thu theo ngày
                </h2>

                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Biểu đồ cơ cấu tài chính --}}
            <div style="background: white; padding: 22px; border-radius: 9px;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

                <h2 style="font-size: 19px; font-weight: bold; margin-bottom: 20px;">
                    Cơ cấu doanh thu và chi phí
                </h2>

                <div style="height: 300px;">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

        </div>

        {{-- Biểu đồ sản phẩm bán chạy --}}
        <div style="background: white; padding: 22px; border-radius: 9px;
                    margin-bottom: 25px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <h2 style="font-size: 19px; font-weight: bold; margin-bottom: 20px;">
                Top 5 sản phẩm bán chạy
            </h2>

            <div style="height: 320px;">
                <canvas id="productChart"></canvas>
            </div>

        </div>

        {{-- Thống kê đơn hàng --}}
        <div style="background: white; padding: 22px; border-radius: 9px;
                    margin-bottom: 25px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 18px;">
                Thống kê đơn hàng
            </h2>

            <div style="display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                        gap: 15px;">

                <div style="background: #eff6ff; padding: 18px; border-radius: 7px;">
                    <div style="color: #1d4ed8;">Đơn hoàn thành</div>
                    <div style="font-size: 25px; font-weight: bold; color: #1d4ed8;">
                        {{ $completedOrders }}
                    </div>
                </div>

                <div style="background: #dcfce7; padding: 18px; border-radius: 7px;">
                    <div style="color: #166534;">Đã thanh toán</div>
                    <div style="font-size: 25px; font-weight: bold; color: #166534;">
                        {{ $paidOrders }}
                    </div>
                </div>

                <div style="background: #fef3c7; padding: 18px; border-radius: 7px;">
                    <div style="color: #92400e;">Chưa thanh toán</div>
                    <div style="font-size: 25px; font-weight: bold; color: #92400e;">
                        {{ $unpaidOrders }}
                    </div>
                </div>

            </div>
        </div>

        {{-- Bảng doanh thu theo ngày --}}
        <div style="background: white; padding: 22px; border-radius: 9px;
                    margin-bottom: 25px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 18px;">
                Doanh thu theo ngày
            </h2>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 500px;">

                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="padding: 13px; text-align: left;">Ngày</th>
                            <th style="padding: 13px; text-align: right;">Doanh thu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($dailyRevenue as $item)
                            <tr style="border-top: 1px solid #e5e7eb;">
                                <td style="padding: 13px;">
                                    {{ \Carbon\Carbon::parse($item->report_date)->format('d/m/Y') }}
                                </td>

                                <td style="padding: 13px; text-align: right;
                                           font-weight: bold; color: #2563eb;">
                                    {{ number_format($item->total_revenue, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    style="padding: 20px; text-align: center; color: #6b7280;">
                                    Chưa có doanh thu trong khoảng thời gian này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- Top sản phẩm bán chạy --}}
        <div style="background: white; padding: 22px; border-radius: 9px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 18px;">
                Top 5 sản phẩm bán chạy
            </h2>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 600px;">

                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="padding: 13px; text-align: center;">STT</th>
                            <th style="padding: 13px; text-align: left;">Tên sản phẩm</th>
                            <th style="padding: 13px; text-align: center;">Số lượng bán</th>
                            <th style="padding: 13px; text-align: right;">Doanh thu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($topProducts as $product)
                            <tr style="border-top: 1px solid #e5e7eb;">
                                <td style="padding: 13px; text-align: center;">
                                    {{ $loop->iteration }}
                                </td>

                                <td style="padding: 13px;">
                                    {{ $product->product_name }}
                                </td>

                                <td style="padding: 13px; text-align: center;">
                                    {{ $product->total_quantity }}
                                </td>

                                <td style="padding: 13px; text-align: right;
                                           font-weight: bold;">
                                    {{ number_format($product->total_revenue, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    style="padding: 20px; text-align: center; color: #6b7280;">
                                    Chưa có dữ liệu sản phẩm bán ra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- Thư viện vẽ biểu đồ --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- Khởi tạo các biểu đồ --}}
        <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Dữ liệu doanh thu theo ngày từ Laravel
            const revenueLabels = @json(
                $dailyRevenue->pluck('report_date')->map(function ($date) {
                    return \Carbon\Carbon::parse($date)->format('d/m');
                })
            );

            const revenueData = @json(
                $dailyRevenue->pluck('total_revenue')
            );

            // Biểu đồ doanh thu theo ngày
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',

                data: {
                    labels: revenueLabels,

                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenueData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return new Intl.NumberFormat('vi-VN').format(context.raw) + ' đ';
                                }
                            }
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                callback: function (value) {
                                    return new Intl.NumberFormat('vi-VN').format(value);
                                }
                            }
                        }
                    }
                }
            });


            // Biểu đồ cơ cấu tài chính
            new Chart(document.getElementById('financeChart'), {
                type: 'doughnut',

                data: {
                    labels: [
                        'Giá vốn',
                        'Chi phí vận hành',
                        '{{ $netProfit >= 0 ? "Lợi nhuận" : "Khoản lỗ" }}'
                    ],

                    datasets: [{
                        data: [
                            {{ max(0, (float) $costOfGoods) }},
                            {{ max(0, (float) $expenses) }},
                            {{ max(0, (float) $netProfit) }}
                        ],

                        backgroundColor: [
                            '#f59e0b',
                            '#ef4444',
                            '#16a34a'
                        ],

                        borderWidth: 2
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'bottom'
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' +
                                        new Intl.NumberFormat('vi-VN').format(context.raw) + ' đ';
                                }
                            }
                        }
                    }
                }
            });


            // Dữ liệu sản phẩm bán chạy
            const productLabels = @json(
                $topProducts->pluck('product_name')
            );

            const productData = @json(
                $topProducts->pluck('total_quantity')
            );

            // Biểu đồ Top 5 sản phẩm
            new Chart(document.getElementById('productChart'), {
                type: 'bar',

                data: {
                    labels: productLabels,

                    datasets: [{
                        label: 'Số lượng đã bán',
                        data: productData,
                        backgroundColor: '#2563eb',
                        borderRadius: 6
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

        });
        </script>
    </div>
</x-app-layout>