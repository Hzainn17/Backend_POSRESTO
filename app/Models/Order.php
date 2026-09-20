<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer_name',
        'table_number',
        'subtotal',
        'tax',
        'discount',
        'service_charge',
        'total_price',
        'total_item',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'double',
        'tax' => 'double',
        'discount' => 'double',
        'service_charge' => 'double',
        'total_price' => 'double',
        'total_item' => 'integer',
        'payment_amount' => 'double',
        'change_amount' => 'double',
    ];

    /**
     * Relationship to the cashier who processed this order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
