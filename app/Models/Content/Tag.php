<?php

namespace App\Models\Content;

use App\Models\Content\Post;
use App\Models\Market\Product;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
    use Sluggable;

protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function faqs()
    {
        return $this->morphedByMany(FAQ::class, 'taggable');
    }
}
