<?php

namespace App\Providers;

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
        // Set default locale
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } elseif (auth()->check() && auth()->user()->locale) {
            app()->setLocale(auth()->user()->locale);
        }
    }
}
