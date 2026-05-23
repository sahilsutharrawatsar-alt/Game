<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', ['reviews' => Review::with(['user', 'venue'])->latest()->paginate(20)]);
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        $review->update($data);

        return back()->with('status', 'Review moderated.');
    }
}
