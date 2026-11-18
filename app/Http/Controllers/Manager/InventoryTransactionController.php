<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\StockReceipt;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Hiển thị danh sách lịch sử giao dịch kho.
     */
    public function index(Request $request)
    {
        // Lấy điều kiện tìm kiếm và lọc.
        $keyword = $request->input('keyword');
        $transactionType = $request->input('transaction_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Khởi tạo truy vấn lịch sử giao dịch.
        $transactions = InventoryTransaction::with([
            'product',
            'createdBy',
        ])

            // Tìm theo tên sản phẩm hoặc mã SKU.
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('product', function ($productQuery) use ($keyword) {
                    $productQuery->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('sku', 'like', '%' . $keyword . '%');
                    });
                });
            })

            // Lọc theo loại giao dịch.
            ->when($transactionType, function ($query) use ($transactionType) {
                $query->where('transaction_type', $transactionType);
            })

            // Lọc từ ngày.
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })

            // Lọc đến ngày.
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })

            // Sắp xếp giao dịch mới nhất lên đầu.
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'manager.inventory_transactions.index',
            compact(
                'transactions',
                'keyword',
                'transactionType',
                'dateFrom',
                'dateTo'
            )
        );
    }

    /**
     * Hiển thị chi tiết một giao dịch kho.
     */
    public function show(InventoryTransaction $transaction)
    {
        // Lấy thông tin sản phẩm và người thực hiện.
        $transaction->load([
            'product',
            'createdBy',
        ]);

        // Khởi tạo thông tin phiếu nhập liên quan.
        $relatedReceipt = null;

        // Nếu giao dịch thuộc một phiếu nhập kho thì lấy phiếu nhập đó.
        if (
            $transaction->reference_type === 'stock_receipt'
            && $transaction->reference_id
        ) {
            $relatedReceipt = StockReceipt::with([
                'createdBy',
                'details.product',
            ])->find($transaction->reference_id);
        }

        return view(
            'manager.inventory_transactions.show',
            compact('transaction', 'relatedReceipt')
        );
    }
}
