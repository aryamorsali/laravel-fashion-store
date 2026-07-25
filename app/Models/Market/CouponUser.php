<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'coupon_id', 'order_id', 'used_at'];


    protected $table = "coupon_user";
}
