<?php

namespace Database\Seeders;

use App\Models\Content\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rootComments = Comment::factory()->count(30)->create();
            

        foreach ($rootComments->random(10) as $parentComment) {
            Comment::factory()->create([
                'parent_id'        => $parentComment->id,
                'commentable_type' => $parentComment->commentable_type,
                'commentable_id'   => $parentComment->commentable_id,
                'rating'           => null, 
                'approved'         => 1,
                'seen'             => 1,
            ]);
        }
    }
}
