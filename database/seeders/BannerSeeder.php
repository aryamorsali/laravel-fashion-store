<?php

namespace Database\Seeders;

use App\Models\Content\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'id'          => 1,
                'title'       => 'Men New-Season',
                'subtitle'    => 'Jackets & Coats',
                'button_text' => 'Shop Now',
                'button_url' => route('customer.market.shop', ['category' => 'men'], false),
                'status'      => 1,
                'image'       => 'images/banner/2025/12/27/1766784173.jpg',
            ],
            [
                'id'          => 2,
                'title'       => 'Women Collection 2018',
                'subtitle'    => 'NEW SEASON',
                'button_text' => 'Shop Now',
                'button_url' => route('customer.market.shop', ['category' => 'women'], false),
                'status'      => 1,
                'image'       => 'images/banner/2026/08/07/1786053684.jpg',
            ],
            [
                'id'          => 3,
                'title'       => 'Men Collection 20181',
                'subtitle'    => 'New arrivals',
                'button_text' => 'Shop Now',
                'button_url' => route('customer.market.shop', ['category' => 'men', 'sort' => 'newness'], false),
                'status'      => 1,
                'image'       => 'images/banner/2025/12/27/1766784207.jpg',
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['id' => $banner['id']],
                $banner
            );
        }
    }
}
