<?php

namespace App\Models\Content;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = ['body', 'rating', 'parent_id', 'author_id', 'commentable_id', 'status', 'commentable_type', 'approved'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id')->with('children');
    }

    public function answers()
    {
        return $this->hasMany($this, 'parent_id');
    }
}
