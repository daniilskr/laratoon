<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    public const GENRES = [
        'fantasy', 'sci-fi', 'sports', 'supernatural',
        'comedy', 'adventure', 'action', 'drama',
        'detective', 'romance', 'horror',
    ];

    public function configure()
    {
        return $this
                ->sequence(
                    ...Arr::map(
                        self::GENRES,
                        fn ($genre) => ['name' => $genre],
                    ),
                );
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
