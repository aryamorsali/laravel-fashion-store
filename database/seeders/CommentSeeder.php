<?php

namespace Database\Seeders;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Models\User;
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

        $users = User::pluck('id');
        $posts = Post::all();

        foreach ($posts as $post) {
            Comment::factory(rand(2, 5))->create([
                'author_id'        => $users->random(),
                'commentable_type' => Post::class,
                'commentable_id'   => $post->id,
            ]);
        }
    }
}
