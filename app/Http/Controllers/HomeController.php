<?php

namespace App\Http\Controllers;

use App\Models\HomepageBanner;
use App\Models\Offer;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sports = Sport::where('is_active', true)->orderBy('name')->get();
        $featuredVenues = Venue::query()
            ->with(['images', 'sports'])
            ->with(['bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->where('status', 'available')->orderBy('starts_at')->take(1)])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $nearbyVenues = Venue::query()
            ->with(['images', 'sports'])
            ->with(['bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->where('status', 'available')->orderBy('starts_at')->take(1)])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->whereIn('city', ['Mohali', 'Chandigarh', 'Zirakpur', 'Panchkula'])
            ->take(6)
            ->get();

        $trendingVenues = Venue::query()
            ->with(['images', 'sports'])
            ->with(['bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->where('status', 'available')->orderBy('starts_at')->take(1)])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->orderByDesc('reviews_count')
            ->take(6)
            ->get();

        $offers = Offer::with('coupon')->where('is_active', true)->latest()->take(3)->get();
        $banners = HomepageBanner::where('is_active', true)->orderBy('sort_order')->take(3)->get();

        return view('home', compact('sports', 'featuredVenues', 'nearbyVenues', 'trendingVenues', 'offers', 'banners'));
    }

    public function live(): JsonResponse
    {
        $offers = Offer::with('coupon')->where('is_active', true)->latest()->take(5)->get();
        $venues = Venue::query()
            ->with(['images', 'sports', 'bookingSlots' => fn ($query) => $query->where('starts_at', '>=', now())->where('status', 'available')->orderBy('starts_at')->take(1)])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->latest('updated_at')
            ->take(6)
            ->get();

        return response()->json([
            'updated_at' => now()->toIso8601String(),
            'offers' => $offers->map(fn ($offer) => [
                'title' => $offer->title,
                'subtitle' => $offer->subtitle,
                'code' => $offer->coupon?->code,
                'badge' => $offer->badge,
            ]),
            'venues' => $venues->map(fn ($venue) => [
                'name' => $venue->name,
                'city' => $venue->city,
                'price' => (float) $venue->base_price,
                'rating' => round((float) $venue->reviews_avg_rating, 1),
                'url' => route('venues.show', $venue),
                'image' => $venue->images->first()?->path,
                'next_slot' => $venue->bookingSlots->first()?->starts_at?->format('h:i A'),
            ]),
        ]);
    }
}
