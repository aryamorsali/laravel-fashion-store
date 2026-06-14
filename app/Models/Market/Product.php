<?php

namespace App\Models\Market;

use App\Models\Content\Comment;
use App\Models\Market\Gallery;
use App\Models\Content\Tag;
use App\Models\Like;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        // order_items → product_variant → product
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
            'product_id',
            'product_variant_id',
            'id',
            'id'
        );
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function activeComments()
    {
        return $this->comments()->where('approved', 1)->whereNull('parent_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
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
            ->where('published_at', '<=', now())
            // فقط محصولاتی که حداقل یک فروش معتبر دارن
            ->whereHas('orderItems.order', $validOrders)
            // محاسبه مجموع تعداد فروش
            ->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                $q->whereHas('order', $validOrders);
            }], 'quantity')
            ->orderByDesc('total_sold');
    }


    //////////////////////////////////////////////////////////////////////////////////////

    public function scopeWithTotalSold($query)
    {
        $validOrders = function ($q) {
            $q->where('payment_status', 'paid')
                ->whereNotIn('order_status', ['canceled', 'returned']);
        };

        return $query->withSum([
            'orderItems as total_sold' => function ($q) use ($validOrders) {
                $q->whereHas('order', $validOrders);
            }
        ], 'quantity');
    }


    //////////////////////////////////////////////////////////////////////////////////////

    protected static function booted()
    {
        static::saving(function ($product) {
            // بررسی اینکه آیا ماهیت سایز یا رنگ دارد
            $hasVariableNature = $product->has_color || $product->has_size;

            // اگر ماهیت متغیر دارد ولی هیچ واریانتی ندارد → draft شود
            if ($hasVariableNature && $product->variants()->doesntExist()) {
                $product->status = 'draft';
            }
        });
    }
}
