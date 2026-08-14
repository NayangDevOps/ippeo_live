<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'state', 'pincode', 'payment_method', 'payment_status',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'paid_at',
        'confirmation_emailed', 'status', 'subtotal', 'shipping', 'total', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmation_emailed' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
