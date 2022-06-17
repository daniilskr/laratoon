<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationStatus extends Model
{
    use HasFactory,
        Concerns\BelongsToManyComics,
        Concerns\HasSlugColumn;

    protected $slugSource = 'name';
}
