<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'staff_id',
        'completed_by',
        'paid_by',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'subtotal',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'note',
        'confirmed_at',
        'completed_at',
        'paid_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // Khách hàng đặt đơn
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Nhân viên phụ trách đơn
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // Nhân viên xác nhận giao hàng thành công
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Nhân viên xác nhận cửa hàng đã nhận tiền COD
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // Danh sách sản phẩm trong đơn
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Phiếu giữ hàng của đơn
    public function stockReservation(): HasOne
    {
        return $this->hasOne(StockReservation::class);
    }
}
