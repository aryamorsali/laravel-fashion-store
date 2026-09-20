<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ساخت مالک سایت 
        User::firstOrCreate(
            [
                'mobile' => '09120000001',
                'email' => 'owner@gmail.com'
            ],
            [
                'email' => 'owner@gmail.com',
                'mobile' => '09120000001',
                'activation' => '1',
                'is_owner' => 1,
            ]
        );

        // user / admin / warehouse / support role
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        $warehouseRole = Role::where('name', 'warehouse-manager')->first();
        $supportRole = Role::where('name', 'support-agent')->first();

        // ساخت مدیر انبار 
        $adminWarehouse = User::firstOrCreate(
            ['mobile' => '09120000002'],
            [
                'mobile'     => '09120000002',
                'email'      => 'warehouse@gmail.com',
                'activation' => '1',
                'is_owner'   => 0,
            ]
        );

        if ($warehouseRole && $adminRole) {
            $adminWarehouse->roles()->sync([$warehouseRole->id, $adminRole->id]);
        }

        // ساخت پشتیبانی
        $adminSupport = User::firstOrCreate(
            ['mobile' => '09120000003'],
            [
                'mobile'     => '09120000003',
                'email'      => 'support@gmail.com',
                'activation' => '1',
                'is_owner'   => 0,
            ]
        );

        if ($supportRole && $adminRole) {
            $adminSupport->roles()->sync([$supportRole->id, $adminRole->id]);
        }

        // کاربران عادی
        if ($userRole && User::where('is_owner', 0)->whereHas('roles', fn ($q) => $q->where('roles.id', $userRole->id))->count() < 100) {
            User::factory()
                ->count(100)
                ->create()
                ->each(function (User $user) use ($userRole) {
                    $user->roles()->sync([$userRole->id]);
                });
        }
    }
}
