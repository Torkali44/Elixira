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

            // Also save to user preferences if authenticated
            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }

        return redirect()->back();
    }
}
