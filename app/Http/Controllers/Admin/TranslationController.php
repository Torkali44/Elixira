<?php

namespace App\Http\Controllers\Admin;

use App\Support\TranslationManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function index(Request $request): View
    {
        $sections = TranslationManager::sections();
        $activeSection = $request->query('section', 'app');

        if (! isset($sections[$activeSection])) {
            $activeSection = 'app';
        }

        $translations = [
            'en' => TranslationManager::loadSection($activeSection, 'en'),
            'ar' => TranslationManager::loadSection($activeSection, 'ar'),
        ];

        $flatTranslations = [
            'en' => TranslationManager::flatten($translations['en']),
            'ar' => TranslationManager::flatten($translations['ar']),
        ];

        return view('admin.settings.translations', [
            'sections' => $sections,
            'activeSection' => $activeSection,
            'flatTranslations' => $flatTranslations,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $language = $request->input('language');
        $section = $request->input('section', 'app');
        $translations = $request->input('translations', []);

        if (! in_array($language, ['en', 'ar'], true)) {
            return back()->with('error', __('admin.common.validation_error'));
        }

        if (! isset(TranslationManager::sections()[$section])) {
            return back()->with('error', __('admin.common.validation_error'));
        }

        TranslationManager::saveSection($section, $language, $translations);

        return redirect()
            ->route('admin.settings.translations', ['section' => $section])
            ->with('success', __('admin.translations_page.saved'));
    }
}
