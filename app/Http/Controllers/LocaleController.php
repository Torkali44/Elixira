<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Switch language locale.
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'ar'])) {
            session()->put('locale', $locale);

            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }

        $backUrl = url()->previous();
        $parsed = parse_url($backUrl);
        $query = [];
        if (! empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        if (request()->has('step')) {
            $query['step'] = request('step');
        }

        $path = ($parsed['path'] ?? '/');
        $redirectTo = $path.(empty($query) ? '' : '?'.http_build_query($query));

        return redirect($redirectTo);
    }
}
