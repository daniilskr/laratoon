<?php

namespace App\Services;

use App\Exceptions\Services\LazyLoadDisabled;
use App\Models\CachedLatestViewedEpisodeByUser;
use App\Models\Comic;
use App\Models\User;
use Illuminate\Support\Collection;

class CachedLatestViewedEpisodesRepository
{
    /**
     * @property Collection<int,CachedLatestViewedEpisodeByUser> $comics
     */
    protected Collection $collection;

    protected array $with = [
        'episode',
    ];

    public function __construct(
        public bool $lazyLoad = true,
    ) {
        $this->collection = new Collection();
    }

    public function getForUserAndComic(int|User $user, int|Comic $comic): ?CachedLatestViewedEpisodeByUser
    {
        $key = $this->getKeyFromUserAndComic($user, $comic);

        if (! $this->collection->has($key)) {
            if (! $this->lazyLoad) {
                throw new LazyLoadDisabled('Lazy loading of entities is disabled, load them eagerly with loadForUserAndComics()');
            }

            $this->loadForUserAndComics($user, collect([$comic]));
        }

        return $this->collection[$key];
    }

    /**
     * @param Collection<int,int|Comic> $comics
     */
    public function loadForUserAndComics(int|User $user, $comics): void
    {
        $comics = collected($comics)
                    // Filter out previously queried
                    ->filter(fn (int|Comic $comic) => false === $this->collection->has(
                        $this->getKeyFromUserAndComic($user, $comic),
                    ))
                    // Set default value (null) for all the entities,
                    // to not query for them again next time
                    ->each(function (int|Comic $comic) use ($user) {
                        $this->collection[$this->getKeyFromUserAndComic($user, $comic)] = null;
                    });

        if ($comics->isEmpty()) {
            return;
        }

        CachedLatestViewedEpisodeByUser::whereUser($user)
                    ->with($this->with)
                    ->whereComicIn($comics)
                    ->get()
                    ->each(function (CachedLatestViewedEpisodeByUser $ep) {
                        $this->collection[$this->getKeyForEntity($ep)] = $ep;
                    });
    }

    protected function getKeyForEntity(CachedLatestViewedEpisodeByUser $ep): string
    {
        return $this->getKeyFromUserAndComic($ep->getUserId(), $ep->getComicId());
    }

    protected function getKeyFromUserAndComic(int|User $user, int|Comic $comic): string
    {
        return modelKey($user).'_'.modelKey($comic);
    }
}
