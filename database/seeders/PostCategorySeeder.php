<?php

namespace Database\Seeders;

use App\Models\Content\PostCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postCategories = [
            [
                'id'          => 1,
                'name'        => 'Style & Trends',
                'description' => 'Latest fashion trends, style guides, and seasonal inspirations.',
                'status'      => 1,
                'image'       => null,
            ],
            [
                'id'          => 2,
                'name'        => 'Footwear Care',
                'description' => 'Tips and tricks for sneaker and leather shoe maintenance.',
                'status'      => 1,
                'image'       => null,
            ],
            [
                'id'          => 3,
                'name'        => 'Brand Stories',
                'description' => 'Behind the scenes with our premium partners and designer insights.',
                'status'      => 1,
                'image'       => null,
            ],
            [
                'id'          => 4,
                'name'        => 'Size & Fit Guides',
                'description' => 'Comprehensive measurement tips to find your perfect fit.',
                'status'      => 1,
                'image'       => null,
            ],
            [
                'id'          => 5,
                'name'        => 'Sustainable Fashion',
                'description' => 'Eco-friendly apparel, ethical sourcing, and circular fashion.',
                'status'      => 1,
                'image'       => null,
            ],
            [
                'id'          => 6,
                'name'        => 'Streetwear Culture',
                'description' => 'Urban lifestyle, drops, and community highlights.',
                'status'      => 1,
                'image'       => null,
            ],
        ];

        foreach ($postCategories as $category) {
            PostCategory::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}
