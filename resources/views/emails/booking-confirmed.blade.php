<h1>Booking confirmed</h1>
<p>Hi {{ $booking->user->name }}, your booking {{ $booking->booking_number }} at {{ $booking->venue->name }} is confirmed.</p>
<p>{{ $booking->sport->name }} · {{ $booking->starts_at->format('d M Y, h:i A') }} - {{ $booking->ends_at->format('h:i A') }}</p>
