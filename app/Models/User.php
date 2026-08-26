<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Market\Address;
use App\Models\Market\CartItem;
use App\Models\Market\Order;
use App\Models\Market\Product;
use App\Models\Ticket\AdminTicket;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use App\Models\User\Permission;
use App\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts()
    {
        return $this->morphedByMany(
            Product::class,
            'likeable',
            'likes',
            'user_id',
            'likeable_id'
        );
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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function hasRole(string $role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermissionTo(string $permissionName): bool
    {
        if ($this->activation == 1) {
            // چک کردن پرمیشن‌ های مستقیم کاربر
            if ($this->permissions->contains(fn($p) => $p->name === $permissionName && $p->status == 1)) {
                return true;
            }

            foreach ($this->roles as $role) {
                if ($role->status == 0) {
                    continue;    //  این نقش رو رد کن بقیه رو چک کن
                }

                // چک کردن پرمیشن‌ هایی که کاربر از طریق نقش‌ هایش دارد
                if ($role->permissions->contains(fn($p) => $p->name === $permissionName && $p->status == 1)) {
                    return true;
                }
            }
        }

        return false;
    }
}
