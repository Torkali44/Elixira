<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomePageSection;
use App\Models\Item;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sections = HomePageSection::query()->active()->ordered()->get();

        $featuredItems = Item::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('sections', 'featuredItems'));
    }

    public function explore(): View
    {
        $categories = Category::withCount('items')->orderBy('name')->get();
        $featuredItems = Item::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return view('explore', compact('categories', 'featuredItems'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }
}
