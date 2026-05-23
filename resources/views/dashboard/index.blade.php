@extends('layouts.app', ['title' => 'Dashboard - ArenaX'])
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-2xl backdrop-blur">
            <p class="text-sm font-bold uppercase tracking-[.25em] text-cyan-300">Member dashboard</p>
            <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
                <div><h1 class="text-4xl font-black">Hey, {{ auth()->user()->name }}</h1><p class="mt-2 text-slate-400">Your bookings, favorites, offers, and alerts are ready.</p></div>
                <a href="{{ route('profile') }}" class="rounded-xl border border-white/15 px-4 py-3 text-sm font-bold">Edit profile</a>
            </div>
            <div class="mt-6 grid gap-3 md:grid-cols-4">
                @foreach([['Upcoming',$upcomingBookings->count()],['Total spend','₹'.number_format($totalSpend)],['Favorites',$favorites->count()],['Offers',$offers->count()]] as [$label,$value])
                    <div class="rounded-2xl border border-white/10 bg-black/25 p-4"><p class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</p><b class="mt-2 block text-2xl text-lime-200">{{ $value }}</b></div>
                @endforeach
            </div>
        </div>
        <aside class="rounded-3xl border border-white/10 bg-black/30 p-6">
            <h2 class="text-xl font-black">Notifications</h2>
            <div class="mt-4 grid gap-3">@forelse($notifications as $note)<div class="rounded-2xl bg-white/[.06] p-4"><b>{{ $note->title }}</b><p class="mt-1 text-sm text-slate-400">{{ $note->message }}</p></div>@empty<p class="text-sm text-slate-500">No notifications yet.</p>@endforelse</div>
        </aside>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
        <div>
            <h2 class="text-2xl font-black">Upcoming bookings</h2>
            <div class="mt-4 grid gap-4">
                @forelse($upcomingBookings as $booking)
                    <div class="grid gap-4 rounded-3xl border border-white/10 bg-white/[.045] p-4 md:grid-cols-[120px_1fr_auto]">
                        <img src="{{ $booking->venue->images->first()?->path }}" alt="{{ $booking->venue->name }}" class="h-28 w-full rounded-2xl object-cover">
                        <div><b class="text-lg">{{ $booking->venue->name }}</b><p class="mt-1 text-sm text-slate-400">{{ $booking->sport->name }} · {{ $booking->starts_at->format('d M, h:i A') }}</p><p class="mt-3 text-sm text-lime-200">{{ ucfirst($booking->status) }} · {{ ucfirst($booking->payment_status) }}</p></div>
                        <div class="grid content-center gap-2">
                            <a href="{{ route('bookings.invoice', $booking) }}" class="rounded-xl border border-white/15 px-4 py-2 text-center text-sm">Invoice</a>
                            @if(! in_array($booking->status, ['cancelled','completed']) && $booking->starts_at->isFuture())<form method="POST" action="{{ route('bookings.cancel', $booking) }}">@csrf<button class="w-full rounded-xl border border-red-300/30 px-4 py-2 text-sm text-red-200">Cancel</button></form>@endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-white/10 bg-white/[.04] p-8 text-slate-400">No upcoming bookings. Your next game is waiting.</div>
                @endforelse
            </div>

            <h2 class="mt-8 text-2xl font-black">Booking history</h2>
            <div class="mt-4 overflow-hidden rounded-3xl border border-white/10">
                @foreach($bookings as $booking)
                    <div class="grid gap-3 border-b border-white/10 bg-white/[.03] p-4 md:grid-cols-[1fr_auto_auto]">
                        <div><b>{{ $booking->venue->name }}</b><p class="text-sm text-slate-400">{{ $booking->sport->name }} · {{ $booking->starts_at->format('d M, h:i A') }}</p></div>
                        <div class="text-sm">₹{{ number_format($booking->total) }} · {{ ucfirst($booking->status) }}</div>
                        <a href="{{ route('bookings.invoice', $booking) }}" class="text-sm font-bold text-cyan-300">Invoice</a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>

        <aside class="grid h-fit gap-6">
            <div class="rounded-3xl border border-white/10 bg-white/[.045] p-6"><h2 class="text-xl font-black">Wallet & offers</h2><div class="mt-4 grid gap-3">@foreach($offers as $offer)<div class="rounded-2xl bg-gradient-to-br from-lime-300/15 to-cyan-300/10 p-4"><b>{{ $offer->title }}</b><p class="text-sm text-slate-400">{{ $offer->subtitle }}</p></div>@endforeach</div></div>
            <div class="rounded-3xl border border-white/10 bg-white/[.045] p-6"><h2 class="text-xl font-black">Favorites</h2><div class="mt-4 grid gap-3">@foreach($favorites as $venue)<a href="{{ route('venues.show',$venue) }}" class="rounded-2xl bg-black/25 p-4 font-bold hover:text-lime-300">{{ $venue->name }}<p class="text-sm font-normal text-slate-500">{{ $venue->city }}</p></a>@endforeach</div></div>
            <div class="rounded-3xl border border-white/10 bg-white/[.045] p-6"><h2 class="text-xl font-black">Recent activity</h2><div class="mt-4 grid gap-3">@foreach($activity as $item)<p class="text-sm text-slate-400">{{ $item->created_at->diffForHumans() }} · {{ $item->booking_number }}</p>@endforeach</div></div>
        </aside>
    </div>
</section>
@endsection
