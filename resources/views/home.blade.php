@extends('layouts.app', ['title' => 'ArenaX - Premium Sports Venue Booking'])
@section('content')
@php($heroImage = $banners->first()?->image ?? 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1800&q=90')
<section class="relative min-h-[88vh] overflow-hidden">
    <img src="{{ $heroImage }}" alt="Premium sports venue" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(3,7,18,.94),rgba(3,7,18,.62),rgba(3,7,18,.22)),linear-gradient(0deg,rgba(3,7,18,1),transparent_38%)]"></div>
    <div class="relative mx-auto flex min-h-[88vh] max-w-7xl flex-col justify-center px-4 py-16">
        <div class="max-w-3xl">
            <p class="inline-flex rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-xs font-black uppercase tracking-[.25em] text-cyan-200">Mohali · Chandigarh · Zirakpur · Panchkula</p>
            <h1 class="mt-6 text-5xl font-black leading-[.95] md:text-8xl">Book elite sports arenas in seconds.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">ArenaX brings verified turfs, courts, pools, live slots, member offers, instant confirmations, and Razorpay-ready checkout into one premium booking experience.</p>
            <form action="{{ route('venues.index') }}" class="mt-8 grid gap-3 rounded-2xl border border-white/15 bg-white/[.08] p-3 shadow-2xl shadow-cyan-950/40 backdrop-blur-xl md:grid-cols-[1fr_190px_160px]">
                <input name="q" placeholder="Search venue, sector, or city" class="rounded-xl border border-white/10 bg-black/40 px-4 py-4 text-white outline-none transition focus:border-cyan-300">
                <select name="sport" class="rounded-xl border border-white/10 bg-black/40 px-4 py-4 text-white outline-none transition focus:border-cyan-300">
                    <option value="">All sports</option>
                    @foreach($sports as $sport)<option value="{{ $sport->slug }}">{{ $sport->name }}</option>@endforeach
                </select>
                <button class="rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 px-6 py-4 font-black text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.02]">Find slots</button>
            </form>
            <div class="mt-8 grid max-w-2xl grid-cols-3 gap-3">
                @foreach([['25K+','games booked'],['4.8','avg rating'],['9','sports live']] as [$value,$label])
                    <div class="rounded-2xl border border-white/10 bg-black/35 p-4 backdrop-blur"><b class="text-2xl text-lime-300">{{ $value }}</b><p class="mt-1 text-xs uppercase tracking-wide text-slate-400">{{ $label }}</p></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <div id="live-offers" class="grid gap-4 md:grid-cols-3">
        @foreach($offers as $offer)
            <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-white/[.10] to-white/[.03] p-6 shadow-2xl shadow-black/20">
                <span class="rounded-full bg-lime-300 px-3 py-1 text-xs font-black text-slate-950">{{ $offer->badge ?? 'Limited' }}</span>
                <h2 class="mt-5 text-2xl font-black">{{ $offer->title }}</h2>
                <p class="mt-2 text-sm text-slate-300">{{ $offer->subtitle }}</p>
                @if($offer->coupon)<p class="mt-4 font-mono text-lime-200">{{ $offer->coupon->code }}</p>@endif
            </div>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="flex items-end justify-between gap-4"><div><p class="text-sm font-bold uppercase tracking-[.25em] text-violet-300">Play your way</p><h2 class="mt-2 text-3xl font-black">Popular Sports</h2></div><a href="{{ route('venues.index') }}" class="hidden text-sm font-bold text-cyan-300 md:block">Explore all venues</a></div>
    <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-5">
        @foreach($sports as $sport)
            <a href="{{ route('venues.index', ['sport' => $sport->slug]) }}" class="group rounded-2xl border border-white/10 bg-white/[.045] p-5 transition hover:-translate-y-1 hover:border-lime-300/40 hover:bg-white/[.08]">
                <div class="text-2xl font-black text-cyan-200">{{ $sport->icon }}</div>
                <div class="mt-4 font-bold">{{ $sport->name }}</div>
                <p class="mt-2 text-xs text-slate-500">Live slots today</p>
            </a>
        @endforeach
    </div>
</section>

@foreach([['Featured Venues',$featuredVenues,'Handpicked premium grounds'],['Nearby Venues',$nearbyVenues,'Fast access across tri-city'],['Trending Grounds',$trendingVenues,'Most loved this week']] as [$heading,$venues,$sub])
<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="flex items-center justify-between"><div><p class="text-sm font-bold uppercase tracking-[.25em] text-lime-300">{{ $sub }}</p><h2 class="mt-2 text-3xl font-black">{{ $heading }}</h2></div><a href="{{ route('venues.index') }}" class="text-sm font-bold text-cyan-300">View all</a></div>
    <div class="mt-6 grid gap-5 md:grid-cols-3" @if($heading === 'Trending Grounds') id="live-venues" @endif>@foreach($venues as $venue)<x-venue-card :venue="$venue" />@endforeach</div>
</section>
@endforeach

<section class="mx-auto max-w-7xl px-4 py-12">
    <div class="grid gap-5 lg:grid-cols-[.8fr_1.2fr]">
        <div class="rounded-3xl border border-white/10 bg-white/[.05] p-8">
            <p class="text-sm font-bold uppercase tracking-[.25em] text-cyan-300">ArenaX App</p>
            <h2 class="mt-3 text-4xl font-black">Your game night command center.</h2>
            <p class="mt-4 text-slate-300">Bookings, offers, invoices, reminders, and favorites designed for players who want fewer calls and more play.</p>
            <div class="mt-6 flex gap-3"><span class="rounded-xl bg-white px-4 py-3 font-black text-slate-950">App Store</span><span class="rounded-xl border border-white/15 px-4 py-3 font-black">Google Play</span></div>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            @foreach(['Lightning-fast confirmation','Verified sports facilities','Invoices, offers, favorites'] as $copy)
                <div class="rounded-3xl border border-white/10 bg-black/30 p-6"><div class="size-10 rounded-xl bg-gradient-to-br from-lime-300 to-cyan-300"></div><h3 class="mt-5 font-black">{{ $copy }}</h3><p class="mt-2 text-sm text-slate-400">Built for high-frequency players, academies, and corporate sports teams.</p></div>
            @endforeach
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach(['It feels like booking a premium cinema seat, but for turf.','The dashboard makes repeat bookings ridiculously easy.','Invoices, offers, and live slots finally in one clean product.'] as $quote)
            <div class="rounded-2xl border border-white/10 bg-white/[.045] p-6 text-slate-300">“{{ $quote }}”<div class="mt-5 font-bold text-white">ArenaX member</div></div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts')
<script>
    async function refreshArenaXLive() {
        try {
            const response = await fetch('{{ route('live.home') }}', {headers: {'Accept': 'application/json'}});
            const data = await response.json();
            const offers = document.getElementById('live-offers');
            const venues = document.getElementById('live-venues');
            if (offers && data.offers?.length) {
                offers.innerHTML = data.offers.slice(0, 3).map((offer) => `
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-white/[.10] to-white/[.03] p-6 shadow-2xl shadow-black/20">
                        <span class="rounded-full bg-lime-300 px-3 py-1 text-xs font-black text-slate-950">${offer.badge || 'Live'}</span>
                        <h2 class="mt-5 text-2xl font-black">${offer.title}</h2>
                        <p class="mt-2 text-sm text-slate-300">${offer.subtitle || ''}</p>
                        <p class="mt-4 font-mono text-lime-200">${offer.code || ''}</p>
                    </div>
                `).join('');
            }
            if (venues && data.venues?.length) {
                venues.innerHTML = data.venues.slice(0, 6).map((venue) => `
                    <a href="${venue.url}" class="group overflow-hidden rounded-2xl border border-white/10 bg-white/[.055] shadow-2xl shadow-black/20 backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-cyan-300/40">
                        <div class="relative h-56 overflow-hidden"><img src="${venue.image || ''}" alt="${venue.name}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div></div>
                        <div class="p-5"><div class="flex justify-between gap-3"><div><b>${venue.name}</b><p class="mt-1 text-sm text-slate-400">${venue.city} · from ₹${Number(venue.price).toLocaleString()}/hr</p></div><span class="rounded-lg bg-lime-400/15 px-2 py-1 text-xs font-bold text-lime-200">${venue.next_slot || 'Live'}</span></div></div>
                    </a>
                `).join('');
            }
        } catch (error) {}
    }
    setInterval(refreshArenaXLive, 20000);
</script>
@endpush
