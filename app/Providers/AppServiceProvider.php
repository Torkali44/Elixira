<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceRootUrl(config('app.url'));

            if (str_starts_with((string) config('app.url'), 'https://')) {
                URL::forceScheme('https');
                config(['session.secure' => true]);
            }
        } elseif ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }

        Paginator::defaultView('pagination.elixira');
        Paginator::defaultSimpleView('pagination.elixira');
    }
}
