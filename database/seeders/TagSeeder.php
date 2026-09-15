<?php

namespace Database\Seeders;

use App\Models\Content\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['id' => 1,  'name' => 'Minimalist'],
            ['id' => 2,  'name' => 'Streetwear'],
            ['id' => 3,  'name' => 'Vintage'],
            ['id' => 4,  'name' => 'Classic'],
            ['id' => 5,  'name' => 'Casual'],

            ['id' => 6,  'name' => 'Summer Collection'],
            ['id' => 7,  'name' => 'Winter 2024'],
            ['id' => 8,  'name' => 'Spring Style'],

        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['id' => $tag['id']],
                $tag
            );
        }
    }
}
