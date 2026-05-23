@extends('layouts.admin', ['title' => 'Admin Dashboard'])
@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
    <div><p class="text-sm font-bold uppercase tracking-[.25em] text-cyan-300">Super admin</p><h1 class="mt-2 text-3xl font-black">ArenaX command center</h1></div>
    <a href="{{ route('admin.vendors.index') }}" class="rounded-xl bg-lime-300 px-4 py-2 font-bold text-slate-950">{{ $stats['pending_vendors'] }} pending vendors</a>
</div>
<div class="mt-6 grid gap-4 md:grid-cols-6">
    @foreach([['Revenue','₹'.number_format($stats['revenue'])],['Bookings',$stats['bookings']],['Venues',$stats['venues']],['Users',$stats['users']],['Vendors',$stats['vendors']],['Pending',$stats['pending_vendors']]] as [$label,$value])
        <div class="rounded-3xl border border-white/10 bg-white/[.04] p-5"><p class="text-sm text-slate-400">{{ $label }}</p><b class="mt-2 block text-3xl text-lime-200">{{ $value }}</b></div>
    @endforeach
</div>
<div class="mt-8 grid gap-6 lg:grid-cols-[1fr_380px]">
    <div class="rounded-3xl border border-white/10 bg-white/[.04] p-5">
        <h2 class="font-black">Live booking analytics</h2>
        <div class="mt-4 flex h-52 items-end gap-2">
            @foreach($revenueByDay as $point)
                <div class="flex flex-1 flex-col items-center gap-2"><div class="w-full rounded-t bg-gradient-to-t from-lime-300 to-cyan-300" style="height: {{ max(8, min(190, $point->revenue / 80)) }}px"></div><span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($point->day)->format('d') }}</span></div>
            @endforeach
        </div>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/[.04] p-5">
        <h2 class="font-black">Vendor performance</h2>
        <div class="mt-4 grid gap-3">@foreach($vendorPerformance as $vendor)<div class="rounded-2xl bg-black/25 p-4"><b>{{ $vendor->business_name }}</b><p class="text-sm text-slate-400">{{ $vendor->venues_count }} venues · ₹{{ number_format($vendor->earnings_sum_net_amount ?? 0) }}</p></div>@endforeach</div>
    </div>
</div>
<div class="mt-8 rounded-3xl border border-white/10 bg-white/[.04] p-5">
    <h2 class="font-black">Recent bookings</h2>
    <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead class="text-slate-400"><tr><th class="py-2">Booking</th><th>User</th><th>Venue</th><th>Status</th><th>Total</th></tr></thead><tbody>@foreach($recentBookings as $booking)<tr class="border-t border-white/10"><td class="py-3">{{ $booking->booking_number }}</td><td>{{ $booking->user->name }}</td><td>{{ $booking->venue->name }}</td><td>{{ $booking->status }}</td><td>₹{{ number_format($booking->total) }}</td></tr>@endforeach</tbody></table></div>
</div>
@endsection
