<?php

namespace App\Models;

use App\Models\Contracts\BelongsToAUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model implements BelongsToAUser
{
    use HasFactory,
        Concerns\BelongsToAUser;

    protected static function booted()
    {
        static::created(function (self $like) {
            $like->likeable()->increment('likes_cached_count');
        });

        static::deleted(function (self $like) {
            $like->likeable()->decrement('likes_cached_count');
        });
    }

    public static function newForUser(User $user): self
    {
        $like = new self();
        $like->user()->associate($user);
        
        return $like;
    }

    public function likeable()
    {
        return $this->belongsTo(Likeable::class);
    }
}
