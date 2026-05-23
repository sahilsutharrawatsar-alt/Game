<?php

namespace App\Services;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\AppNotification;
use App\Models\User;
use App\Models\Earning;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BookingService
{
    public function __construct(private readonly CouponService $coupons)
    {
    }

    public function create(User $user, BookingSlot $slot, ?string $couponCode = null, ?string $notes = null): Booking
    {
        if ($slot->status !== 'available' || $slot->starts_at->isPast()) {
            throw new InvalidArgumentException('This slot is no longer available.');
        }

        return DB::transaction(function () use ($user, $slot, $couponCode, $notes) {
            $lockedSlot = BookingSlot::query()->lockForUpdate()->findOrFail($slot->id);

            if ($lockedSlot->status !== 'available') {
                throw new InvalidArgumentException('This slot has just been booked.');
            }

            $subtotal = (float) $lockedSlot->price;
            [$coupon, $discount] = $this->coupons->discount($couponCode, $subtotal);
            $tax = round(($subtotal - $discount) * 0.18, 2);
            $total = round($subtotal - $discount + $tax, 2);

            $booking = Booking::create([
                'booking_number' => 'PA-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => $user->id,
                'venue_id' => $lockedSlot->venue_id,
                'sport_id' => $lockedSlot->sport_id,
                'booking_slot_id' => $lockedSlot->id,
                'booking_date' => $lockedSlot->starts_at->toDateString(),
                'starts_at' => $lockedSlot->starts_at,
                'ends_at' => $lockedSlot->ends_at,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'coupon_id' => $coupon?->id,
                'notes' => $notes,
            ]);

            $lockedSlot->update(['status' => 'held']);

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $booking->load(['venue.images', 'sport', 'slot', 'payment']);
        });
    }

    public function markPaid(Booking $booking, array $paymentData = []): Booking
    {
        return DB::transaction(function () use ($booking, $paymentData) {
            $booking->payment()->updateOrCreate([], [
                'provider' => 'razorpay',
                'provider_order_id' => $paymentData['razorpay_order_id'] ?? $paymentData['provider_order_id'] ?? null,
                'provider_payment_id' => $paymentData['razorpay_payment_id'] ?? null,
                'provider_signature' => $paymentData['razorpay_signature'] ?? null,
                'amount' => $booking->total,
                'status' => 'captured',
                'payload' => $paymentData,
            ]);

            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            $booking->slot?->update(['status' => 'booked']);

            AppNotification::create([
                'user_id' => $booking->user_id,
                'title' => 'Booking confirmed',
                'message' => $booking->booking_number.' at '.$booking->venue->name.' is confirmed.',
                'type' => 'booking',
            ]);

            if ($booking->venue->vendor_id) {
                $platformFee = round((float) $booking->total * 0.12, 2);
                $earning = Earning::updateOrCreate([
                    'vendor_id' => $booking->venue->vendor_id,
                    'booking_id' => $booking->id,
                ], [
                    'gross_amount' => $booking->total,
                    'platform_fee' => $platformFee,
                    'net_amount' => round((float) $booking->total - $platformFee, 2),
                    'status' => 'pending',
                    'available_at' => now()->addDays(2),
                ]);

                Transaction::create([
                    'vendor_id' => $booking->venue->vendor_id,
                    'booking_id' => $booking->id,
                    'payment_id' => $booking->payment?->id,
                    'type' => 'booking_earning',
                    'amount' => $earning->net_amount,
                    'status' => 'posted',
                    'reference' => $booking->booking_number,
                ]);
            }

            Mail::to($booking->user)->queue(new BookingConfirmedMail($booking->fresh(['venue', 'sport', 'slot'])));

            return $booking->fresh(['venue.images', 'sport', 'slot', 'payment']);
        });
    }

    public function cancel(Booking $booking): Booking
    {
        abort_if($booking->starts_at->isPast(), 422, 'Past bookings cannot be cancelled.');

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        if ($booking->slot && $booking->slot->status !== 'available') {
            $booking->slot->update(['status' => 'available']);
        }

        AppNotification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking cancelled',
            'message' => $booking->booking_number.' has been cancelled and the slot is released.',
            'type' => 'booking',
        ]);

        return $booking->fresh(['venue', 'sport', 'slot']);
    }
}
