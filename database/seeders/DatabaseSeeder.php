<?php

namespace Database\Seeders;

use App\Enums\CharacterRoleType;
use App\Models\Comic;
use App\Models\Author;
use App\Models\Character;
use App\Models\CharacterRole;
use App\Models\Episode;
use App\Models\ComicTag;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\PublicationStatus;
use App\Models\User;
use App\Models\View;
use App\Models\Viewable;
use App\Services\DemoService;
use Carbon\Carbon;
use Database\Factories\ComicTagFactory;
use Database\Factories\GenreFactory;
use Database\Factories\PublicationStatusFactory;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->seedUsers();
            $this->seedComics();
            $this->seedViews();
            $this->seedComments();
            $this->seedLikes();
        });
    }

    protected function seedUsers()
    {
        $this->command->line('Seeding users');

        $chunk = 20;

        repeat(DemoService::DEMO_USERS_MAX_ID / $chunk, function () use ($chunk) {
            User::factory($chunk)->create();
        });
    }

    protected function seedAuthors()
    {
        $this->command->line('Seeding authors');

        Author::factory(20)->create();
    }

    protected function seedComicTags()
    {
        $this->command->line('Seeding tags');

        ComicTag::factory(count(ComicTagFactory::TAGS))->create();
    }

    protected function seedGenres()
    {
        $this->command->line('Seeding genres');

        Genre::factory(count(GenreFactory::GENRES))->create();
    }

    protected function seedPublicationStatuses()
    {
        $this->command->line('Seeding publication statuses');

        PublicationStatus::factory(count(PublicationStatusFactory::STATUSES))->create();
    }

    protected function seedComics()
    {
        $this->command->line('Seeding comics');

        if (0 === Genre::count()) {
            $this->seedGenres();
        }
        $genres = Genre::all();

        if (0 === ComicTag::count()) {
            $this->seedComicTags();
        }
        $tags = ComicTag::all();

        if (0 === Author::count()) {
            $this->seedAuthors();
        }
        $authors = Author::all();

        if (0 === PublicationStatus::count()) {
            $this->seedPublicationStatuses();
        }
        $publicationStatuses = PublicationStatus::all();

        $authors->each(function ($author) use ($tags, $genres, $publicationStatuses) {
            $otherComicsByAuthor = collect();
            $this->command->line("<comment>Seeding comics for author {$author->id}</comment>");

            foreach (genRange(random_int(2, 5)) as $i) {
                $comic = Comic::factory()
                    ->for($author)
                    ->hasAttached($tags->shuffle()->take(random_int(3, min(7, $tags->count()))))
                    ->hasAttached($genres->shuffle()->take(random_int(1, 3)))
                    ->for($publicationStatuses->random())
                    ->create();

                $this->seedCharacters($comic, $otherComicsByAuthor);
                $this->seedEpisodes($comic);

                $otherComicsByAuthor->push($comic);
            }
        });

        $this->command->line('<info>Comics are seeded</info>');
    }

    protected function seedCharacters(Comic $comic, Collection $otherComicsByAuthor)
    {
        $characters = Character::factory(random_int(1, 4))->create();

        foreach ($characters as $character) {
            CharacterRole::factory()
                ->for($comic)
                ->for($character)
                ->create(['role_type' => CharacterRoleType::Main]);

            $this->seedOtherCharacterRoles($character, $otherComicsByAuthor);
        }
    }

    protected function seedOtherCharacterRoles(Character $character, Collection $otherComicsByAuthor)
    {
        $comics = collect();
        if ($otherComicsByAuthor->count() >= 1) {
            $comics = $otherComicsByAuthor->random(random_int(1, min(3, $otherComicsByAuthor->count())));
        }

        $comics->unique()->each(function (Comic $comic) use ($character) {
            CharacterRole::factory()
                ->for($comic)
                ->for($character)
                ->create();
        });
    }

    protected function seedEpisodes(Comic $comic)
    {
        Episode::factory()
                ->for($comic)
                ->count(random_int(10, 30))
                ->create();
    }

    /**
     * Возвращает количество комиксов, предварительно генерируя их, если кол-во равно нолю.
     */
    protected function ensureComicsSeeded(): int
    {
        $comicCount = Comic::count();
        if (0 === $comicCount) {
            $this->seedComics();

            return Comic::count();
        }

        return $comicCount;
    }

    protected function seedViews()
    {
        $this->command->line('Seeding views');

        $this->ensureComicsSeeded();
        $episodesTotal = Episode::count();
        if (0 === $episodesTotal) {
            throw new Exception('There is no comic episodes seeded');
        }

        $users        = collect(range(1, min(2000, User::count())));
        $seeded       = 0;
        $creationDate = Carbon::now()->toDateTimeString();

        Episode::with('viewable')->chunk(10, function ($episodesChunk) use (
            $users,
            &$seeded,
            $episodesTotal,
            $creationDate
        ) {
            $views = $episodesChunk->flatMap(function (Episode $episode) use ($users, $creationDate, &$seeded) {
                $seeded++;

                return $users->shuffle()->take(random_int(1, 30))->map(fn ($user) => [
                    'user_id' => $user,
                    'viewable_id' => $episode->viewable->id,
                    'created_at' => $creationDate,
                    'updated_at' => $creationDate,
                ]);
            });

            View::insert($views->toArray());

            if (($seeded) % 100 === 0 || $seeded === $episodesTotal) {
                $this->command->line("<comment>{$seeded}/{$episodesTotal} episodes are seeded with views</comment>");
            }
        });

        Viewable::whereHasMorph('owner', Episode::class)->lazyById(100)->each(function (Viewable $viewable) {
            $viewable->views_cached_count = $viewable->views()->count();
            $viewable->save();
        });
    }

    protected function seedComments()
    {
        $this->command->line('Seeding comments');

        $comicsTotal = $this->ensureComicsSeeded();
        $demoService = resolve(DemoService::class);
        $users       = collect()->range(1, min($demoService::DEMO_USERS_MIN_ID, User::count()));
        $seeded      = 0;

        Comic::with('commentable')->chunk(10, function ($comicsChunk) use (
            $users,
            &$seeded,
            $comicsTotal
        ) {
            $comicsChunk->each(function ($comic) use ($users, &$seeded) {
                $seeded++;
                $users->shuffle()
                    ->take(random_int(1, 10))
                    ->each(function ($user) use ($comic) {
                        Comment::factory()
                            ->state([
                                'user_id' => $user,
                                'commentable_id' => $comic->commentable->id,
                            ])
                            ->create();
                    });
            });

            if (($seeded) % 10 === 0 || $seeded === $comicsTotal) {
                $this->command->line("<comment>{$seeded}/{$comicsTotal} comics are seeded with comments</comment>");
            }
        });
    }

    protected function seedLikes()
    {
        $this->command->line('Seeding likes');

        $comicsTotal  = $this->ensureComicsSeeded();
        $users        = collect()->range(1, min(2000, User::count()));
        $seeded       = 0;
        $creationDate = Carbon::now()->toDateTimeString();

        Comic::with('likeable')->chunk(10, function ($comicsChunk) use (
            $users,
            $creationDate,
            &$seeded,
            $comicsTotal
        ) {
            $likes = $comicsChunk->flatMap(function ($comic) use ($users, $creationDate, &$seeded) {
                $seeded++;

                return $users->shuffle()->take(random_int(1, 100))->map(fn ($user) => [
                    'user_id' => $user,
                    'likeable_id' => $comic->likeable->id,
                    'created_at' => $creationDate,
                    'updated_at' => $creationDate,
                ]);
            });

            Like::insert($likes->toArray());

            if (($seeded) % 10 === 0 || $seeded === $comicsTotal) {
                $this->command->line("<comment>{$seeded}/{$comicsTotal} comics are seeded with likes</comment>");
            }
        });

        Likeable::lazyById(100)->each(function (Likeable $likeable) {
            $likeable->likes_cached_count = $likeable->likes()->count();
            $likeable->save();
        });
    }
}
