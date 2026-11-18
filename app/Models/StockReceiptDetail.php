<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_receipt_id',
        'product_id',
        'quantity',
        'unit_cost',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Chi tiết thuộc một phiếu nhập
    public function stockReceipt(): BelongsTo
    {
        return $this->belongsTo(StockReceipt::class);
    }

    // Chi tiết thuộc một sản phẩm
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
