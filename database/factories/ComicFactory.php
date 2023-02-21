<?php

namespace Database\Factories;

use App\Enums\CharacterRoleType;
use App\Models\Author;
use App\Models\CachedLatestViewedEpisodeByUser;
use App\Models\Character;
use App\Models\CharacterRole;
use App\Models\Comic;
use App\Models\ComicHeaderBackground;
use App\Models\ComicPoster;
use App\Models\ComicTag;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\PublicationStatus;
use App\Models\User;
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
            ->forPublicationStatus();
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

    /**
     * State modifiers.
     */
    public function hasTagsAttached(int $count = 1)
    {
        return $this->afterCreating(function (Comic $comic) use ($count) {
            $tags = ComicTag::count()
                    ? ComicTag::limit($count)->inRandomOrder()->get()
                    : ComicTag::factory(count(ComicTagFactory::TAGS))->create()->shuffle()->take($count);

            $comic->comicTags()->attach($tags);
        });
    }

    public function hasGenresAttached(int $count = 1)
    {
        return $this->afterCreating(function (Comic $comic) use ($count) {
            $genres = Genre::count()
                    ? Genre::limit($count)->inRandomOrder()->get()
                    : Genre::factory(count(GenreFactory::GENRES))->create()->shuffle()->take($count);

            $comic->genres()->attach($genres);
        });
    }

    public function hasCachedLatestViewedEpisodeByUser(User $user)
    {
        return $this->afterCreating(function (Comic $comic) use ($user) {
            CachedLatestViewedEpisodeByUser::factory()->state([
                'user_id' => modelKey($user),
                'comic_id' => modelKey($comic),
                'episode_id' => modelKey(
                    $episode
                    ?? Episode::whereComic($comic)->orderByDesc('id')->first()
                    ?? Episode::factory()->for($comic)->create()
                ),
            ])->create();
        });
    }

    public function hasMainCharacters(int $count = 1)
    {
        return $this->afterCreating(function (Comic $comic) use ($count) {
            Character::factory($count)
                ->has(
                    CharacterRole::factory()
                        ->state([
                            'role_type' => CharacterRoleType::Main,
                        ])
                        ->for($comic)
                )
                ->create();
        });
    }

    public function hasOtherComicsByAuthor(int $count = 1)
    {
        return $this->afterCreating(function (Comic $comic) use ($count) {
            Comic::factory($count)
                ->for($comic->author)
                ->create();
        });
    }

    protected function forPublicationStatus()
    {
        return $this->state(fn () => [
            'publication_status_id' => modelKey(
                PublicationStatus::count()
                ? PublicationStatus::inRandomOrder()->first()
                : PublicationStatus::factory(count(PublicationStatusFactory::STATUSES))->create()->random()
            ),
        ]);
    }
}
