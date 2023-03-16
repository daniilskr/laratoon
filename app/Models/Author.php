<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $full_name (max length 128)
 *
 * @property Comic[]|Collection $comics
 */
class Author extends Model
{
    use HasFactory;

    public function comics(): HasMany
    {
        return $this->hasMany(Comic::class);
    }
}
