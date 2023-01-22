<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;

class DemoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            Comment::addGlobalScope(new DoesNotBelongToOtherDemoUsersScope);
        }
    }
}
