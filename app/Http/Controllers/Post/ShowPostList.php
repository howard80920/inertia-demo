<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Presenters\PostPresenter;

class ShowPostList extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $posts = Post::with('author')
        ->where('published', true)
        ->latest()
        ->paginate();

        return Inertia::render('Home', [
            'posts' => PostPresenter::collection($posts)
                ->preset('list')
                ->get(),
        ]);
    }
}
