<?php

namespace App\Models;

use App\Enums\CharacterRoleType as RoleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
