<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\StockReceipt;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Hiển thị lịch sử giao dịch kho.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $transactionType = $request->input('transaction_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

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
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('employee.inventory_transactions.index', compact(
            'transactions',
            'keyword',
            'transactionType',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Hiển thị chi tiết giao dịch kho.
     */
    public function show(InventoryTransaction $transaction)
    {
        $transaction->load([
            'product',
            'createdBy',
        ]);

        $relatedReceipt = null;

        if (
            $transaction->reference_type === 'stock_receipt'
            && $transaction->reference_id
        ) {
            $relatedReceipt = StockReceipt::with([
                'createdBy',
                'details.product',
            ])->find($transaction->reference_id);
        }

        return view('employee.inventory_transactions.show', compact(
            'transaction',
            'relatedReceipt'
        ));
    }
}
