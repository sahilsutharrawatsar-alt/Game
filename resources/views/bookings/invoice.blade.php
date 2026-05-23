<!doctype html>
<html>
<head><meta charset="utf-8"><title>Invoice {{ $booking->booking_number }}</title></head>
<body style="font-family: Arial, sans-serif; color:#111827; padding:32px;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div><h1 style="margin:0;">ArenaX Invoice</h1><p>{{ $booking->booking_number }}</p></div>
        <div style="text-align:right;"><strong>{{ ucfirst($booking->payment_status) }}</strong><p>{{ $booking->created_at->format('d M Y') }}</p></div>
    </div>
    <hr>
    <p><strong>Billed to:</strong> {{ $booking->user->name }}<br>{{ $booking->user->email }}<br>{{ $booking->user->phone }}</p>
    <p><strong>Venue:</strong> {{ $booking->venue->name }}<br>{{ $booking->venue->address }}, {{ $booking->venue->city }}</p>
    <table width="100%" cellspacing="0" cellpadding="10" border="1" style="border-collapse:collapse;">
        <tr><th align="left">Sport</th><th align="left">Slot</th><th align="right">Amount</th></tr>
        <tr><td>{{ $booking->sport->name }}</td><td>{{ $booking->starts_at->format('d M Y, h:i A') }} - {{ $booking->ends_at->format('h:i A') }}</td><td align="right">₹{{ number_format($booking->subtotal, 2) }}</td></tr>
        <tr><td colspan="2" align="right">Discount</td><td align="right">₹{{ number_format($booking->discount, 2) }}</td></tr>
        <tr><td colspan="2" align="right">GST</td><td align="right">₹{{ number_format($booking->tax, 2) }}</td></tr>
        <tr><td colspan="2" align="right"><strong>Total</strong></td><td align="right"><strong>₹{{ number_format($booking->total, 2) }}</strong></td></tr>
    </table>
</body>
</html>
