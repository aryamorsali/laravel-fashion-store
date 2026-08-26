<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailFile extends Model
{
    protected $table =  'notification_email_files';
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function email()
    {
        return $this->belongsTo(Email::class, 'notification_email_id');
    }
}
