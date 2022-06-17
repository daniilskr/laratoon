<?php

namespace Database\Factories;

use App\Models\Comic;
use App\Models\Author;
use App\Models\PublicationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ComicFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Comic $comic) {
            $this->attachRandomPoster($comic);
            $this->attachRandomHeading($comic);
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
        return Str::headline($this->faker->words(random_int(1, 5), true));
    }

    protected function description()
    {
        return $this->faker->text(130);
    }

    /**
     * Factory states.
     */
    public function author(Author $author)
    {
        return $this->afterMaking(function (Comic $comic) use ($author) {
            $comic->author()->associate($author);
        });
    }

    public function comicTags(Collection $tags)
    {
        return $this->afterCreating(function (Comic $comic) use ($tags) {
            $comic->comicTags()->attach($tags->map(fn ($el) => $el->id));
        });
    }

    public function genres(Collection $genres)
    {
        return $this->afterCreating(function (Comic $comic) use ($genres) {
            $comic->genres()->attach($genres->map(fn ($el) => $el->id));
        });
    }

    public function publicationStatus(PublicationStatus $publicationStatus)
    {
        return $this->afterMaking(function (Comic $comic) use ($publicationStatus) {
            $comic->publicationStatus()->associate($publicationStatus);
        });
    }

    /**
     * Functions to attach relations.
     */
    protected function attachRandomPoster(Comic $comic)
    {
        $posters = collect([
            'images/comic-poster-1.png',
            'images/comic-poster-2.png',
            'images/comic-poster-3.png',
        ]);

        $comic->comicPoster->save();
        $comic->comicPoster->image->medium = $posters->random();
        $comic->comicPoster->image->save();
    }

    protected function attachRandomHeading(Comic $comic)
    {
        $headings = collect([
            'images/comic-heading-1.png',
            'images/comic-heading-2.png',
            'images/comic-heading-3.png',
        ]);

        $comic->comicHeaderBackground->save();
        $comic->comicHeaderBackground->image->medium = $headings->random();
        $comic->comicHeaderBackground->image->save();
    }
}
