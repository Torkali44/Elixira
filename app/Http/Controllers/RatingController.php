<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rateable_id' => 'required|integer',
            'rateable_type' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:4096',
        ]);

        if (! auth()->check()) {
            return back()->with('error', __('shop.login_to_rate'));
        }

        $allowedTypes = [
            'App\Models\Brand',
            'App\Models\Item',
        ];

        if (! in_array($request->rateable_type, $allowedTypes, true)) {
            return back()->with('error', 'Invalid rating target.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ratings', 'public');
        }

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'rateable_id' => $request->rateable_id,
                'rateable_type' => $request->rateable_type,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'image' => $imagePath,
            ]
        );

        return back()->with('success', __('shop.rating_saved'));
    }
}
