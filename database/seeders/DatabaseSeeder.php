<?php

namespace Database\Seeders;

use App\Enums\CharacterRoleType;
use App\Models\Author;
use App\Models\Character;
use App\Models\CharacterRole;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Episode;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\User;
use App\Models\View;
use App\Models\Viewable;
use App\Services\Demo\DemoUsersPool;
use Carbon\Carbon;
use Database\Factories\ComicTagFactory;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
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

        repeat(app(DemoUsersPool::class)->maxDemoUserId / $chunk, function () use ($chunk) {
            User::factory($chunk)->create();
        });
    }

    protected function seedAuthors()
    {
        $this->command->line('Seeding authors');

        Author::factory(20)->create();
    }

    protected function seedComics()
    {
        $this->command->line('Seeding comics');

        if (0 === Author::count()) {
            $this->seedAuthors();
        }
        $authors = Author::all();

        $authors->each(function ($author) {
            $otherComicsByAuthor = collect();
            $this->command->line("<comment>Seeding comics for author {$author->id}</comment>");

            foreach (genRange(random_int(2, 5)) as $i) {
                $comic = Comic::factory()
                    ->for($author)
                    ->hasTagsAttached(random_int(2, count(ComicTagFactory::TAGS)))
                    ->hasGenresAttached(random_int(1, 2))
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
        $users       = collect()->range(1, min(app(DemoUsersPool::class)->minDemoUserId, User::count()));
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
