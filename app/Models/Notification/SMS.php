<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    protected $table =  'notification_sms';
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
}
