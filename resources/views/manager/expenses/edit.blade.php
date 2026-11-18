<x-app-layout>
    <div style="max-width: 800px; margin: 30px auto; padding: 20px;">

        <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 25px;">
            Chỉnh sửa khoản chi phí
        </h1>

        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b;
                        padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('manager.expenses.update', $expense->id) }}"
              method="POST"
              style="background: white; padding: 25px; border-radius: 8px;
                     box-shadow: 0 1px 4px rgba(0,0,0,0.1);">

            @csrf
            @method('PUT')

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 7px;">
                    Loại chi phí *
                </label>

                <input type="text" name="category"
                       value="{{ old('category', $expense->category) }}"
                       required
                       style="width: 100%; padding: 11px;
                              border: 1px solid #d1d5db; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 7px;">
                    Nội dung chi phí
                </label>

                <textarea name="description" rows="4"
                          style="width: 100%; padding: 11px;
                                 border: 1px solid #d1d5db; border-radius: 6px;">{{ old('description', $expense->description) }}</textarea>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 7px;">
                    Số tiền (VNĐ) *
                </label>

                <input type="number" name="amount"
                       value="{{ old('amount', $expense->amount) }}"
                       min="0.01" step="0.01" required
                       style="width: 100%; padding: 11px;
                              border: 1px solid #d1d5db; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 7px;">
                    Ngày phát sinh *
                </label>

                <input type="date" name="expense_date"
                       value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                       required
                       style="width: 100%; padding: 11px;
                              border: 1px solid #d1d5db; border-radius: 6px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit"
                        style="background: #2563eb; color: white; padding: 11px 20px;
                               border: none; border-radius: 6px; cursor: pointer;">
                    Cập nhật
                </button>

                <a href="{{ route('manager.expenses.index') }}"
                   style="background: #6b7280; color: white; padding: 11px 20px;
                          border-radius: 6px; text-decoration: none;">
                    Quay lại
                </a>
            </div>

        </form>
    </div>
</x-app-layout>