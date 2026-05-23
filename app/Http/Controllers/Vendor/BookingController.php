<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $venueIds = $request->user()->vendorProfile->venues()->pluck('id');
        $bookings = Booking::with(['user', 'venue', 'sport'])->whereIn('venue_id', $venueIds)->latest()->paginate(20);

        return view('vendor.bookings.index', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        abort_unless($booking->venue->vendor_id === $request->user()->vendorProfile->id, 403);
        $data = $request->validate(['status' => ['required', 'in:pending,confirmed,rejected,cancelled,completed']]);
        $booking->update($data);

        if (in_array($data['status'], ['rejected', 'cancelled'], true)) {
            $booking->slot?->update(['status' => 'available']);
        }

        return back()->with('status', 'Booking updated.');
    }
}
