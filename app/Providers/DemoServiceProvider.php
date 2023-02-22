<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use App\Services\DemoService;

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
