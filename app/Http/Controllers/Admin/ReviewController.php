<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tag;
use App\Support\TagService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.create', [
            'tagSuggestions' => Tag::query()->orderBy('name')->pluck('name')->all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:whatsapp,instagram,external,video',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'nullable|string|max:1000',
        ]);

        $avatar = null;
        $content = $request->content;

        if ($request->type === 'video') {
            // Content must be the link
            if (! $content) {
                return redirect()->back()->withErrors(['content' => 'YouTube link is required for videos.']);
            }
        } else {
            // Must have an image
            if (! $request->hasFile('image')) {
                return redirect()->back()->withErrors(['image' => 'An image is required for screenshots.']);
            }
            $avatar = $request->file('image')->store('reviews', 'public');
        }

        $review = Review::create([
            'type' => $request->type,
            'avatar' => $avatar,
            'content' => $content,
            'status' => 'approved',
        ]);

        app(TagService::class)->syncFromInput($review, $request->input('tags'));

        return redirect()->route('admin.reviews.index')->with('success', 'Testimonial added successfully.');
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Review status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
