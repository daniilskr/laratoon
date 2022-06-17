<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $medium 512 path to a medium sized copy of the image
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'medium',
    ];

    public function imageable()
    {
        return $this->morphTo(__FUNCTION__);
    }
}
