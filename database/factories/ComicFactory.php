<?php

namespace Database\Factories;

use App\Models\ComicHeaderBackground;
use App\Models\ComicPoster;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ComicFactory extends Factory
{
    public function configure()
    {
        $posters = [
            'images/comic-poster-1.png',
            'images/comic-poster-2.png',
            'images/comic-poster-3.png',
        ];

        $headings = [
            'images/comic-heading-1.png',
            'images/comic-heading-2.png',
            'images/comic-heading-3.png',
        ];

        return $this
            ->has(
                ComicPoster::factory()
                    ->has(
                        Image::factory()
                            ->sequence(fn () => [
                                'medium' => Arr::random($posters),
                            ])
                    )
            )
            ->has(
                ComicHeaderBackground::factory()
                    ->has(
                        Image::factory()
                            ->sequence(fn () => [
                                'medium' => Arr::random($headings),
                            ])
                    )
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
