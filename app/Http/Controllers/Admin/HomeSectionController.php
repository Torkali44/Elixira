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
    public function index(): View
    {
        $sections = HomePageSection::ordered()->get();

        return view('admin.home-sections.index', compact('sections'));
    }

    public function edit(HomePageSection $home_section): View
    {
        return view('admin.home-sections.edit', ['section' => $home_section]);
    }

    public function update(UpdateHomeSectionRequest $request, HomePageSection $home_section): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($home_section->image) {
                Storage::disk('public')->delete($home_section->image);
            }
            $data['image'] = $request->file('image')->store('home-sections', 'public');
        } else {
            unset($data['image']);
        }

        $home_section->update($data);

        return redirect()->route('admin.home-sections.index')->with('success', 'Home section updated.');
    }
}
