<?php
namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table =  'notification_email';
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
}
