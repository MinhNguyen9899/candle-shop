<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'created_by',
        'transaction_type',
        'quantity',
        'reference_type',
        'reference_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    // Giao dịch thuộc một sản phẩm
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Người thực hiện giao dịch
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
