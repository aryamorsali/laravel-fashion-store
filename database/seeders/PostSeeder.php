<?php

namespace Database\Seeders;

use App\Models\Content\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'id'          => 1,
                'title'       => 'Men New-Season',
                'body'    => 'Jackets & Coats',
                'summary' => 'Shop Now',
                'image' => 'Shop Now',
                'status' => 'Shop Now',
                'commentable' => 1,
                'status'      => 1,
                'published_at'       => 'images/banner/2025/12/27/1766784173.jpg',
                'author_id'       => 'images/banner/2025/12/27/1766784173.jpg',
                'category_id'       => 'images/banner/2025/12/27/1766784173.jpg',
            ],

        ];

        foreach ($posts as $banner) {
            Post::updateOrCreate(
                ['id' => $banner['id']],
                $banner
            );
        }


        Post::factory()->count(10)->create();
    }
}
