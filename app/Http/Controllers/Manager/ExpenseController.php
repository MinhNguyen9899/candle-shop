<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Hiển thị danh sách chi phí.
     */
    public function index(Request $request)
    {
        // Kiểm tra dữ liệu lọc.
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ], [
            'date_from.date' => 'Ngày bắt đầu không hợp lệ.',
            'date_to.date' => 'Ngày kết thúc không hợp lệ.',
            'date_to.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
        ]);

        $query = Expense::with('creator');

        // Tìm kiếm theo nội dung hoặc loại chi phí.
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($expenseQuery) use ($keyword) {
                $expenseQuery->where('category', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo ngày bắt đầu.
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        // Lọc theo ngày kết thúc.
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Lọc theo loại chi phí.
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Expense::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('manager.expenses.index', compact(
            'expenses',
            'categories'
        ));
    }

    /**
     * Hiển thị form thêm chi phí.
     */
    public function create()
    {
        return view('manager.expenses.create');
    }

    /**
     * Lưu khoản chi phí mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999999.99',
            'expense_date' => 'required|date',
        ], [
            'category.required' => 'Vui lòng nhập loại chi phí.',
            'category.max' => 'Loại chi phí không được vượt quá 255 ký tự.',
            'description.max' => 'Nội dung không được vượt quá 1000 ký tự.',
            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.numeric' => 'Số tiền phải là số hợp lệ.',
            'amount.min' => 'Số tiền phải lớn hơn 0.',
            'amount.max' => 'Số tiền vượt quá giới hạn cho phép.',
            'expense_date.required' => 'Vui lòng chọn ngày phát sinh.',
            'expense_date.date' => 'Ngày phát sinh không hợp lệ.',
        ]);

        $validated['created_by'] = Auth::id();

        Expense::create($validated);

        return redirect()
            ->route('manager.expenses.index')
            ->with('success', 'Thêm khoản chi phí thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa chi phí.
     */
    public function edit(Expense $expense)
    {
        return view('manager.expenses.edit', compact('expense'));
    }

    /**
     * Cập nhật khoản chi phí.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01|max:9999999999.99',
            'expense_date' => 'required|date',
        ], [
            'category.required' => 'Vui lòng nhập loại chi phí.',
            'category.max' => 'Loại chi phí không được vượt quá 255 ký tự.',
            'description.max' => 'Nội dung không được vượt quá 1000 ký tự.',
            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.numeric' => 'Số tiền phải là số hợp lệ.',
            'amount.min' => 'Số tiền phải lớn hơn 0.',
            'amount.max' => 'Số tiền vượt quá giới hạn cho phép.',
            'expense_date.required' => 'Vui lòng chọn ngày phát sinh.',
            'expense_date.date' => 'Ngày phát sinh không hợp lệ.',
        ]);

        $expense->update($validated);

        return redirect()
            ->route('manager.expenses.index')
            ->with('success', 'Cập nhật khoản chi phí thành công.');
    }

    /**
     * Xóa khoản chi phí.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('manager.expenses.index')
            ->with('success', 'Xóa khoản chi phí thành công.');
    }
}