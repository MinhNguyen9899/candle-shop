<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Manager\StockReceiptController as ManagerStockReceiptController;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockReceipt;

class StockReceiptController extends ManagerStockReceiptController
{
    // Danh sách phiếu nhập kho dành cho nhân viên
    public function index()
    {
        $receipts = StockReceipt::with('createdBy')
            ->withCount('details')
            ->latest()
            ->paginate(10);

        return view('employee.stock_receipts.index', compact('receipts'));
    }

    // Chi tiết phiếu nhập
    public function show(StockReceipt $stockReceipt)
    {
        $stockReceipt->load([
            'createdBy',
            'details.product',
        ]);

        return view('employee.stock_receipts.show', compact('stockReceipt'));
    }

    // Form tạo phiếu nhập
    public function create()
    {
        $products = Product::with('inventory')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('employee.stock_receipts.create', compact(
            'products',
            'categories'
        ));
    }

    // Hàm store được kế thừa từ ManagerStockReceiptController.
    // Nhân viên sử dụng chung nghiệp vụ tạo phiếu nhập,
    // cập nhật tồn kho và ghi nhận lịch sử giao dịch.
}
