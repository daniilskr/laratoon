<?php

namespace App\Events;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class EpisodeViewedByUser
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Episode $episode,
        public readonly User $user,
    ) {
    }
}
