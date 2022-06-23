<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->title(),
        ];
    }

    protected function title()
    {
        return Str::ucfirst($this->faker->words(random_int(1, 5), true));
    }
}
