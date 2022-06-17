<?php

namespace Database\Factories;

use App\Models\Comic;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{
    /**
     * State Modifiers.
     */
    public function comic(Comic $comic)
    {
        return $this->afterMaking(function ($episode) use ($comic) {
            $episode->comic()->associate($comic);
        });
    }

    /**
     * State Modifiers.
     */
    public function poster(string $poster)
    {
        return $this->afterCreating(function (Episode $episode) use ($poster) {
            $episode->episodePoster->save();
            $episode->episodePoster->image->medium = $poster;
            $episode->episodePoster->image->save();
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
            'title' => $this->title(),
        ];
    }

    protected function title()
    {
        return Str::ucfirst($this->faker->words(random_int(1, 5), true));
    }
}
