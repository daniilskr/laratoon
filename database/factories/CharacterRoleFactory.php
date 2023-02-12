<?php

namespace Database\Factories;

use App\Enums\CharacterRoleType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterRoleFactory extends Factory
{
    public const ROLE_TYPES = [
        CharacterRoleType::Main,
        CharacterRoleType::Secondary,
        CharacterRoleType::Episodic,
    ];

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
