<?php

namespace App\Providers;

use App\Services\Scoring\ScoreProxy;
use App\Services\Scoring\ScoringService;
use App\Services\Scoring\ScoringServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind ScoringServiceInterface → ScoreProxy(ScoringService)
        $this->app->bind(ScoringServiceInterface::class, function ($app) {
            return new ScoreProxy(new ScoringService());
        });
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('userAccess', new \App\Patterns\Proxy\RoleAccessProxy(auth()->user()));
            }
        });
    }
}
