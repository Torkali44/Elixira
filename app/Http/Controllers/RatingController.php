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
        ]);

        if (!auth()->check()) {
            return back()->with('error', 'You must be logged in to rate.');
        }

        // Validate that the rateable type is allowed
        $allowedTypes = [
            'App\Models\Brand',
            'App\Models\Item'
        ];

        if (!in_array($request->rateable_type, $allowedTypes)) {
            return back()->with('error', 'Invalid rating target.');
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
            ]
        );

        return back()->with('success', 'Your rating has been saved successfully!');
    }
}
