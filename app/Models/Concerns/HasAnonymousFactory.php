<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

trait HasAnonymousFactory
{
    use HasFactory,
        Helpers\TypehintProxyThis;

    protected static function newFactory()
    {
        $factory = new class extends Factory {
            protected static $modelNameResolver;

            public function definition()
            {
                return [];
            }
        };
        
        $factory->guessModelNamesUsing(fn () => static::class);

        return $factory;
    }
}
