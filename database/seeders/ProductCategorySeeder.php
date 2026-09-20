<?php

namespace Database\Seeders;

use App\Models\Market\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            // parent category
            [
                'id'          => 1,
                'name'        => 'Men',
                'description' => 'Explore the latest premium menswear collection.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => null,
            ],
            [
                'id'          => 2,
                'name'        => 'Women',
                'description' => 'Trendy and elegant fashion curated for women.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => null,
            ],
            [
                'id'          => 3,
                'name'        => 'Footwear',
                'description' => 'Performance sneakers, casual shoes, and leather boots.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => null,
            ],
            [
                'id'          => 4,
                'name'        => 'Accessories',
                'description' => 'Bags, wallets, watches, and daily essentials.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => null,
            ],

            // Sub-categories
            // Men Sub-categories
            [
                'id'          => 5,
                'name'        => 'T-Shirts & Hoodies',
                'description' => 'Graphic tees, oversized fits, and cozy streetwear hoodies.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 1,
            ],
            [
                'id'          => 6,
                'name'        => 'Jackets & Coats',
                'description' => 'Windbreakers, denim jackets, and winter parkas.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 1,
            ],

            // Women Sub-categories
            [
                'id'          => 7,
                'name'        => 'Dresses & Tops',
                'description' => 'Casual daywear to high-end evening outfits.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 2,
            ],
            [
                'id'          => 8,
                'name'        => 'Pants & Denim',
                'description' => 'Wide-leg trousers, classic denim, and relaxed fits.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 2,
            ],

            // Accessories Sub-categories

            [
                'id'          => 9,
                'name'        => 'Watches',
                'description' => 'High-cushion runners and everyday street kicks.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 4,
            ],
            [
                'id'          => 10,
                'name'        => 'Bags',
                'description' => 'Crossbody pouches, leather backpacks, and totes.',
                'status'      => 1,
                'image'       => null,
                'parent_id'   => 4,
            ],
        ];

        foreach ($productCategories as $category) {
            ProductCategory::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}
