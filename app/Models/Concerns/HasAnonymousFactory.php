<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

trait HasAnonymousFactory
{
    use HasFactory,
        Helpers\TypehintProxyThis;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return (new class extends Factory {
            protected static $modelNameResolver;

            public function definition()
            {
                return [];
            }
        })->guessModelNamesUsing(fn () => static::class);
    }
}
