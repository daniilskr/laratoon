<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComicTagFactory extends Factory
{
    public const TAGS = [
        'magic', 'cats', 'friendship', 'girl main character',
        'slice of life', 'adventures', 'fantasy creatures',
        'wizards', 'scientists', 'sportsmen', 'war',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
