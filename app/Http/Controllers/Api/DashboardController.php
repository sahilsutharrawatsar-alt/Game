<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\VenueResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'bookings' => BookingResource::collection($request->user()->bookings()->with(['venue.images', 'sport'])->latest()->take(10)->get()),
            'favorites' => VenueResource::collection($request->user()->favorites()->with(['images', 'sports'])->get()),
            'notifications' => $request->user()->notifications()->latest()->take(10)->get(),
            'offers' => Offer::with('coupon')->where('is_active', true)->latest()->take(5)->get(),
            'stats' => [
                'upcoming' => $request->user()->bookings()->where('starts_at', '>=', now())->whereNotIn('status', ['cancelled', 'rejected'])->count(),
                'total_spend' => (float) $request->user()->bookings()->where('payment_status', 'paid')->sum('total'),
                'favorites' => $request->user()->favorites()->count(),
            ],
        ]);
    }
}
