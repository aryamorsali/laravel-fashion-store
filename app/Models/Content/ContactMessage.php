<?php

namespace App\Models\Content;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
  protected $guarded = ['id'];


  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
