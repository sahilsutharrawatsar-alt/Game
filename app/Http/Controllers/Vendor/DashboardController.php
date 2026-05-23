<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Earning;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $vendor = $request->user()->vendorProfile;
        $venueIds = $vendor->venues()->pluck('id');

        $stats = [
            'revenue' => Earning::where('vendor_id', $vendor->id)->sum('net_amount'),
            'bookings' => Booking::whereIn('venue_id', $venueIds)->count(),
            'venues' => $venueIds->count(),
            'rating' => round((float) $vendor->venues()->withAvg('reviews', 'rating')->get()->avg('reviews_avg_rating'), 1),
        ];

        $recentBookings = Booking::with(['user', 'venue', 'sport'])
            ->whereIn('venue_id', $venueIds)
            ->latest()
            ->take(8)
            ->get();

        $revenueByDay = Earning::selectRaw('DATE(created_at) as day, SUM(net_amount) as revenue')
            ->where('vendor_id', $vendor->id)
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topVenues = $vendor->venues()
            ->withCount('bookings')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentBookings', 'revenueByDay', 'topVenues'));
    }
}
