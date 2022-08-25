<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Multicaret\Acquaintances\Traits\CanLike;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use CanLike;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'description', 'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarUrl(string $default = 'mp', int $size = 80)
    {
        return sprintf(
            'https://www.gravatar.com/avatar/%s?d=%s&s=%s',
            md5(strtolower(trim($this->email))),
            urlencode($default),
            $size
        );
    }

    protected static function booted()
    {
        static::creating(function (self $user) {
            if (!$user->avatar) {
                $user->avatar = $user->getAvatarUrl();
            }
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function setAvatarAttribute($avatar)
    {
        $this->attributes['avatar'] = $avatar instanceof UploadedFile
            ? Storage::url($avatar->store('avatars'))
            : $avatar;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publishedPosts()
    {
        return $this->posts()->published();
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function likedPosts()
    {
        return $this->likes(Post::class)->published();
    }

}
