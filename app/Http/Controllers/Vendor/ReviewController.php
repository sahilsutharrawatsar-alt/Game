<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $venueIds = $request->user()->vendorProfile->venues()->pluck('id');

        return view('vendor.reviews.index', [
            'reviews' => Review::with(['user', 'venue'])->whereIn('venue_id', $venueIds)->latest()->paginate(20),
        ]);
    }
}
