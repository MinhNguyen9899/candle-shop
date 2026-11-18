
<x-app-layout>
    <div style="background: #F7F2EA; min-height: 100vh; padding: 38px 22px 65px;">
        <div style="max-width: 1200px; margin: auto;">

            {{-- Tiêu đề --}}
            <div style="display: flex; justify-content: space-between;
                        align-items: center; flex-wrap: wrap;
                        gap: 15px; margin-bottom: 28px;">

                <div>
                    <p style="font-size: 12px; letter-spacing: 2px;
                              color: #A38B70; font-weight: bold; margin-bottom: 7px;">
                        EMPLOYEE / INVENTORY
                    </p>

                    <h2 style="font-size: 29px; font-weight: bold; color: #30241E;">
                        Chi tiết giao dịch kho
                    </h2>

                    <p style="color: #887B70; margin-top: 6px;">
                        Thông tin chi tiết về biến động hàng hóa trong kho.
                    </p>
                </div>

                <a href="{{ route('employee.inventory-transactions.index') }}"
                   style="background: #70452F; color: white; padding: 11px 18px;
                          border-radius: 8px; text-decoration: none;
                          font-weight: 600;">
                    ← Quay lại
                </a>
            </div>

            {{-- Thông tin giao dịch --}}
            <div style="background: white; border: 1px solid #E8DED2;
                        border-radius: 15px; padding: 25px;
                        margin-bottom: 22px;
                        box-shadow: 0 4px 14px rgba(70, 45, 25, 0.04);">

                <h3 style="font-size: 19px; font-weight: bold;
                           margin-bottom: 22px; color: #5C4030;">
                    Thông tin giao dịch
                </h3>

                <div style="display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                            gap: 22px;">

                    <div>
                        <strong style="color: #806B57;">Mã giao dịch</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            #{{ $transaction->id }}
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Loại giao dịch</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            @if ($transaction->transaction_type === 'receipt')
                                Nhập kho
                            @elseif ($transaction->transaction_type === 'issue')
                                Xuất kho
                            @elseif ($transaction->transaction_type === 'adjustment')
                                Điều chỉnh kho
                            @else
                                {{ $transaction->transaction_type }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Số lượng giao dịch</strong>
                        <p style="font-size: 20px; font-weight: bold;
                                  color: #70452F; margin-top: 6px;">
                            {{ $transaction->quantity }}
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Người thực hiện</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            {{ $transaction->createdBy->name ?? 'Không xác định' }}
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Thời gian thực hiện</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            {{ $transaction->created_at
                                ? $transaction->created_at->format('d/m/Y H:i:s')
                                : 'Không xác định' }}
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Loại chứng từ liên quan</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            {{ $transaction->reference_type ?: 'Không có' }}
                        </p>
                    </div>

                    <div>
                        <strong style="color: #806B57;">Mã chứng từ liên quan</strong>
                        <p style="margin-top: 6px; color: #30241E;">
                            {{ $transaction->reference_id ?: 'Không có' }}
                        </p>
                    </div>

                </div>

                <div style="margin-top: 22px; padding-top: 18px;
                            border-top: 1px solid #F0EAE3;">
                    <strong style="color: #806B57;">Ghi chú</strong>
                    <p style="margin-top: 7px; color: #30241E;">
                        {{ $transaction->note ?: 'Không có ghi chú' }}
                    </p>
                </div>
            </div>

            {{-- Thông tin sản phẩm --}}
            <div style="background: white; border: 1px solid #E8DED2;
                        border-radius: 15px; padding: 25px;
                        margin-bottom: 22px;
                        box-shadow: 0 4px 14px rgba(70, 45, 25, 0.04);">

                <h3 style="font-size: 19px; font-weight: bold;
                           margin-bottom: 22px; color: #5C4030;">
                    Thông tin sản phẩm
                </h3>

                @if ($transaction->product)

                    <div style="display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                                gap: 22px;">

                        <div>
                            <strong style="color: #806B57;">Tên sản phẩm</strong>
                            <p style="margin-top: 6px; color: #30241E;">
                                {{ $transaction->product->name }}
                            </p>
                        </div>

                        <div>
                            <strong style="color: #806B57;">Mã SKU</strong>
                            <p style="margin-top: 6px; color: #30241E;">
                                {{ $transaction->product->sku ?: '-' }}
                            </p>
                        </div>

                    </div>

                @else

                    <p style="color: #887B70;">
                        Không tìm thấy thông tin sản phẩm.
                    </p>

                @endif
            </div>

            {{-- Phiếu nhập kho liên quan --}}
            @if ($relatedReceipt)

                <div style="background: white; border: 1px solid #E8DED2;
                            border-radius: 15px; padding: 25px;
                            box-shadow: 0 4px 14px rgba(70, 45, 25, 0.04);">

                    <h3 style="font-size: 19px; font-weight: bold;
                               margin-bottom: 22px; color: #5C4030;">
                        Phiếu nhập kho liên quan
                    </h3>

                    <div style="display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                                gap: 22px;">

                        <div>
                            <strong style="color: #806B57;">Mã phiếu nhập</strong>
                            <p style="margin-top: 6px; color: #30241E;">
                                {{ $relatedReceipt->receipt_code }}
                            </p>
                        </div>

                        <div>
                            <strong style="color: #806B57;">Nhà cung cấp</strong>
                            <p style="margin-top: 6px; color: #30241E;">
                                {{ $relatedReceipt->supplier_name ?: 'Không có thông tin' }}
                            </p>
                        </div>

                        <div>
                            <strong style="color: #806B57;">Người tạo phiếu</strong>
                            <p style="margin-top: 6px; color: #30241E;">
                                {{ $relatedReceipt->createdBy->name ?? 'Không xác định' }}
                            </p>
                        </div>

                        <div>
                            <strong style="color: #806B57;">Tổng tiền phiếu nhập</strong>
                            <p style="margin-top: 6px; color: #527B55;
                                      font-weight: bold;">
                                {{ number_format((float) $relatedReceipt->total_cost, 0, ',', '.') }} VNĐ
                            </p>
                        </div>

                    </div>

                    <a href="{{ route('employee.stock-receipts.show', $relatedReceipt->id) }}"
                       style="display: inline-block; margin-top: 22px;
                              background: #70452F; color: white;
                              padding: 11px 17px; border-radius: 8px;
                              text-decoration: none; font-weight: 600;">
                        Xem phiếu nhập kho →
                    </a>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>
