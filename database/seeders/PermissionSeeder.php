<?php

namespace Database\Seeders;

use App\Models\User\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = collect();


        $resources = [
            'product-category' => ['view', 'create', 'update', 'delete'],
            'product' => ['view', 'create', 'update', 'delete'],
            'product-variant' => ['view', 'create', 'update', 'delete'],
            'brand' => ['view', 'create', 'update', 'delete'],
            'product-attribute' => ['view', 'create', 'update', 'delete'],
            'product-attribute-value' => ['view', 'create', 'update', 'delete'],
            'home-box' => ['view', 'create', 'update', 'delete'],
            'color' => ['view', 'create', 'delete'],
            'size' => ['view', 'create', 'delete'],

            'warehouse' => ['view', 'create', 'update', 'delete'],
            'inventory' => ['view', 'create', 'update'],
            'warehouse-transaction' => ['view'],

            'coupon' => ['view', 'create', 'update', 'delete'],
            'common-discount' => ['view', 'create', 'update', 'delete'],
            'amazing-sale' => ['view', 'create', 'update', 'delete'],

            'delivery' => ['view', 'create', 'update', 'delete'],

            'post' => ['view', 'create', 'update', 'delete'],
            'post-category' => ['view', 'create', 'update', 'delete'],
            'tag' => ['view', 'create', 'update', 'delete'],
            'menu' => ['view', 'create', 'update', 'delete'],
            'faq' => ['view', 'create', 'update', 'delete'],
            'banner' => ['view', 'create', 'update', 'delete'],

            'email-notification' => ['view', 'create', 'update', 'delete', 'send'],
            'sms-notification' => ['view', 'create', 'update', 'delete', 'send'],

        ];

        foreach ($resources as $resource => $actions) {
            foreach ($actions as $action) {
                $permissions->push([
                    'name' => "{$action}-{$resource}",
                    'description' => "{$action} {$resource}",
                    'status' => 1,
                ]);
            }
        }

        $customPermissions = [
            [
                'name' => 'access-admin-panel',
                'description' => 'Access admin panel',
                'status' => 1,
            ],
            [
                'name' => 'manage-product-gallery',
                'description' => 'Manage product gallery',
                'status' => 1,
            ],
            [
                'name' => 'manage-product-comments',
                'description' => 'Manage product reviews',
                'status' => 1,
            ],
            [
                'name' => 'manage-post-comments',
                'description' => 'Manage post reviews',
                'status' => 1,
            ],
            [
                'name' => 'manage-orders',
                'description' => 'Order Management',
                'status' => 1,
            ],
            [
                'name' => 'manage-payments',
                'description' => 'Manage payments and change their status',
                'status' => 1,
            ],
            [
                'name' => 'manage-tickets',
                'description' => 'Ticket Management',
                'status' => 1,
            ],
            [
                'name' => 'manage-email-notification-file',
                'description' => 'Manage email notification file',
                'status' => 1,
            ],
        ];

        $permissions = $permissions
            ->merge($customPermissions)
            ->unique('name')
            ->values();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'description' => $permission['description'],
                    'status' => $permission['status'],
                ]
            );
        }
    }
}
