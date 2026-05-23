@extends('layouts.vendor', ['title' => 'Vendor Dashboard'])
@section('content')
<h1 class="text-3xl font-black">{{ $vendor->business_name }}</h1>
<p class="mt-2 text-slate-400">Live operations, earnings, and venue performance.</p>
<div class="mt-6 grid gap-4 md:grid-cols-4">
    @foreach([['Net revenue','₹'.number_format($stats['revenue'])],['Bookings',$stats['bookings']],['Grounds',$stats['venues']],['Rating',$stats['rating'] ?: 'New']] as [$label,$value])
        <div class="rounded-3xl border border-white/10 bg-white/[.05] p-5"><p class="text-sm text-slate-400">{{ $label }}</p><b class="mt-2 block text-3xl text-lime-200">{{ $value }}</b></div>
    @endforeach
</div>
<div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
    <div class="rounded-3xl border border-white/10 bg-white/[.04] p-5">
        <h2 class="font-black">Recent bookings</h2>
        <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead class="text-slate-400"><tr><th class="py-2">Booking</th><th>User</th><th>Ground</th><th>Status</th><th>Total</th></tr></thead><tbody>@foreach($recentBookings as $booking)<tr class="border-t border-white/10"><td class="py-3">{{ $booking->booking_number }}</td><td>{{ $booking->user->name }}</td><td>{{ $booking->venue->name }}</td><td>{{ $booking->status }}</td><td>₹{{ number_format($booking->total) }}</td></tr>@endforeach</tbody></table></div>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/[.04] p-5">
        <h2 class="font-black">Top venues</h2>
        <div class="mt-4 grid gap-3">@foreach($topVenues as $venue)<div class="rounded-2xl bg-black/25 p-4"><b>{{ $venue->name }}</b><p class="text-sm text-slate-400">{{ $venue->bookings_count }} bookings · ★ {{ number_format($venue->reviews_avg_rating ?? 0, 1) }}</p></div>@endforeach</div>
    </div>
</div>
@endsection
