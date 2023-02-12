<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EpisodePoster>
 */
class EpisodePosterFactory extends Factory
{
    public const POSTERS = [
        'images/ep-poster-1.png',
        'images/ep-poster-2.png',
        'images/ep-poster-3.png',
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
