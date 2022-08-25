<?php

namespace App\Presenters;

use AdditionApps\FlexiblePresenter\FlexiblePresenter;
use App\Models\Post;
use App\Presenters\UserPresenter;
use App\Acquaintances\Interaction;

class PostPresenter extends FlexiblePresenter
{

    use Concens\HasAuthUser;

    public function values(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'visits' => Interaction::numberToReadable($this->visits),
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'created_ago' => optional($this->created_at)->diffForHumans(),
            'published' => $this->published,
            'likes' => $this->likersCountReadable(),
        ];
    }

    public function presetShow()
    {
        return $this->with(function (Post $post) {
            return [
                'content' => $post->content,
                'author'  => function () use ($post) {
                    return UserPresenter::make($post->author)->preset('withCount')->get();
                },
                'can' => [
                    'update' => $this->userCan('update', $post),
                    'delete' => $this->userCan('delete', $post),
                ],
                'is_liked' => $post->is_liked,
            ];
        });
    }

    public function presetList()
    {
        return $this->with(function (Post $post) {
            return [
                'author' => function () use ($post) {
                    return UserPresenter::make($post->author)
                    ->only('id', 'name', 'avatar')
                    ->get();
                },
            ];
        });
    }
}
