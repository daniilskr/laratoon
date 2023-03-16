<?php

namespace App\Services;

use App\Exceptions\Services\LazyLoadDisabled;
use App\Models\Viewable;
use App\Models\User;
use App\Models\View;
use Illuminate\Support\Collection;

class ViewableViewsByUsersRepository
{
    /**
     * @property Collection<int,View> $viewables
     */
    protected Collection $collection;

    protected array $with = [];

    public function __construct(
        public bool $lazyLoad = true,
    ) {
        $this->collection = new Collection();
    }

    /**
     * @throws LazyLoadDisabled
     */
    public function getForUserAndViewable(int|User $user, int|Viewable $viewable): ?View
    {
        $key = $this->getKeyFromUserAndViewable($user, $viewable);

        if (! $this->collection->has($key)) {
            if (! $this->lazyLoad) {
                throw new LazyLoadDisabled('Lazy loading of entities is disabled, load them eagerly with loadForUserAndViewables()');
            }

            $this->loadForUserAndViewables($user, collect([$viewable]));
        }

        return $this->collection[$key];
    }

    /**
     * @param Collection<int,int|Viewable> $viewables
     */
    public function loadForUserAndViewables(int|User $user, $viewables): void
    {
        $viewables = collected($viewables)
                    // Filter out previously queried
                    ->reject(fn (int|Viewable $viewable) => $this->collection->has(
                        $this->getKeyFromUserAndViewable($user, $viewable),
                    ))
                    // Set default value (null) for all the entities,
                    // to not query for them again next time
                    ->each(function (int|Viewable $viewable) use ($user) {
                        $this->collection[$this->getKeyFromUserAndViewable($user, $viewable)] = null;
                    });

        if ($viewables->isEmpty()) {
            return;
        }

        View::whereUser($user)
                ->with($this->with)
                ->whereViewableIn($viewables)
                ->get()
                ->each(function (View $ep) {
                    $this->collection[$this->getKeyForEntity($ep)] = $ep;
                });
    }

    protected function getKeyForEntity(View $ep): string
    {
        return $this->getKeyFromUserAndViewable($ep->getUserId(), $ep->getViewableId());
    }

    protected function getKeyFromUserAndViewable(int|User $user, int|Viewable $viewable): string
    {
        return modelKey($user).'_'.modelKey($viewable);
    }
}
