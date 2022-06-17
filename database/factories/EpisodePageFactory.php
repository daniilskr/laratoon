<?php

namespace Database\Factories;

use App\Models\Episode;
use App\Models\EpisodePage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EpisodePage>
 */
class EpisodePageFactory extends Factory
{
    /**
     * State Modifiers.
     */
    public function episode(Episode $episode)
    {
        return $this->afterMaking(function (EpisodePage $instance) use ($episode) {
            $instance->episode()->associate($episode);
        });
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
