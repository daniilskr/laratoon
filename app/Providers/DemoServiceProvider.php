<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use App\Services\DemoService;
use Exception;
use Illuminate\Support\Facades\App;

class DemoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! App::runningInConsole()) {
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
            ->when(DemoService::class)
            ->needs('$minDemoUserId')
            ->giveConfig('demo.min_demo_user_id');

        $this->app
            ->when(DemoService::class)
            ->needs('$maxDemoUserId')
            ->giveConfig('demo.max_demo_user_id');
    }
}
