<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CachedLatestViewedEpisodeByUser extends Model
{
    use HasFactory,
        Concerns\BelongsToAUser,
        Concerns\BelongsToAComic;

    protected $fillable = [
        'user_id',
        'comic_id',
        'episode_id',
    ];

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public static function updateCache(User|int $user, Comic|int $comic, Episode|int $episode): static
    {
        return self::updateOrCreate([
            'user_id' => modelKey($user),
            'comic_id' => modelKey($comic),
        ], [
            'episode_id' => modelKey($episode),
        ]);
    }
}
