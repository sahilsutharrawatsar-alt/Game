<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bookingsQuery = $request->user()->bookings()
            ->with(['venue.images', 'sport', 'slot'])
            ->latest();

        $bookings = (clone $bookingsQuery)->paginate(8);
        $upcomingBookings = (clone $bookingsQuery)->where('starts_at', '>=', now())->whereNotIn('status', ['cancelled', 'rejected'])->take(3)->get();
        $totalSpend = (clone $bookingsQuery)->where('payment_status', 'paid')->sum('total');

        $favorites = $request->user()->favorites()->with(['images', 'sports'])->take(6)->get();
        $offers = Offer::with('coupon')->where('is_active', true)->latest()->take(3)->get();
        $notifications = $request->user()->notifications()->latest()->take(6)->get();
        $activity = (clone $bookingsQuery)->take(5)->get();

        return view('dashboard.index', compact('bookings', 'favorites', 'upcomingBookings', 'totalSpend', 'offers', 'notifications', 'activity'));
    }

    public function profile(Request $request)
    {
        return view('dashboard.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->update($data);

        return back()->with('status', 'Profile updated.');
    }
}
