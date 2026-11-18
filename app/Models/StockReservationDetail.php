<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReservationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_reservation_id',
        'product_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    // Chi tiết thuộc một phiếu giữ hàng
    public function stockReservation(): BelongsTo
    {
        return $this->belongsTo(StockReservation::class);
    }

    // Sản phẩm được giữ
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
