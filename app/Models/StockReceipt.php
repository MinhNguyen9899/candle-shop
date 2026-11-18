<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_code',
        'created_by',
        'supplier_name',
        'total_cost',
        'note',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'total_cost' => 'decimal:2',
            'received_at' => 'datetime',
        ];
    }

    // Một phiếu nhập có nhiều dòng chi tiết
    public function details(): HasMany
    {
        return $this->hasMany(StockReceiptDetail::class);
    }

    // Người tạo phiếu nhập
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}