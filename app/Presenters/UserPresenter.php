<?php

namespace App\Presenters;

use AdditionApps\FlexiblePresenter\FlexiblePresenter;
use App\Models\User;

class UserPresenter extends FlexiblePresenter
{
    public function values(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'avatar' => $this->avatar,
        ];
    }

    public function presetWithCount()
    {
        return $this->with(function (User $user) {
            return [
                'postsCount' => $user->published_posts_count,
                'likesCount' => $user->liked_posts_count,
            ];
        });
    }
}
