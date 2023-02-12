<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAvatar>
 */
class UserAvatarFactory extends Factory
{
    public function configure()
    {
        return $this
            ->has(
                Image::factory()
                    ->sequence(fn () => [
                        'medium' => 'images/character-poster-'.Arr::random([1, 2]).'.png',
                    ])
            );
    }

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
