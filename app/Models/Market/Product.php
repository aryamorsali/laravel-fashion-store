<?php

namespace App\Models\Market;

use App\Models\Market\Gallery;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    protected $casts = [
        'image' => 'array',
        'published_at' => 'datetime',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(Gallery::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }


    public function amazingSale()
    {
        return $this->hasOne(AmazingSale::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }



    /////////////////////////////////////////////////////

    public function scopeBestSellers($query, $days = 30)
    {
        // سفارش های درست
        $validOrders = function ($q) use ($days) {
            $q->where('payment_status', 'paid')
                ->whereNotIn('order_status', ['canceled', 'returned'])
                ->where('created_at', '>=', now()->subDays($days));
        };

        return $query
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            // فقط محصولاتی که حداقل یک فروش معتبر دارن
            ->whereHas('orderItems.order', $validOrders)
            // محاسبه مجموع تعداد فروش
            ->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                $q->whereHas('order', $validOrders);
            }], 'quantity')
            ->orderByDesc('total_sold');
    }
}
