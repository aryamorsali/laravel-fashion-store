<?php

namespace App\Models\Market;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'address_snapshot' => 'array',
        'delivery_snapshot' => 'array',
        'coupon_snapshot' => 'array',
        'common_discount_snapshot' => 'array',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function commonDiscount()
    {
        return $this->belongsTo(CommonDiscount::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function getOrderStatusValueAttribute()
    {
        return match ($this->order_status) {
            'not_checked' => 'Not Checked',
            'confirmed' => 'Confirmed',
            'awaiting_confirmation' => 'Awaiting Confirmation',
            'not_confirmed' => 'Not Confirmed',
            'canceled' => ' Canceled',
            'returned' => 'Returned',
            default => 'Unknown',
        };
    }
}
