<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ComicTagFactory extends Factory
{
    public const TAGS = [
        'magic', 'cats', 'friendship', 'girl main character',
        'slice of life', 'adventures', 'fantasy creatures',
        'wizards', 'scientists', 'sportsmen', 'war',
    ];

    public function configure()
    {
        return $this
                ->sequence(
                    ...Arr::map(
                        self::TAGS,
                        fn ($tag) => ['name' => $tag],
                    ),
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
            //
        ];
    }
}
