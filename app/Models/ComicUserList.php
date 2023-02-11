<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name (max length 64)
 * @property string $color (max length 32)
 */
class ComicUserList extends Model
{
    use HasFactory,
        Concerns\BelongsToAUser,
        Concerns\HasSlugColumn;

    protected array $slugSource = ['name'];

    public function comicUserListEntries()
    {
        return $this->hasMany(ComicUserListEntry::class);
    }
}
