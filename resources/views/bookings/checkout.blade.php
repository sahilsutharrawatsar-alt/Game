@extends('layouts.app', ['title' => 'Checkout - Play Arena'])
@section('content')
<section class="mx-auto grid max-w-3xl place-items-center px-4 py-10">
    <div class="w-full rounded-lg border border-white/10 bg-white/[.05] p-6">
        <h1 class="text-3xl font-black">Confirm payment</h1>
        <div class="mt-5 rounded border border-white/10 bg-black/20 p-4">
            <b>{{ $booking->venue->name }}</b>
            <p class="mt-1 text-sm text-slate-400">{{ $booking->sport->name }} · {{ $booking->starts_at->format('d M Y, h:i A') }} - {{ $booking->ends_at->format('h:i A') }}</p>
            <dl class="mt-4 grid gap-2 text-sm">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($booking->subtotal, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Discount</dt><dd>₹{{ number_format($booking->discount, 2) }}</dd></div>
                <div class="flex justify-between"><dt>GST</dt><dd>₹{{ number_format($booking->tax, 2) }}</dd></div>
                <div class="flex justify-between text-lg font-black"><dt>Total</dt><dd>₹{{ number_format($booking->total, 2) }}</dd></div>
            </dl>
        </div>
        <form method="POST" action="{{ route('bookings.confirm', $booking) }}" class="mt-5">@csrf
            <input type="hidden" name="razorpay_order_id" value="{{ $order['id'] }}">
            <input type="hidden" name="razorpay_payment_id" value="demo_payment_{{ $booking->id }}">
            <button class="w-full rounded bg-lime-400 px-4 py-3 font-black text-slate-950">Pay securely with Razorpay</button>
            <p class="mt-3 text-center text-xs text-slate-500">Demo mode completes payment locally until Razorpay keys are configured.</p>
        </form>
    </div>
</section>
@endsection
