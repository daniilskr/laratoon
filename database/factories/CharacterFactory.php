<?php

namespace Database\Factories;

use App\Models\CharacterPoster;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Arr;

class CharacterFactory extends Factory
{
    public function configure()
    {
        return $this->has(
            CharacterPoster::factory()
                ->has(
                    Image::factory()
                        ->sequence(fn () => [
                            'medium' => Arr::random([
                                'images/character-poster-1.png',
                                'images/character-poster-2.png'
                            ])
                        ])
                )
        );
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'description' => $this->faker->text(),
        ];
    }
}
