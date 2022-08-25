<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(12)
        ->make()
        ->each(function (Post $post) {
            User::inRandomOrder()->first()->posts()->save($post);
            Comment::factory(random_int(0, 3))->make()->each(function (Comment $comment) use ($post) {
                $comment->commenter()->associate(User::inRandomOrder()->first());
                $comment->post()->associate($post);
                $comment->save();
            });
        });
    }
}
