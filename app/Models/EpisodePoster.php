<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpisodePoster extends Model
{
    use HasFactory,
        Concerns\HasOneImage;

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }
}
