<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VenueResource;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $venues = Venue::query()
            ->with(['images', 'sports', 'amenities'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->city))
            ->when($request->filled('sport'), fn ($query) => $query->whereHas('sports', fn ($sport) => $sport->where('slug', $request->sport)))
            ->paginate($request->integer('per_page', 12));

        return VenueResource::collection($venues);
    }

    public function show(Venue $venue)
    {
        $venue->load(['images', 'sports', 'amenities', 'bookingSlots', 'reviews.user'])
            ->loadAvg('reviews', 'rating')
            ->loadCount('reviews');

        return new VenueResource($venue);
    }
}
