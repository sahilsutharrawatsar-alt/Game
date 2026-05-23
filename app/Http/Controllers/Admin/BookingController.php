<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'venue', 'sport'])->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,confirmed,rejected,cancelled,completed']]);
        $booking->update($data);

        if ($data['status'] === 'rejected') {
            $booking->slot?->update(['status' => 'available']);
        }

        return back()->with('status', 'Booking updated.');
    }
}
