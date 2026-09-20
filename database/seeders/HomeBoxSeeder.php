<?php

namespace Database\Seeders;

use App\Models\Market\HomeBox;
use App\Models\Market\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeBoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::pluck('id', 'slug');

        $boxes = [
            [
                'title'       => 'Women',
                'subtitle'    => 'New Trend',
                'image'       => 'images/home-box/seed/banner-01.jpg',
                'category_id' => $categories->get('women'),
                'position'    => 'top-left',
                'status'      => 1,
            ],
            [
                'title'       => 'Men',
                'subtitle'    => 'New Trend',
                'image'       => 'images/home-box/seed/banner-02.jpg',
                'category_id' => $categories->get('men'),
                'position'    => 'top-right',
                'status'      => 1,
            ],
            [
                'title'       => 'Bags',
                'subtitle'    => 'Exclusive Offers',
                'image'       => 'images/home-box/seed/banner-04.jpg',
                'category_id' => $categories->get('bags'),
                'position'    => 'bottom-left',
                'status'      => 1,
            ],
            [
                'title'       => 'Watches',
                'subtitle'    => 'Exclusive Offers',
                'image'       => 'images/home-box/seed/banner-05.jpg',
                'category_id' => $categories->get('watches'),
                'position'    => 'center',
                'status'      => 1,
            ],
            [
                'title'       => 'Accessories',
                'subtitle'    => 'New Trend',
                'image'       => 'images/home-box/seed/banner-03.jpg',
                'category_id' => $categories->get('accessories'),
                'position'    => 'bottom-right',
                'status'      => 1,
            ],
        ];

        foreach ($boxes as $box) {
            HomeBox::updateOrCreate(
                ['position' => $box['position']],
                $box
            );
        }
    }
}
