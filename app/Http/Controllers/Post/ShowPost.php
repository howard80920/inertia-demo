<?php

namespace App\Http\Controllers\Post;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use App\Presenters\PostPresenter;
use App\Presenters\CommentPresenter;

class ShowPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Post $post)
    {
        $this->authorize('view', $post);
        $this->incrementVisit($post);
        $post->load([
            'author' => function ($query) {
                return $query->withCount('publishedPosts', 'likedPosts');
            },
        ]);
        return Inertia::render('Post/Show', [
            'post' => PostPresenter::make($post)->preset('show')->get(),
            'postOnlyLikes' => PostPresenter::make($post)
            ->only('likes')
            ->with(function (Post $post) {
                return [
                    'is_liked' => $post->is_liked,
                ];
            })
            ->get(),
            'comments' => function () use ($post) {
                return CommentPresenter::collection(
                    $post
                    ->comments()
                    ->with('commenter')
                    ->latest()
                    ->get()
                    ->each->setRelation('post', $post)
                )->get();
            },
            // 'test_users' => function () {
            //     return User::all();
            // },
        ]);
    }

    protected function user(): ?User
    {
        return Auth::user();
    }

    protected function incrementVisit(Post $post)
    {
        if (
            !optional($this->user())->can('view', $post) &&
            !session("posts:visits:{$post->id}")
        ) {
            $post->increment('visits');
            session()->put("posts:visits:{$post->id}", true);
        }
    }
}
