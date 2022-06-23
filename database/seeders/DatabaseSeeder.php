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
use App\Models\EpisodePage;
use App\Models\EpisodePoster;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\PublicationStatus;
use App\Models\User;
use App\Models\View;
use App\Models\Viewable;
use App\Services\DemoService;
use Carbon\Carbon;
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
        //Автокомит можно заменить обычной транзакцией
        DB::statement('SET autocommit=0');
        DB::statement('SET unique_checks=0');
        DB::statement('SET foreign_key_checks=0');

        // DB::transaction(function () {
        $this->seedUsers();
        $this->seedComics();
        $this->seedViews();
        $this->seedComments();
        $this->seedLikes();
        // });

        DB::statement('COMMIT');
        DB::statement('SET autocommit=1');
        DB::statement('SET unique_checks=1');
        DB::statement('SET foreign_key_checks=1');
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

        $tags = [
            'magic', 'cats', 'friendship', 'girl main character',
            'slice of life', 'adventures', 'fantasy creatures',
            'wizards', 'scientists', 'sportsmen', 'war',
        ];

        $tags = collect($tags)->map(function ($tag) {
            return ['name' => $tag];
        });

        ComicTag::factory()
                ->count($tags->count())
                ->sequence(...$tags->all())
                ->create();
    }

    protected function seedGenres()
    {
        $this->command->line('Seeding genres');

        $genres = [
            'fantasy', 'sci-fi', 'sports', 'supernatural',
            'comedy', 'adventure', 'action', 'drama',
            'detective', 'romance', 'horror',
        ];

        $genres = collect($genres)->map(function ($genre) {
            return ['name' => $genre];
        });

        Genre::factory()
                ->count($genres->count())
                ->sequence(...$genres)
                ->create();
    }

    protected function seedPublicationStatuses()
    {
        $this->command->line('Seeding publication statuses');

        $statuses = collect([
            'publishing',
            'finished',
            'on hiatus',
            'discounted',
        ])->map(fn ($status) => ['name' => $status]);

        PublicationStatus::factory()
                ->count($statuses->count())
                ->sequence(...$statuses)
                ->create();
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

        $roleTypes = collect([
            CharacterRoleType::Main,
            CharacterRoleType::Secondary,
            CharacterRoleType::Episodic,
        ]);

        $comics->unique()->each(function (Comic $comic) use ($character, $roleTypes) {
            CharacterRole::factory()
                ->for($comic)
                ->for($character)
                ->create(['role_type' => $roleTypes->random()]);
        });
    }

    protected function seedEpisodes(Comic $comic)
    {
        $total = random_int(10, 30);

        $posters = [
            'images/ep-poster-1.png',
            'images/ep-poster-2.png',
            'images/ep-poster-3.png',
        ];

        foreach (genRange(1, $total) as $iteration) {
            $episodePoster = EpisodePoster::factory()
                                ->has(
                                    Image::factory()
                                        ->state([
                                            'medium' => $posters[(($iteration - 1) % count($posters))],
                                        ])
                                );

            $episode = Episode::factory()
                    ->for($comic)
                    ->has($episodePoster)
                    ->create([
                        'number' => $iteration,
                    ]);

            $this->seedEpisodePages($episode);
        }
    }

    protected function seedEpisodePages(Episode $episode)
    {
        $images = [
            'images/E05P00.jpg',
            'images/E05P01.jpg',
            'images/E05P02.jpg',
            'images/E05P03.jpg',
            'images/E05P04.jpg',
            'images/E05P05.jpg',
        ];

        EpisodePage::factory()
                ->for($episode)
                ->has(
                    Image::factory()
                    ->sequence(
                        ...collect($images)->map(fn ($i) => [
                            'medium' => $i,
                        ])
                    )
                )
                ->sequence(
                    ...collect(range(1, count($images)))
                        ->map(fn ($n) => ['order' => $n])
                )
                ->count(count($images))
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
                $this->command->line("<comment>${seeded}/${episodesTotal} episodes are seeded with views</comment>");
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
                $this->command->line("<comment>${seeded}/${comicsTotal} comics are seeded with comments</comment>");
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
                $this->command->line("<comment>${seeded}/${comicsTotal} comics are seeded with likes</comment>");
            }
        });

        Likeable::lazyById(100)->each(function (Likeable $likeable) {
            $likeable->likes_cached_count = $likeable->likes()->count();
            $likeable->save();
        });
    }
}
