<?php

namespace Database\Factories;

use App\Models\Image;
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

    public function configure()
    {
        return $this
            ->has(
                Image::factory()
                    ->sequence(fn ($sequence) => [
                        'medium' => self::POSTERS[$sequence->index % count(self::POSTERS)],
                    ])
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
