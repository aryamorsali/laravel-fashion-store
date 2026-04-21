<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
        use HasFactory, SoftDeletes;


        public function amazingSale()
        {
                return $this->belongsTo(AmazingSale::class);
        }

        public function order()
        {
                return $this->belongsTo(Order::class);
        }


        public function productVariant()
        {
                return $this->belongsTo(ProductVariant::class);
        }
}
