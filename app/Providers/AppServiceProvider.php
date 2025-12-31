<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // السطر المهم اللي ضفناه

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
        Vite::prefetch(concurrency: 3);

        // هذا الكود يحل مشكلة الشاشة البيضاء (Mixed Content) على Render
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
