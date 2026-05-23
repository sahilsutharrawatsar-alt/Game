<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $sports = Sport::where('is_active', true)->orderBy('name')->get();

        $venues = Venue::query()
            ->with(['images', 'sports', 'bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->where('status', 'available')->orderBy('starts_at')->take(1)])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->when($request->filled('q'), fn ($query) => $query->where(function ($inner) use ($request) {
                $inner->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('city', 'like', '%'.$request->q.'%')
                    ->orWhere('address', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->city))
            ->when($request->filled('sport'), fn ($query) => $query->whereHas('sports', fn ($sport) => $sport->where('slug', $request->sport)))
            ->when($request->filled('min_price'), fn ($query) => $query->where('base_price', '>=', $request->integer('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('base_price', '<=', $request->integer('max_price')))
            ->when($request->filled('near'), fn ($query) => $query->orderByRaw('CASE WHEN city = ? THEN 0 ELSE 1 END', [$request->near]))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $cities = Venue::where('status', 'active')->distinct()->orderBy('city')->pluck('city');

        return view('venues.index', compact('venues', 'sports', 'cities'));
    }

    public function show(Venue $venue)
    {
        $venue->load([
            'images',
            'sports',
            'amenities',
            'bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->whereIn('status', ['available', 'held'])->orderBy('starts_at')->take(80),
            'bookingSlots.sport',
            'reviews.user',
        ])->loadAvg('reviews', 'rating')->loadCount('reviews');

        return view('venues.show', compact('venue'));
    }
}
