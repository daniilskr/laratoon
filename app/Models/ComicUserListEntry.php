<?php

namespace App\Models;

use App\Models\Contracts\BelongsToAUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComicUserListEntry extends Model implements BelongsToAUser
{
    use HasFactory,
        Concerns\BelongsToAComic,
        Concerns\BelongsToAUser;

    protected $fillable = [
        'comic_user_list_id',
        'user_id',
    ];

    public function comicUserList()
    {
        return $this->belongsTo(ComicUserList::class);
    }
}
