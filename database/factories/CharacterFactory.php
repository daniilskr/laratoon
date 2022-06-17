<?php

namespace Database\Factories;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CharacterFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Character $character) {
            $character->characterPoster->save();
            $character->characterPoster->image->medium = Arr::random(['images/character-poster-1.png', 'images/character-poster-2.png']);
            $character->characterPoster->image->save();
        });
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
