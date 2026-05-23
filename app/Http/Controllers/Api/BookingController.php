<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return BookingResource::collection($request->user()->bookings()->with(['venue.images', 'sport'])->latest()->paginate(10));
    }

    public function store(Request $request, BookingService $bookings)
    {
        $data = $request->validate([
            'booking_slot_id' => ['required', 'exists:booking_slots,id'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
        ]);

        $booking = $bookings->create($request->user(), BookingSlot::findOrFail($data['booking_slot_id']), $data['coupon_code'] ?? null);

        return new BookingResource($booking);
    }

    public function cancel(Request $request, Booking $booking, BookingService $bookings)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        return new BookingResource($bookings->cancel($booking));
    }
}
