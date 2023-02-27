<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Models\Author;
use App\Models\Character;
use App\Models\Comic;
use App\Models\ComicPoster;
use App\Models\CharacterPoster;
use App\Models\Episode;
use App\Models\EpisodePoster;
use App\Models\ComicHeaderBackground;
use App\Models\Comment;
use App\Models\EpisodePage;
use App\Models\Like;
use App\Models\UserAvatar;
use App\Models\View;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTelescope();
        $this->registerSingletons();
    }

    protected function registerTelescope()
    {
        if (isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    protected function registerSingletons()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootEloquentRelationships();
        $this->enforceStrictEloquentModelBehaviour();
    }

    protected function enforceStrictEloquentModelBehaviour()
    {
        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();
    }

    protected function bootEloquentRelationships()
    {
        /**
         * Текстовые индексы до идиотизма громадные.
         */
        $morphMap = [
            [1,   Comic::class],
            [20,  Author::class],
            [60,  Character::class],
            [80,  CharacterPoster::class],
            [120, Episode::class],
            [121, EpisodePage::class],
            [140, EpisodePoster::class],
            [160, ComicHeaderBackground::class],
            [180, ComicPoster::class],
            [260, Comment::class],
            [261, View::class],
            [262, Like::class],
            [263, UserAvatar::class],
        ];

        // isLocal() to not trigger autoload in production
        if (isLocal()) {
            collect($morphMap)->map(fn ($val) => ($val[1]))->each(function ($class) {
                if (! is_subclass_of($class, Model::class)) {
                    throw new \LogicException("{$class} does not extend the Eloquent Model class. Probably you forgot to import the class");
                }
            });
        }

        if (count($morphMap) !== collect($morphMap)->map(fn ($val) => $val[0])->unique()->count()) {
            throw new \LogicException('Number keys must be unique in the morph map');
        }

        if (count($morphMap) !== collect($morphMap)->map(fn ($val) => $val[1])->unique()->count()) {
            throw new \LogicException('Some class is accuring more than one time in the morph map');
        }

        Relation::enforceMorphMap(collect($morphMap)->mapWithKeys(fn ($pair) => [$pair[0] => $pair[1]])->toArray());
    }
}
