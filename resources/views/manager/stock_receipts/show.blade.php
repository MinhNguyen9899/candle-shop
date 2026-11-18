<x-app-layout>
    <div style="max-width: 1200px; margin: 30px auto; padding: 20px;">

        {{-- Tiêu đề --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h2 style="font-size: 26px; font-weight: bold; color: #4b3625;">
                    Chi tiết phiếu nhập kho
                </h2>

                <p style="color: #8b735e; margin-top: 5px;">
                    Thông tin đầy đủ về phiếu nhập và các sản phẩm đã nhập.
                </p>
            </div>

            <a href="{{ route('manager.stock-receipts.index') }}"
               style="background: #6b7280; color: white; padding: 10px 16px;
                      border-radius: 6px; text-decoration: none;">
                Quay lại
            </a>
        </div>

        {{-- Thông tin chung --}}
        <div style="background: white; border: 1px solid #e8ddd0;
                    border-radius: 8px; padding: 20px; margin-bottom: 25px;">

            <h3 style="font-size: 19px; font-weight: bold; margin-bottom: 18px; color: #5c4030;">
                Thông tin phiếu nhập
            </h3>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
                        gap: 18px;">

                <div>
                    <strong>Mã phiếu nhập:</strong>
                    <div>{{ $stockReceipt->receipt_code }}</div>
                </div>

                <div>
                    <strong>Nhà cung cấp:</strong>
                    <div>{{ $stockReceipt->supplier_name ?: 'Không có thông tin' }}</div>
                </div>

                <div>
                    <strong>Người tạo:</strong>
                    <div>{{ $stockReceipt->createdBy->name ?? 'Không xác định' }}</div>
                </div>

                <div>
                    <strong>Thời gian nhập:</strong>
                    <div>
                        {{ $stockReceipt->received_at
                            ? $stockReceipt->received_at->format('d/m/Y H:i')
                            : 'Không xác định' }}
                    </div>
                </div>

                <div>
                    <strong>Số dòng sản phẩm:</strong>
                    <div>{{ $stockReceipt->details->count() }}</div>
                </div>

                <div>
                    <strong>Tổng tiền nhập:</strong>
                    <div style="font-size: 20px; font-weight: bold; color: #a65d36;">
                        {{ number_format($stockReceipt->total_cost, 0, ',', '.') }} VNĐ
                    </div>
                </div>

            </div>

            <div style="margin-top: 20px;">
                <strong>Ghi chú:</strong>
                <p style="margin-top: 5px;">
                    {{ $stockReceipt->note ?: 'Không có ghi chú' }}
                </p>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div style="background: white; border: 1px solid #e8ddd0;
                    border-radius: 8px; padding: 20px;">

            <h3 style="font-size: 19px; font-weight: bold; margin-bottom: 18px; color: #5c4030;">
                Danh sách sản phẩm nhập kho
            </h3>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 850px;">

                    <thead>
                        <tr style="background: #f5f0e9;">
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">STT</th>
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">Sản phẩm</th>
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">SKU</th>
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">Số lượng</th>
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">Đơn giá nhập</th>
                            <th style="padding: 12px; border: 1px solid #e8ddd0;">Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($stockReceipt->details as $index => $detail)
                            @php
                                $product = $detail->product;
                            @endphp

                            <tr>
                                <td style="padding: 12px; border: 1px solid #e8ddd0; text-align: center;">
                                    {{ $index + 1 }}
                                </td>

                                <td style="padding: 12px; border: 1px solid #e8ddd0;">
                                    {{ $product->name ?? 'Sản phẩm không tồn tại' }}
                                </td>

                                <td style="padding: 12px; border: 1px solid #e8ddd0;">
                                    {{ $product->sku ?? '-' }}
                                </td>

                                <td style="padding: 12px; border: 1px solid #e8ddd0; text-align: center;">
                                    {{ $detail->quantity }}
                                </td>

                                <td style="padding: 12px; border: 1px solid #e8ddd0; text-align: right;">
                                    {{ number_format($detail->unit_cost, 0, ',', '.') }} VNĐ
                                </td>

                                <td style="padding: 12px; border: 1px solid #e8ddd0;
                                           text-align: right; font-weight: bold;">
                                    {{ number_format($detail->subtotal, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 20px; text-align: center;">
                                    Phiếu nhập chưa có sản phẩm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr style="background: #f5f0e9;">
                            <td colspan="5" style="padding: 15px; text-align: right;
                                                   border: 1px solid #e8ddd0; font-weight: bold;">
                                TỔNG CỘNG
                            </td>

                            <td style="padding: 15px; text-align: right;
                                       border: 1px solid #e8ddd0; font-weight: bold;
                                       color: #a65d36;">
                                {{ number_format($stockReceipt->total_cost, 0, ',', '.') }} VNĐ
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

    </div>
</x-app-layout>
