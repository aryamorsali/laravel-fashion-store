<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Permission;
use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissions = [
            'user' => [],
            'admin' => ['access-admin-panel'],
            'content-manager' => [
                'view-post',
                'create-post',
                'update-post',
                'delete-post',

                'view-post-category',
                'create-post-category',
                'update-post-category',
                'delete-post-category',

                'view-tag',
                'create-tag',
                'update-tag',
                'delete-tag',

                'view-menu',
                'create-menu',
                'update-menu',
                'delete-menu',

                'view-faq',
                'create-faq',
                'update-faq',
                'delete-faq',

                'view-banner',
                'create-banner',
                'update-banner',
                'delete-banner',
            ],

            'support-agent' => ['manage-tickets'],

            'warehouse-manager' => [
                'view-warehouse',
                'create-warehouse',
                'update-warehouse',
                'delete-warehouse',

                'view-inventory',
                'create-inventory',
                'update-inventory',

                'view-warehouse-transaction',
            ],
        ];

        foreach ($rolePermissions as $roleName  => $permissionNames) {

            $systemRoles = ['user', 'admin'];

            $role = Role::updateOrCreate(
                ['name' => $roleName],
                [
                    'description' => $roleName,
                    'status' => 1,
                    'is_system' => in_array($roleName, $systemRoles, true) ? 1 : 0,
                ]
            );

            $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');

            $role->permissions()->sync($permissionIds);
        }
    }
}
