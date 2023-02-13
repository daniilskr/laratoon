<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\ComicHeaderBackground;
use App\Models\ComicPoster;
use App\Models\PublicationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ComicFactory extends Factory
{
    public function configure()
    {
        return $this
            ->has(ComicPoster::factory())
            ->has(ComicHeaderBackground::factory())
            ->for(Author::factory())
            ->for(PublicationStatus::factory());
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
            'description' => $this->description(),
            'publishing_start' => Carbon::now()->subYears($yearsSinceStart = random_int(0, 20)),
            'publishing_end' => random_int(0, 100) > 20 ? null : Carbon::now()->subYears(random_int(0, $yearsSinceStart)),
        ];
    }

    /**
     * Faker generators.
     */
    protected function title()
    {
        return Str::headline($this->faker->words(random_int(3, 5), true));
    }

    protected function description()
    {
        return $this->faker->text(130);
    }
}
