<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Venue $venue)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $venue->allReviews()->create([
            ...$data,
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Review submitted for moderation.');
    }
}
