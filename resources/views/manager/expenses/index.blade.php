<x-app-layout>
    <div style="max-width: 1300px; margin: 30px auto; padding: 20px;">

        {{-- Tiêu đề --}}
        <div style="display: flex; justify-content: space-between;
                    align-items: center; margin-bottom: 25px;">

            <div>
                <h1 style="font-size: 28px; font-weight: bold; color: #1f2937;">
                    Quản lý chi phí
                </h1>

                <p style="color: #6b7280; margin-top: 5px;">
                    Theo dõi các khoản chi phí phát sinh của cửa hàng.
                </p>
            </div>

            <a href="{{ route('manager.expenses.create') }}"
               style="background: #2563eb; color: white; padding: 11px 18px;
                      border-radius: 6px; text-decoration: none; font-weight: bold;">
                + Thêm chi phí
            </a>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 12px;
                        border-radius: 6px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 12px;
                        border-radius: 6px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Bộ lọc --}}
        <form method="GET" action="{{ route('manager.expenses.index') }}"
              style="display: flex; flex-wrap: wrap; gap: 10px;
                     margin-bottom: 20px;">

            <input type="text"
                   name="keyword"
                   value="{{ request('keyword') }}"
                   placeholder="Tìm loại chi phí hoặc nội dung..."
                   style="flex: 1; min-width: 220px; padding: 10px;
                          border: 1px solid #d1d5db; border-radius: 6px;">

            <select name="category"
                    style="padding: 10px; border: 1px solid #d1d5db;
                           border-radius: 6px;">
                <option value="">Tất cả loại chi phí</option>

                @foreach ($categories as $category)
                    <option value="{{ $category }}"
                        {{ request('category') === $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <input type="date"
                   name="date_from"
                   value="{{ request('date_from') }}"
                   style="padding: 10px; border: 1px solid #d1d5db;
                          border-radius: 6px;">

            <input type="date"
                   name="date_to"
                   value="{{ request('date_to') }}"
                   style="padding: 10px; border: 1px solid #d1d5db;
                          border-radius: 6px;">

            <button type="submit"
                    style="background: #2563eb; color: white; padding: 10px 18px;
                           border: none; border-radius: 6px; cursor: pointer;">
                Tìm kiếm
            </button>

            <a href="{{ route('manager.expenses.index') }}"
               style="background: #6b7280; color: white; padding: 10px 18px;
                      border-radius: 6px; text-decoration: none;">
                Làm mới
            </a>
        </form>

        {{-- Bảng chi phí --}}
        <div style="overflow-x: auto; background: white; border-radius: 8px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            <table style="width: 100%; min-width: 900px; border-collapse: collapse;">

                <thead style="background: #f3f4f6;">
                    <tr>
                        <th style="padding: 13px; text-align: center;">STT</th>
                        <th style="padding: 13px; text-align: left;">Loại chi phí</th>
                        <th style="padding: 13px; text-align: left;">Nội dung</th>
                        <th style="padding: 13px; text-align: right;">Số tiền</th>
                        <th style="padding: 13px; text-align: center;">Ngày phát sinh</th>
                        <th style="padding: 13px; text-align: left;">Người ghi nhận</th>
                        <th style="padding: 13px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($expenses as $expense)
                        <tr style="border-top: 1px solid #e5e7eb;">

                            <td style="padding: 13px; text-align: center;">
                                {{ $expenses->firstItem() + $loop->index }}
                            </td>

                            <td style="padding: 13px;">
                                {{ $expense->category }}
                            </td>

                            <td style="padding: 13px;">
                                {{ $expense->description ?? '-' }}
                            </td>

                            <td style="padding: 13px; text-align: right;
                                       font-weight: bold; color: #dc2626;">
                                {{ number_format($expense->amount, 0, ',', '.') }} đ
                            </td>

                            <td style="padding: 13px; text-align: center;">
                                {{ $expense->expense_date->format('d/m/Y') }}
                            </td>

                            <td style="padding: 13px;">
                                {{ $expense->creator->name ?? 'Không xác định' }}
                            </td>

                            <td style="padding: 13px; text-align: center; white-space: nowrap;">

                                <a href="{{ route('manager.expenses.edit', $expense->id) }}"
                                style="display: inline-flex;
                                        justify-content: center;
                                        align-items: center;
                                        width: 65px;
                                        height: 32px;
                                        box-sizing: border-box;
                                        background: #f59e0b;
                                        color: white;
                                        border-radius: 5px;
                                        text-decoration: none;
                                        font-size: 14px;">
                                    Sửa
                                </a>

                                <form action="{{ route('manager.expenses.destroy', $expense->id) }}"
                                    method="POST"
                                    style="display: inline-block; margin: 0;"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoản chi phí này không?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="display: inline-flex;
                                                justify-content: center;
                                                align-items: center;
                                                width: 65px;
                                                height: 32px;
                                                box-sizing: border-box;
                                                background: #dc2626;
                                                color: white;
                                                border: none;
                                                border-radius: 5px;
                                                cursor: pointer;
                                                font-size: 14px;">
                                        Xóa
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                style="padding: 25px; text-align: center; color: #6b7280;">
                                Chưa có khoản chi phí nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Phân trang --}}
        <div style="margin-top: 20px;">
            {{ $expenses->links() }}
        </div>

    </div>
</x-app-layout>