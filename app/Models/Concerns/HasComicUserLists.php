<?php

namespace App\Models\Concerns;

use App\Models\ComicUserList;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasComicUserLists
{
    public function comicUserLists(): HasMany
    {
        return $this->hasMany(ComicUserList::class);
    }

    protected function attachDefaultComicUserLists(self $owner)
    {
        $owner->comicUserLists()->saveMany([
            new ComicUserList(['name' => 'reading', 'color' => '#14df71']),
            new ComicUserList(['name' => 'complete', 'color' => '#74b1eb']),
            new ComicUserList(['name' => 'plan to read', 'color' => '#ffc499']),
            new ComicUserList(['name' => 'on hold', 'color' => '#f9e74b']),
            new ComicUserList(['name' => 'dropped', 'color' => '#e93939']),
        ]);
    }

    protected static function bootHasComicUserLists()
    {
        static::created(function (self $owner) {
            $owner->attachDefaultComicUserLists($owner);
        });
    }
}
