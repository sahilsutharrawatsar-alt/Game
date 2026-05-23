<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Venue $venue)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'max:1000'],
        ]);

        $review = $venue->allReviews()->create($data + ['user_id' => $request->user()->id, 'status' => 'pending']);

        return response()->json(['review' => $review, 'message' => 'Review submitted for moderation.'], 201);
    }
}
