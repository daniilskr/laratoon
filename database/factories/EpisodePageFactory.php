<?php

namespace Database\Factories;

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
