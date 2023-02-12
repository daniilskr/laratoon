<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
