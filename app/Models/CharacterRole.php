<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CharacterRoleType as RoleType;

/**
 * @property RoleEnum $role_type
 * @property string $description
 */
class CharacterRole extends Model
{
    use HasFactory,
        Concerns\BelongsToAComic;

    protected $attributes = [
        'role_type' => RoleType::Main,
    ];

    protected $casts = [
        'role_type' => RoleType::class,
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
