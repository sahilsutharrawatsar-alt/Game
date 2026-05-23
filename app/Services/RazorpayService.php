<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;

class RazorpayService
{
    public function createOrder(Booking $booking): array
    {
        return [
            'id' => 'order_'.Str::random(18),
            'amount' => (int) round($booking->total * 100),
            'currency' => 'INR',
            'receipt' => $booking->booking_number,
            'key' => config('services.razorpay.key'),
            'mode' => config('services.razorpay.key') ? 'live-ready' : 'demo',
        ];
    }

    public function verifySignature(array $payload): bool
    {
        $secret = config('services.razorpay.secret');

        if (! $secret) {
            return true;
        }

        $expected = hash_hmac(
            'sha256',
            $payload['razorpay_order_id'].'|'.$payload['razorpay_payment_id'],
            $secret
        );

        return hash_equals($expected, $payload['razorpay_signature'] ?? '');
    }
}
