<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $full_name (max length 128)
 *
 * @property Comic[]|Collection $comics
 */
class Author extends Model
{
    use HasFactory;

    public function comics()
    {
        return $this->hasMany(Comic::class);
    }
}
