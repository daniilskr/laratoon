<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    protected $fillable = [
        'name',
        'color',
    ];

    public function comicUserListEntries()
    {
        return $this->hasMany(ComicUserListEntry::class);
    }

    /**
     * Move comic entry into $this list.
     */
    public function moveEntry(User $user, Comic $comic)
    {
        $comic->comicUserListEntries()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'comic_user_list_id' => $this->id,
        ]);
    }

    /**
     * Remove comic entry from whatever list it is in.
     *
     * @throws ModelNotFoundException
     */
    public static function removeEntry(User $user, Comic $comic)
    {
        $comic->comicUserListEntries()
            ->whereBelongsTo($user)
            ->firstOrFail()
            ->delete();
    }
}
