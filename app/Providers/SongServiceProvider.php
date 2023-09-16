<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SongServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SongService::class, function ($app) {
            return new SongService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
