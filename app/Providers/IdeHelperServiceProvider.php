<?php

namespace App\Providers;

use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class IdeHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->isLocal()) {
            Event::listen(
                MigrationsEnded::class,
                function () {
                    Artisan::call('ide-helper:generate');
                    Artisan::call('ide-helper:models', ['--nowrite' => true]);
                }
            );
        }
    }
}
