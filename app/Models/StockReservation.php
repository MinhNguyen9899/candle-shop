<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'reserved_at',
        'released_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'released_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    // Đơn hàng được giữ hàng
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Các sản phẩm được giữ
    public function details(): HasMany
    {
        return $this->hasMany(StockReservationDetail::class);
    }
}
