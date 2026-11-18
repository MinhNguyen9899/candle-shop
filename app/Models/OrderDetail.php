<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'quantity',
        'unit_price',
        'unit_cost_snapshot',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'unit_cost_snapshot' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Đơn hàng chứa chi tiết này
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Sản phẩm được mua
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
