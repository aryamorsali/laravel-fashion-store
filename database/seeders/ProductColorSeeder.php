<?php

namespace Database\Seeders;

use App\Models\Market\ProductColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'id'       => 1,
                'name'     => 'Midnight Black',
                'hex_code' => '#111111',
            ],
            [
                'id'       => 2,
                'name'     => 'Pure White',
                'hex_code' => '#FFFFFF',
            ],
            [
                'id'       => 3,
                'name'     => 'Charcoal Gray',
                'hex_code' => '#374151',
            ],
            [
                'id'       => 4,
                'name'     => 'Navy Blue',
                'hex_code' => '#1E3A8A',
            ],
            [
                'id'       => 5,
                'name'     => 'Crimson Red',
                'hex_code' => '#DC2626',
            ],
            [
                'id'       => 6,
                'name'     => 'Olive Green',
                'hex_code' => '#4D7C0F',
            ],
            [
                'id'       => 7,
                'name'     => 'Camel Beige',
                'hex_code' => '#D97706',
            ],
            [
                'id'       => 8,
                'name'     => 'Pastel Blue',
                'hex_code' => '#93C5FD',
            ],
        ];

        foreach ($colors as $color) {
            ProductColor::updateOrCreate(
                ['id' => $color['id']],
                $color
            );
        }
    }
}
