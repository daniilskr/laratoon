<?php

namespace Database\Factories;

use App\Models\CharacterPoster;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterFactory extends Factory
{
    public function configure()
    {
        return $this
            ->has(CharacterPoster::factory());
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
