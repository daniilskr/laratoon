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
        Comment::addGlobalScope(new DoesNotBelongToOtherDemoUsersScope);
    }
}
