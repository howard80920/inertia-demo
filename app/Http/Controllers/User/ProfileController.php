<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Presenters\UserPresenter;
use App\Presenters\PostPresenter;
use Inertia\Inertia;

use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index(User $user)
    {

        $user->loadCount('publishedPosts', 'likedPosts');

        return Inertia::render('User/Profile', [
            'pageTitle' => "$user->name 的文章",
            'type' => 'show',
            'user' => UserPresenter::make($user)->with(function (User $user) {
                return [
                    'posts' => PostPresenter::collection(
                        $user->posts()
                            ->with('author')
                            ->where('published', true)
                            ->latest()
                            ->paginate()
                    )->preset('list'),
                    'postsCount' => $user->published_posts_count,
                    'likesCount' => $user->liked_posts_count,
                ];
            })->get(),
        ]);
    }

    public function likes(User $user)
    {
        $user->loadCount('publishedPosts', 'likedPosts');

        return Inertia::render('User/Profile', [
            'pageTitle' => "$user->name 喜歡的文章",
            'type' => 'likes',
            'user' => UserPresenter::make($user)->with(function (User $user) {
                return [
                    'posts' => PostPresenter::collection(
                        $user->likedPosts()
                        ->with('author')
                        ->latest('pivot_created_at')
                        ->paginate()
                    )->preset('list'),
                    'postsCount' => $user->published_posts_count,
                    'likesCount' => $user->liked_posts_count,
                ];
            })->get(),
        ]);
    }

}
