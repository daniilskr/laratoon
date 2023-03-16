<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        Concerns\HasComicUserLists;

    protected $attributes = [
        'views_cached_count' => 0,
        'likes_cached_count' => 0,
        'stars_cached_count' => 0,
        'comments_cached_count' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'issued_for_demo' => 'boolean',
    ];

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function userAvatar(): HasOne
    {
        return $this->hasOne(UserAvatar::class);
    }

    /**
     * User statistics.
     */
    public function countViews(): int
    {
        return View::whereUser($this)->count();
    }

    public function countLikes(): int
    {
        return $this->likes()->count();
    }

    public function countStars(): int
    {
        return Like::whereUserNot($this)
                ->whereCommentOfUser($this)
                ->count();
    }

    public function countComments(): int
    {
        return $this->comments()->count();
    }
}
