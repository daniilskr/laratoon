<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheLatestViewedEpisodeByUser extends Model
{
    use HasFactory,
        Concerns\BelongsToAUser,
        Concerns\BelongsToAComic;

    protected $fillable = [
        'user_id',
        'comic_id',
        'episode_id',
    ];

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
