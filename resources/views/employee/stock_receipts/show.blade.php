
<x-app-layout>
    <div style="background:#F7F2EA; min-height:100vh; padding:38px 22px 65px;">
        <div style="max-width:1450px; margin:0 auto;">

            {{-- Tiêu đề trang --}}
            <div style="display:flex; justify-content:space-between;
                        align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:28px;">

                <div>
                    <p style="font-size:12px; font-weight:700; letter-spacing:2px;
                              color:#A38B70; margin-bottom:7px;">
                        QUẢN LÝ KHO / PHIẾU NHẬP
                    </p>

                    <h1 style="font-size:29px; font-weight:700; color:#30241E;">
                        Chi tiết phiếu nhập kho
                    </h1>

                    <p style="color:#887B70; margin-top:6px;">
                        Thông tin phiếu nhập và danh sách sản phẩm đã nhập kho.
                    </p>
                </div>

                <a href="{{ route('employee.stock-receipts.index') }}"
                   style="display:inline-block; background:#70452F; color:white;
                          padding:11px 20px; border-radius:8px;
                          text-decoration:none; font-weight:600;">
                    ← Quay lại
                </a>
            </div>

            {{-- Thông tin chung --}}
            <div style="background:white; border:1px solid #E8DED2;
                        border-radius:15px; padding:25px; margin-bottom:25px;
                        box-shadow:0 3px 12px rgba(70,45,25,0.04);">

                <h3 style="font-size:19px; font-weight:700;
                           color:#4B3625; margin-bottom:22px;">
                    Thông tin phiếu nhập
                </h3>

                <div style="display:grid;
                            grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
                            gap:22px;">

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Mã phiếu nhập
                        </p>
                        <p style="font-weight:700; color:#30241E;">
                            {{ $stockReceipt->receipt_code }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Nhà cung cấp
                        </p>
                        <p style="font-weight:600; color:#30241E;">
                            {{ $stockReceipt->supplier_name ?: 'Không có thông tin' }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Người tạo
                        </p>
                        <p style="font-weight:600; color:#30241E;">
                            {{ $stockReceipt->createdBy->name ?? 'Không xác định' }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Thời gian nhập
                        </p>
                        <p style="font-weight:600; color:#30241E;">
                            {{ $stockReceipt->received_at
                                ? $stockReceipt->received_at->format('d/m/Y H:i')
                                : 'Không xác định' }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Số dòng sản phẩm
                        </p>
                        <p style="font-weight:600; color:#30241E;">
                            {{ $stockReceipt->details->count() }} sản phẩm
                        </p>
                    </div>

                    <div>
                        <p style="font-size:13px; color:#887B70; margin-bottom:5px;">
                            Tổng tiền nhập
                        </p>
                        <p style="font-size:21px; font-weight:700; color:#A65D36;">
                            {{ number_format($stockReceipt->total_cost, 0, ',', '.') }} VNĐ
                        </p>
                    </div>
                </div>

                <div style="margin-top:22px; padding-top:18px;
                            border-top:1px solid #F0EAE3;">
                    <p style="font-size:13px; color:#887B70; margin-bottom:6px;">
                        Ghi chú
                    </p>
                    <p style="color:#30241E;">
                        {{ $stockReceipt->note ?: 'Không có ghi chú' }}
                    </p>
                </div>
            </div>

            {{-- Danh sách sản phẩm nhập kho --}}
            <div style="background:white; border:1px solid #E8DED2;
                        border-radius:15px; padding:25px;
                        box-shadow:0 3px 12px rgba(70,45,25,0.04);">

                <div style="margin-bottom:20px;">
                    <h3 style="font-size:19px; font-weight:700; color:#4B3625;">
                        Danh sách sản phẩm nhập kho
                    </h3>
                    <p style="font-size:13px; color:#887B70; margin-top:5px;">
                        Chi tiết số lượng và chi phí nhập của từng sản phẩm.
                    </p>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; min-width:850px;">

                        <thead>
                            <tr style="background:#F8F5F0;">
                                <th style="padding:14px 12px; color:#806B57;
                                           border-bottom:1px solid #E8DED2;">STT</th>

                                <th style="padding:14px 12px; color:#806B57;
                                           text-align:left; border-bottom:1px solid #E8DED2;">
                                    Sản phẩm
                                </th>

                                <th style="padding:14px 12px; color:#806B57;
                                           text-align:left; border-bottom:1px solid #E8DED2;">
                                    SKU
                                </th>

                                <th style="padding:14px 12px; color:#806B57;
                                           border-bottom:1px solid #E8DED2;">
                                    Số lượng
                                </th>

                                <th style="padding:14px 12px; color:#806B57;
                                           text-align:right; border-bottom:1px solid #E8DED2;">
                                    Đơn giá nhập
                                </th>

                                <th style="padding:14px 12px; color:#806B57;
                                           text-align:right; border-bottom:1px solid #E8DED2;">
                                    Thành tiền
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($stockReceipt->details as $index => $detail)
                                @php
                                    $product = $detail->product;
                                @endphp

                                <tr>
                                    <td style="padding:14px 12px; text-align:center;
                                               border-bottom:1px solid #F0EAE3;">
                                        {{ $index + 1 }}
                                    </td>

                                    <td style="padding:14px 12px;
                                               border-bottom:1px solid #F0EAE3;
                                               font-weight:600; color:#30241E;">
                                        {{ $product->name ?? 'Sản phẩm không tồn tại' }}
                                    </td>

                                    <td style="padding:14px 12px;
                                               border-bottom:1px solid #F0EAE3;
                                               color:#887B70;">
                                        {{ $product->sku ?? '-' }}
                                    </td>

                                    <td style="padding:14px 12px; text-align:center;
                                               border-bottom:1px solid #F0EAE3;
                                               font-weight:600;">
                                        {{ number_format($detail->quantity) }}
                                    </td>

                                    <td style="padding:14px 12px; text-align:right;
                                               border-bottom:1px solid #F0EAE3;">
                                        {{ number_format($detail->unit_cost, 0, ',', '.') }} VNĐ
                                    </td>

                                    <td style="padding:14px 12px; text-align:right;
                                               border-bottom:1px solid #F0EAE3;
                                               font-weight:700; color:#70452F;">
                                        {{ number_format($detail->subtotal, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6"
                                        style="padding:25px; text-align:center; color:#887B70;">
                                        Phiếu nhập chưa có sản phẩm nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr style="background:#F8F5F0;">
                                <td colspan="5"
                                    style="padding:16px 12px; text-align:right;
                                           border-top:1px solid #E8DED2;
                                           font-weight:700; color:#4B3625;">
                                    TỔNG CỘNG
                                </td>

                                <td style="padding:16px 12px; text-align:right;
                                           border-top:1px solid #E8DED2;
                                           font-weight:700; color:#A65D36;">
                                    {{ number_format($stockReceipt->total_cost, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
