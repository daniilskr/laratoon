<?php

namespace App\Providers;

use App\Models\Comment;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use App\Services\Demo\DemoUsersPool;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class DemoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! App::runningInConsole() && config('demo.scope_hide_other_demo_users_comments')) {
            Comment::addGlobalScope(new DoesNotBelongToOtherDemoUsersScope);
        }

        if (
            ! App::runningUnitTests()
            && 'database' !== config('session.driver')
        ) {
            throw new Exception("Demo service requires the 'database' session driver to function properly");
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(DemoUsersPool::class)
            ->needs('$minDemoUserId')
            ->giveConfig('demo.min_demo_user_id');

        $this->app
            ->when(DemoUsersPool::class)
            ->needs('$maxDemoUserId')
            ->giveConfig('demo.max_demo_user_id');
    }
}
