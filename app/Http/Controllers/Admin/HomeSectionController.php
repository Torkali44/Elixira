<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeSectionRequest;
use App\Models\HomePageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): RedirectResponse|View
    {
        $hero = HomePageSection::query()->where('slug', 'hero')->first();

        if ($hero) {
            return redirect()->route('admin.home-sections.edit', $hero);
        }

        return view('admin.home-sections.index', [
            'sections' => HomePageSection::query()->ordered()->get(),
        ]);
    }

    public function edit(HomePageSection $home_section): View
    {
        abort_unless($home_section->slug === 'hero', 404);

        return view('admin.home-sections.edit', ['section' => $home_section]);
    }

    public function update(UpdateHomeSectionRequest $request, HomePageSection $home_section): RedirectResponse
    {
        abort_unless($home_section->slug === 'hero', 404);

        $data = $request->only(['title', 'subtitle', 'button_label', 'button_url', 'body']);

        if ($request->hasFile('image')) {
            if ($home_section->image) {
                Storage::disk('public')->delete($home_section->image);
            }
            $data['image'] = $request->file('image')->store('home-sections', 'public');
        }

        $home_section->update($data);

        return redirect()->route('admin.home-sections.edit', $home_section)
            ->with('success', __('admin.home_sections_page.updated'));
    }
}
