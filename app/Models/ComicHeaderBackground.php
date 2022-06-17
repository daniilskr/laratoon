<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Comic[]|Collection $comic
 * @property Image[]|Collection $image
 */
class ComicHeaderBackground extends Model
{
    use HasFactory,
        Concerns\HasOneImage,
        Concerns\BelongsToAComic;
}
