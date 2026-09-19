<?php

namespace Database\Seeders;

use App\Models\Market\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productAttributes = [
            [
                'name'        => 'Material',
                'unit'        => null,
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Weight',
                'unit'        => 'kg',
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Color',
                'unit'        => null,
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Size',
                'unit'        => null,
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Length',
                'unit'        => 'cm',
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Width',
                'unit'        => 'cm',
                'category_id' => null,
                'is_global'   => true,
            ],
            [
                'name'        => 'Height',
                'unit'        => 'cm',
                'category_id' => null,
                'is_global'   => true,
            ],
        ];


        foreach ($productAttributes as $attribute) {
            ProductAttribute::updateOrCreate(
                ['name' => $attribute['name']],
                $attribute
            );
        }
    }
}
