<?php
 
namespace App\Providers;

use App\Http\Resources\CurrentUserResource;
use App\View\Composers\ProfileComposer;
use App\View\Composers\VueAppComposer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Illuminate\Http\Request;
 
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ...
    }
 
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ViewFacade::composer('vue.app', VueAppComposer::class);
        $this->app->when(VueAppComposer::class)
                ->needs('$userDataJson')
                ->give(function (Application $app) {
                    return is_null($user = $app->request?->user())
                            ? null
                            : (new CurrentUserResource($user))->toJson();
                });
    }
}