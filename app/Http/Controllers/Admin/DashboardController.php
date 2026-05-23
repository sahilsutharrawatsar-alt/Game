<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'revenue' => Booking::where('payment_status', 'paid')->sum('total'),
            'bookings' => Booking::count(),
            'venues' => Venue::count(),
            'users' => User::where('role', 'user')->count(),
            'vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
        ];

        $recentBookings = Booking::with(['user', 'venue', 'sport'])->latest()->take(8)->get();
        $revenueByDay = Booking::selectRaw('DATE(created_at) as day, SUM(total) as revenue')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        $vendorPerformance = Vendor::withCount('venues')
            ->withSum('earnings', 'net_amount')
            ->orderByDesc('earnings_sum_net_amount')
            ->take(6)
            ->get();
        $userGrowth = User::selectRaw('DATE(created_at) as day, COUNT(*) as users')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'revenueByDay', 'vendorPerformance', 'userGrowth'));
    }
}
