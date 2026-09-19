<?php

namespace Database\Seeders;

use App\Models\Content\Tag;
use App\Models\Market\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::factory()->count(100)->create()->each(function (Product $product) {
            $tagIds = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->tags()->sync($tagIds);
        });
    }
}
