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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
