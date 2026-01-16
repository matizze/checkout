<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('asaas', function () {
            return Http::baseUrl(config('services.asaas.base_url'))
                ->withHeaders([
                    'access_token' => config('services.asaas.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->retry(3, 100);
        });
    }
}
