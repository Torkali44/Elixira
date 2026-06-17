<?php

namespace App\Http\Middleware;

use App\Support\ItemPricingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale');

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->locale && in_array($user->locale, ['en', 'ar'], true)) {
                $locale = $user->locale;
                session()->put('locale', $locale);
            }

            $defaultTheme = $request->routeIs('admin.*') || $request->routeIs('vendor.*') ? 'light' : 'dark';
            $theme = in_array($user->theme, ['light', 'dark'], true) ? $user->theme : $defaultTheme;
            if (! $request->routeIs('admin.*') && ! $request->routeIs('vendor.*')) {
                $theme = 'dark';
            }
            session()->put('theme', $theme);
            view()->share('userTheme', $theme);
        } else {
            if (session()->has('locale') && in_array(session('locale'), ['en', 'ar'], true)) {
                $locale = session('locale');
            }

            $defaultTheme = $request->routeIs('admin.*') || $request->routeIs('vendor.*') ? 'light' : 'dark';
            $theme = session('theme', $defaultTheme);
            if (! $request->routeIs('admin.*') && ! $request->routeIs('vendor.*')) {
                $theme = 'dark';
            }
            if (! in_array($theme, ['light', 'dark'], true)) {
                $theme = 'dark';
            }

            view()->share('userTheme', $theme);
        }

        app()->setLocale($locale);

        $user = auth()->user();
        session(['shopping_country' => app(ItemPricingService::class)->detectUserCountry($user)]);

        view()->share('currentLocale', $locale);
        view()->share('isRtl', $locale === 'ar');

        return $next($request);
    }
}
