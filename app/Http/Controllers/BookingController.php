<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Services\BookingService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request, BookingService $bookings, RazorpayService $razorpay)
    {
        $data = $request->validate([
            'booking_slot_id' => ['required', 'exists:booking_slots,id'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $booking = $bookings->create(
            $request->user(),
            BookingSlot::findOrFail($data['booking_slot_id']),
            $data['coupon_code'] ?? null,
            $data['notes'] ?? null
        );

        $order = $razorpay->createOrder($booking);

        return view('bookings.checkout', compact('booking', 'order'));
    }

    public function confirm(Request $request, Booking $booking, BookingService $bookings, RazorpayService $razorpay)
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        $data = $request->validate([
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        abort_unless($razorpay->verifySignature($data + ['razorpay_order_id' => request('order_id', 'demo')]), 422, 'Payment verification failed.');

        $booking = $bookings->markPaid($booking, $data);

        return view('bookings.confirmation', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking, BookingService $bookings)
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        $bookings->cancel($booking);

        return back()->with('status', 'Booking cancelled.');
    }

    public function invoice(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        $booking->load(['user', 'venue', 'sport', 'payment']);

        return response()
            ->view('bookings.invoice', compact('booking'))
            ->header('Content-Disposition', 'attachment; filename="invoice-'.$booking->booking_number.'.html"');
    }
}
