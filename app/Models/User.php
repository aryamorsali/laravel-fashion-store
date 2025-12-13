<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Ticket\AdminTicket;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

      protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'national_code',
        'profile_photo_path',
        'activation',
        'registration_date',
        'user_type',
        'mobile_verified_at',
        'email_verified_at',
        'loyalty_level',
    ];
  
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function assignedTicket()
    {
        return $this->hasMany(Ticket::class, 'assigned_admin_id');
    }

    public function ticketAccesses()
    {
        return $this->hasMany(AdminTicket::class, 'admin_id');
    }

    public function accessibleCategories()
    {
        return $this->belongsToMany(
            TicketCategory::class,
            'ticket_admin_access',
            'admin_id',
            'category_id'
        );
    }

    public function updateLoyaltyLevel()
    {
        $total = $this->orders()
            ->where('payment_status', 'paid')
            ->sum('order_final_amount');

        if ($total >= 50000000) {
            $this->loyalty_level = 'platinum';
        } elseif ($total >= 20000000) {
            $this->loyalty_level = 'gold';
        } elseif ($total >= 5000000) {
            $this->loyalty_level = 'silver';
        } else {
            $this->loyalty_level = 'bronze';
        }

        $this->save();
    }
}
