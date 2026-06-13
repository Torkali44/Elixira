<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of published blogs.
     */
    public function index(): View
    {
        $blogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('blogs.show', compact('blog'));
    }
}
