<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComicTag extends Model
{
    use HasFactory,
        Concerns\BelongsToManyComics,
        Concerns\HasSlugColumn;

    protected string $slugSource = 'name';
}
