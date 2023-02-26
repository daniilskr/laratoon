<?php

namespace App\Models;

use App\Models\Contracts\HasCommentable;
use App\Models\Contracts\HasLikeable;
use App\Models\Contracts\HasViewable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Comic $comic
 */
class Episode extends Model implements HasCommentable, HasLikeable, HasViewable
{
    use HasFactory,
        Concerns\BelongsToAComic,
        Concerns\HasCommentable,
        Concerns\HasViewable,
        Concerns\HasLikeable;

    public function episodePoster()
    {
        return $this->hasOne(EpisodePoster::class);
    }

    public function episodePages()
    {
        return $this->hasMany(EpisodePage::class);
    }

    public function markAsLatestViewedEpisodeByUser(User $user): void
    {
        $latestView = $this->viewable->firstOrCreateViewForUser($user);

        if (! $latestView->wasRecentlyCreated) {
            $latestView->touch();
        }
    }
}
