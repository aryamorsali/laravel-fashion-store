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
                'logo'           => 'images/brands/nike.png',
                'status'         => 1,
            ],
            [
                'id'             => 2,
                'name'           => 'Adidas',
                'logo'           => 'images/brands/adidas.png',
                'status'         => 1,
            ],
            [
                'id'             => 3,
                'name'           => 'Puma',
                'logo'           => 'images/brands/puma.png',
                'status'         => 1,
            ],
            [
                'id'             => 4,
                'name'           => 'Zara',
                'logo'           => 'images/brands/zara.png',
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
