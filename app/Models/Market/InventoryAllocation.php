<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;

class InventoryAllocation extends Model
{
    protected $guarded = ['id'];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function warehouseVariant()
    {
        return $this->belongsTo(WarehouseVariant::class);
    }
}
