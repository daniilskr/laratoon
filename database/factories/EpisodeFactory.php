<?php

namespace Database\Factories;

use App\Models\EpisodePage;
use App\Models\EpisodePoster;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{
    public function configure()
    {
        return $this
            ->has(EpisodePoster::factory())
            ->has(
                EpisodePage::factory()
                    ->count(count(EpisodePageFactory::IMAGES))
            )->sequence(fn ($sequence) => [
                'number' => $sequence->index + 1,
            ]);
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
