<?php

namespace Database\Factories;

use App\Models\Comic;
use App\Models\Character;
use App\Models\CharacterRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterRoleFactory extends Factory
{
    /**
     * State Modifiers.
     */
    public function comic(Comic $comic)
    {
        return $this->afterMaking(function ($instance) use ($comic) {
            $instance->comic()->associate($comic);
        });
    }

    public function character(Character $character)
    {
        return $this->afterMaking(function (CharacterRole $role) use ($character) {
            $role->character()->associate($character);
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
            'description' => $this->faker->text(64),
        ];
    }
}
