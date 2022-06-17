<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodePoster extends Model
{
    use HasFactory,
        Concerns\HasOneImage;

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
