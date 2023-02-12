<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComicHeaderBackground>
 */
class ComicHeaderBackgroundFactory extends Factory
{
    public const IMAGES = [
        'images/comic-heading-1.png',
        'images/comic-heading-2.png',
        'images/comic-heading-3.png',
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
