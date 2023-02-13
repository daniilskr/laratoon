<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PublicationStatus>
 */
class PublicationStatusFactory extends Factory
{
    public const STATUSES = [
        'publishing',
        'finished',
        'on hiatus',
        'discounted',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => Arr::random(self::STATUSES),
        ];
    }
}
