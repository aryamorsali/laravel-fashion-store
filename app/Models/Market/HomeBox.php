<?php

namespace App\Models\Market;

use App\Models\Market\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeBox extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }


    public static $positions = ['top-left','top-right','center','bottom-left','bottom-right'];
}
