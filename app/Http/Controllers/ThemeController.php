<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    /**
     * Switch theme (dark/light)
     */
    public function switch(string $theme): RedirectResponse
    {
        if (in_array($theme, ['light', 'dark'])) {
            session()->put('theme', $theme);

            if (auth()->check()) {
                auth()->user()->update(['theme' => $theme]);
            }
        }

        return redirect()->back();
    }

    /**
     * Get current theme for user
     */
    public static function getCurrentTheme(): string
    {
        if (auth()->check()) {
            return auth()->user()->theme ?? 'dark';
        }

        return session()->get('theme', 'dark');
    }
}
