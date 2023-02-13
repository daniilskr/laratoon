<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EpisodePage>
 */
class EpisodePageFactory extends Factory
{
    public const IMAGES = [
        'images/E05P00.jpg',
        'images/E05P01.jpg',
        'images/E05P02.jpg',
        'images/E05P03.jpg',
        'images/E05P04.jpg',
        'images/E05P05.jpg',
    ];

    public function configure()
    {
        return $this
            ->has(
                Image::factory()
                    ->sequence(fn ($sequence) => [
                        'medium' => self::IMAGES[$sequence->index % count(self::IMAGES)],
                    ])
            )
            ->sequence(fn ($sequence) => ['order' => $sequence->index % count(self::IMAGES)]);
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
