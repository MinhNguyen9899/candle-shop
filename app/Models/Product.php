<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'sku',
        'price',
        'cost_price',
        'low_stock_threshold',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'low_stock_threshold' => 'integer',
        ];
    }

    // Danh mục sản phẩm
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Tồn kho của sản phẩm
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    // Lịch sử giao dịch kho
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Chi tiết các phiếu nhập kho
    public function stockReceiptDetails(): HasMany
    {
        return $this->hasMany(StockReceiptDetail::class);
    }

    // Chi tiết đơn hàng
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Chi tiết giữ hàng
    public function stockReservationDetails(): HasMany
    {
        return $this->hasMany(StockReservationDetail::class);
    }
}
