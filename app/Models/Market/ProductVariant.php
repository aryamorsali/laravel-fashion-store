<?php

namespace App\Models\Market;

use App\Models\Market\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class);
    }
    public function size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function amazingSale()
    {
        return $this->hasOne(AmazingSale::class);
    }
    public function activeAmazingSale()
    {
        return $this->hasOne(AmazingSale::class)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }


    public function warehouseVariants()
    {
        return $this->hasMany(WarehouseVariant::class);
    }

    public function availableStock()
    {
        return $this->warehouseVariants
            ->sum(fn($w) => $w->stock - $w->reserved);
    }
}
