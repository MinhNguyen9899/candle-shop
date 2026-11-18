<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'on_hand_qty',
        'reserved_qty',
    ];

    protected function casts(): array
    {
        return [
            'on_hand_qty' => 'integer',
            'reserved_qty' => 'integer',
        ];
    }

    // Sản phẩm sở hữu bản ghi tồn kho
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Số lượng có thể bán
    public function getAvailableQtyAttribute(): int
    {
        return max(0, $this->on_hand_qty - $this->reserved_qty);
    }

    // Lịch sử giao dịch kho của sản phẩm 
    public function transactions(): HasMany
    {
        return $this->hasMany(
            InventoryTransaction::class,
            'product_id',
            'product_id'
        );
    }
}
