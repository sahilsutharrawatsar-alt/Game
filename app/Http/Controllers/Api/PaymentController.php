<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function order(Request $request, Booking $booking, RazorpayService $razorpay)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        return response()->json(['order' => $razorpay->createOrder($booking)]);
    }

    public function verify(Request $request, Booking $booking, BookingService $bookings, RazorpayService $razorpay)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        abort_unless($razorpay->verifySignature($data), 422, 'Payment verification failed.');

        return new BookingResource($bookings->markPaid($booking, $data));
    }
}
