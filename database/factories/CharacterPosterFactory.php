<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CharacterPoster>
 */
class CharacterPosterFactory extends Factory
{
    public const IMAGES = [
        'images/character-poster-1.png',
        'images/character-poster-2.png',
    ];

    public function configure()
    {
        return $this
            ->has(
                Image::factory()
                    ->sequence(fn () => [
                        'medium' => Arr::random(self::IMAGES),
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
