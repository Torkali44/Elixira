<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $locale = app()->getLocale();

        $items = collect();
        $blogs = collect();
        $faqs = collect();

        if ($query !== '') {
            $like = '%'.$query.'%';

            $items = Item::query()
                ->with(['category', 'brandModel', 'countryPrices'])
                ->publiclyVisible()
                ->where(function ($builder) use ($like) {
                    $builder->where('name', 'like', $like)
                        ->orWhere('name_en', 'like', $like)
                        ->orWhere('name_ar', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('description_en', 'like', $like)
                        ->orWhere('description_ar', 'like', $like);
                })
                ->latest()
                ->limit(24)
                ->get();

            $blogs = Blog::query()
                ->where('is_published', true)
                ->where(function ($builder) use ($like) {
                    $builder->where('title_en', 'like', $like)
                        ->orWhere('title_ar', 'like', $like)
                        ->orWhere('summary_en', 'like', $like)
                        ->orWhere('summary_ar', 'like', $like)
                        ->orWhere('content_en', 'like', $like)
                        ->orWhere('content_ar', 'like', $like);
                })
                ->latest('published_at')
                ->limit(12)
                ->get();

            $faqs = Faq::query()
                ->where('is_published', true)
                ->where(function ($builder) use ($like, $locale) {
                    if ($locale === 'ar') {
                        $builder->where('question_ar', 'like', $like)
                            ->orWhere('answer_ar', 'like', $like);
                    } else {
                        $builder->where('question_en', 'like', $like)
                            ->orWhere('answer_en', 'like', $like);
                    }
                })
                ->orderBy('sort_order')
                ->limit(12)
                ->get();
        }

        return view('search.index', compact('query', 'items', 'blogs', 'faqs'));
    }
}
