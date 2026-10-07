<?php

namespace Database\Seeders;

use App\Models\Market\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'id'             => 1,
                'name'           => 'Nike',
                'logo' => [
                    'indexArray' => [
                        'large' => 'images/brand/seed/nike-logo.png',
                        'main'  => 'images/brand/seed/nike-logo.png',
                        'small' => 'images/brand/seed/nike-logo.png',
                    ],
                    'directory'    => 'images/brand/seed',
                    'currentImage' => 'main',
                ],
                'status'         => 1,
            ],
            [
                'id'             => 2,
                'name'           => 'Adidas',
                'logo' => [
                    'indexArray' => [
                        'large' => 'images/brand/seed/adidas-logo.png',
                        'main'  => 'images/brand/seed/adidas-logo.png',
                        'small' => 'images/brand/seed/adidas-logo.png',
                    ],
                    'directory'    => 'images/brand/seed',
                    'currentImage' => 'main',
                ],
                'status'         => 1,
            ],
            [
                'id'             => 3,
                'name'           => 'Puma',
                'logo' => [
                    'indexArray' => [
                        'large' => 'images/brand/seed/puma-logo.png',
                        'main'  => 'images/brand/seed/puma-logo.png',
                        'small' => 'images/brand/seed/puma-logo.png',
                    ],
                    'directory'    => 'images/brand/seed',
                    'currentImage' => 'main',
                ],
                'status'         => 1,
            ],
            [
                'id'             => 4,
                'name'           => 'Zara',
                'logo' => [
                    'indexArray' => [
                        'large' => 'images/brand/seed/zara-logo.png',
                        'main'  => 'images/brand/seed/zara-logo.png',
                        'small' => 'images/brand/seed/zara-logo.png',
                    ],
                    'directory'    => 'images/brand/seed',
                    'currentImage' => 'main',
                ],
                'status'         => 1,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['id' => $brand['id']],
                $brand
            );
        }
    }
}
