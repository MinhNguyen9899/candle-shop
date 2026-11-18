
<x-app-layout>
    <div style="background:#faf8f5; min-height:100vh; padding:35px 20px 60px;">
        <div style="max-width:1250px; margin:auto;">

            {{-- Tiêu đề --}}
            <div style="margin-bottom:25px;">
                <h2 style="font-size:27px; font-weight:700; color:#493728;">
                    Tạo phiếu nhập kho
                </h2>
                <p style="color:#8b7968; margin-top:6px;">
                    Có thể nhập sản phẩm cũ và tạo sản phẩm mới trong cùng một phiếu nhập.
                </p>
            </div>

            <form action="{{ route('employee.stock-receipts.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="receiptForm">
                @csrf

                {{-- Thông báo lỗi --}}
                @if ($errors->any())
                    <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:9px; margin-bottom:20px;">
                        <strong>Vui lòng kiểm tra lại thông tin:</strong>
                        <ul style="padding-left:20px; margin-top:8px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Thông tin phiếu nhập --}}
                <div style="background:white; padding:24px; border-radius:13px; margin-bottom:22px; border:1px solid #eee5db;">
                    <h3 style="font-size:19px; font-weight:700; color:#493728; margin-bottom:18px;">
                        Thông tin phiếu nhập
                    </h3>

                    <div style="margin-bottom:17px;">
                        <label style="display:block; font-weight:600; margin-bottom:7px;">
                            Nhà cung cấp
                        </label>
                        <input type="text"
                               name="supplier_name"
                               value="{{ old('supplier_name') }}"
                               placeholder="Nhập tên nhà cung cấp"
                               style="width:100%; padding:11px; border:1px solid #d8cabc; border-radius:7px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">
                            Ghi chú
                        </label>
                        <textarea name="note"
                                  rows="3"
                                  placeholder="Ghi chú thêm cho phiếu nhập..."
                                  style="width:100%; padding:11px; border:1px solid #d8cabc; border-radius:7px;">{{ old('note') }}</textarea>
                    </div>
                </div>

                {{-- Sản phẩm cũ --}}
                <div style="background:white; padding:24px; border-radius:13px; margin-bottom:22px; border:1px solid #eee5db;">

                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:18px;">
                        <div>
                            <h3 style="font-size:19px; font-weight:700; color:#493728;">
                                1. Sản phẩm đã tồn tại
                            </h3>
                            <p style="color:#8b7968; font-size:13px; margin-top:5px;">
                                Chọn sản phẩm trong hệ thống để bổ sung số lượng tồn kho.
                            </p>
                        </div>

                        <button type="button"
                                id="addExistingButton"
                                style="background:#76543b; color:white; padding:10px 16px; border-radius:7px; font-weight:600;">
                            + Thêm sản phẩm cũ
                        </button>
                    </div>

                    <div style="overflow-x:auto;">
                        <table style="width:100%; min-width:850px; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f7f2ec; text-align:left;">
                                    <th style="padding:13px;">Sản phẩm</th>
                                    <th style="padding:13px; width:130px;">Số lượng</th>
                                    <th style="padding:13px; width:175px;">Giá nhập (đ)</th>
                                    <th style="padding:13px; width:150px;">Thành tiền</th>
                                    <th style="padding:13px; width:80px;">Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="existingItems"></tbody>
                        </table>
                    </div>

                    <p id="emptyExisting"
                       style="text-align:center; color:#9a8b7e; padding:20px;">
                        Chưa có sản phẩm cũ. Bấm "Thêm sản phẩm cũ" để chọn.
                    </p>
                </div>

                {{-- Sản phẩm mới --}}
                <div style="background:white; padding:24px; border-radius:13px; margin-bottom:22px; border:1px solid #eee5db;">

                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:18px;">
                        <div>
                            <h3 style="font-size:19px; font-weight:700; color:#493728;">
                                2. Sản phẩm mới
                            </h3>
                            <p style="color:#8b7968; font-size:13px; margin-top:5px;">
                                Khai báo thông tin sản phẩm chưa có trong hệ thống và nhập kho ngay.
                            </p>
                        </div>

                        <button type="button"
                                id="addNewButton"
                                style="background:#52765b; color:white; padding:10px 16px; border-radius:7px; font-weight:600;">
                            + Thêm sản phẩm mới
                        </button>
                    </div>

                    <div id="newProducts"></div>

                    <p id="emptyNew"
                       style="text-align:center; color:#9a8b7e; padding:20px;">
                        Chưa có sản phẩm mới. Bấm "Thêm sản phẩm mới" nếu muốn tạo sản phẩm.
                    </p>
                </div>

                {{-- Tổng tiền --}}
                <div style="background:#f3eee7; border:1px solid #e8ded2; padding:22px 24px; border-radius:12px; margin-bottom:22px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                        <div>
                            <p style="font-size:13px; color:#88796b;">
                                TỔNG GIÁ TRỊ PHIẾU NHẬP
                            </p>
                            <p style="font-size:12px; color:#9a8b7e; margin-top:4px;">
                                Bao gồm toàn bộ sản phẩm cũ và sản phẩm mới.
                            </p>
                        </div>
                        <strong id="totalCost" style="font-size:25px; color:#76543b;">
                            0 đ
                        </strong>
                    </div>
                </div>

                {{-- Nút thao tác --}}
                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('employee.stock-receipts.index') }}"
                       style="background:#8b8178; color:white; padding:12px 21px; border-radius:7px; text-decoration:none;">
                        Quay lại
                    </a>

                    <button type="submit"
                            style="background:#76543b; color:white; padding:12px 24px; border-radius:7px; font-weight:600;">
                        Lưu phiếu nhập kho
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const existingItems = document.getElementById('existingItems');
        const newProducts = document.getElementById('newProducts');

        const emptyExisting = document.getElementById('emptyExisting');
        const emptyNew = document.getElementById('emptyNew');

        const totalCostElement = document.getElementById('totalCost');

        const addExistingButton = document.getElementById('addExistingButton');
        const addNewButton = document.getElementById('addNewButton');

        let existingIndex = 0;
        let newIndex = 0;

        // Danh sách sản phẩm hiện có.
        const productOptions = `
            <option value="">-- Chọn sản phẩm --</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                        data-cost="{{ $product->cost_price ?? 0 }}">
                    {{ $product->name }} - {{ $product->sku }}
                </option>
            @endforeach
        `;

        // Danh sách danh mục.
        const categoryOptions = `
            <option value="">-- Chọn danh mục --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        `;

        function formatMoney(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
        }

        // Tính tổng tiền cả hai nhóm sản phẩm.
        function calculateTotal() {
            let total = 0;

            document.querySelectorAll('.receipt-row, .new-product-card').forEach(function(row) {
                const quantity = Number(row.querySelector('.quantity-input').value) || 0;
                const cost = Number(row.querySelector('.cost-input').value) || 0;
                const subtotal = quantity * cost;

                row.querySelector('.subtotal').textContent = formatMoney(subtotal);
                total += subtotal;
            });

            totalCostElement.textContent = formatMoney(total);
        }

        function updateEmptyMessages() {
            emptyExisting.style.display =
                existingItems.querySelectorAll('.receipt-row').length === 0 ? 'block' : 'none';

            emptyNew.style.display =
                newProducts.querySelectorAll('.new-product-card').length === 0 ? 'block' : 'none';
        }

        // Tạo dòng sản phẩm cũ.
        function createExistingRow(index) {
            const row = document.createElement('tr');

            row.className = 'receipt-row';
            row.style.borderBottom = '1px solid #eee5db';

            row.innerHTML = `
                <td style="padding:12px;">
                    <select name="items[${index}][product_id]"
                            class="product-select"
                            required
                            style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                        ${productOptions}
                    </select>
                </td>

                <td style="padding:12px;">
                    <input type="number"
                           name="items[${index}][quantity]"
                           class="quantity-input"
                           min="1"
                           value="1"
                           required
                           style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                </td>

                <td style="padding:12px;">
                    <input type="number"
                           name="items[${index}][unit_cost]"
                           class="cost-input"
                           min="0"
                           step="0.01"
                           value="0"
                           required
                           style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                </td>

                <td style="padding:12px;">
                    <strong class="subtotal">0 đ</strong>
                </td>

                <td style="padding:12px;">
                    <button type="button"
                            class="remove-row"
                            style="background:#a94442; color:white; padding:8px 11px; border-radius:6px;">
                        Xóa
                    </button>
                </td>
            `;

            return row;
        }

        // Tạo thẻ sản phẩm mới.
        function createNewProductCard(index) {
            const card = document.createElement('div');

            card.className = 'new-product-card';
            card.style.cssText = `
                border:1px solid #e8ded2;
                background:#fdfbf9;
                border-radius:11px;
                padding:20px;
                margin-bottom:16px;
            `;

            card.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                    <strong style="color:#52765b;">Sản phẩm mới #${index + 1}</strong>
                    <button type="button"
                            class="remove-new-product"
                            style="background:#a94442; color:white; padding:8px 12px; border-radius:6px;">
                        Xóa sản phẩm
                    </button>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px;">

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Tên sản phẩm *</label>
                        <input type="text"
                               name="new_products[${index}][name]"
                               required
                               maxlength="255"
                               placeholder="Ví dụ: Nến thơm Lavender"
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">SKU *</label>
                        <input type="text"
                               name="new_products[${index}][sku]"
                               required
                               maxlength="100"
                               placeholder="Ví dụ: YAN-LAV"
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Danh mục *</label>
                        <select name="new_products[${index}][category_id]"
                                required
                                style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                            ${categoryOptions}
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Giá bán (đ) *</label>
                        <input type="number"
                               name="new_products[${index}][price]"
                               min="0"
                               step="0.01"
                               required
                               placeholder="0"
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Giá nhập (đ) *</label>
                        <input type="number"
                               name="new_products[${index}][unit_cost]"
                               class="cost-input"
                               min="0"
                               step="0.01"
                               required
                               value="0"
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Số lượng nhập *</label>
                        <input type="number"
                               name="new_products[${index}][quantity]"
                               class="quantity-input"
                               min="1"
                               value="1"
                               required
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Ngưỡng cảnh báo tồn kho</label>
                        <input type="number"
                               name="new_products[${index}][low_stock_threshold]"
                               min="0"
                               value="5"
                               style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;">
                    </div>

                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:7px;">Ảnh sản phẩm</label>
                        <input type="file"
                               name="new_products[${index}][image]"
                               accept="image/jpeg,image/png,image/webp"
                               style="width:100%; padding:8px; border:1px solid #d8cabc; border-radius:6px;">
                        <p style="font-size:12px; color:#8b7968; margin-top:5px;">
                            JPG, PNG, WEBP. Tối đa 5MB.
                        </p>
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <label style="display:block; font-weight:600; margin-bottom:7px;">Mô tả sản phẩm</label>
                    <textarea name="new_products[${index}][description]"
                              rows="3"
                              placeholder="Nhập mô tả sản phẩm..."
                              style="width:100%; padding:10px; border:1px solid #d8cabc; border-radius:6px;"></textarea>
                </div>

                <div style="text-align:right; margin-top:15px;">
                    <span style="color:#88796b; font-size:13px;">Thành tiền:</span>
                    <strong class="subtotal" style="color:#76543b; margin-left:8px;">0 đ</strong>
                </div>
            `;

            return card;
        }

        // Thêm sản phẩm cũ.
        addExistingButton.addEventListener('click', function() {
            existingItems.appendChild(createExistingRow(existingIndex));
            existingIndex++;
            updateEmptyMessages();
            calculateTotal();
        });

        // Thêm sản phẩm mới.
        addNewButton.addEventListener('click', function() {
            newProducts.appendChild(createNewProductCard(newIndex));
            newIndex++;
            updateEmptyMessages();
            calculateTotal();
        });

        // Tính lại tiền khi thay đổi số lượng hoặc giá nhập.
        document.addEventListener('input', function(event) {
            if (
                event.target.classList.contains('quantity-input') ||
                event.target.classList.contains('cost-input')
            ) {
                calculateTotal();
            }
        });

        // Tự điền giá vốn của sản phẩm cũ.
        existingItems.addEventListener('change', function(event) {
            if (event.target.classList.contains('product-select')) {
                const option = event.target.options[event.target.selectedIndex];
                const row = event.target.closest('.receipt-row');

                row.querySelector('.cost-input').value = option.dataset.cost || 0;
                calculateTotal();
            }
        });

        // Xóa sản phẩm cũ.
        existingItems.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-row')) {
                event.target.closest('.receipt-row').remove();
                updateEmptyMessages();
                calculateTotal();
            }
        });

        // Xóa sản phẩm mới.
        newProducts.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-new-product')) {
                event.target.closest('.new-product-card').remove();
                updateEmptyMessages();
                calculateTotal();
            }
        });

        updateEmptyMessages();
        calculateTotal();
    </script>
</x-app-layout>
