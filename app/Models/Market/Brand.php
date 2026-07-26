<?php

namespace App\Models\Market;

use App\Models\Content\Tag;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, Sluggable;

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
    ];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
