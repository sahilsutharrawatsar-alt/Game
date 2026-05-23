@props(['venue'])
@php($image = $venue->images->first()?->path ?? 'https://images.unsplash.com/photo-1556056504-5c7696c4c28d?auto=format&fit=crop&w=900&q=80')
@php($nextSlot = $venue->bookingSlots->where('status', 'available')->sortBy('starts_at')->first())
<article class="group overflow-hidden rounded-2xl border border-white/10 bg-white/[.055] shadow-2xl shadow-black/20 backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-cyan-300/40 hover:shadow-cyan-500/10">
    <a href="{{ route('venues.show', $venue) }}">
        <div class="relative h-56 overflow-hidden">
            <img src="{{ $image }}" alt="{{ $venue->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
            <div class="absolute left-4 top-4 rounded-full border border-white/15 bg-black/45 px-3 py-1 text-xs font-bold backdrop-blur">★ {{ number_format($venue->reviews_avg_rating ?? 4.8, 1) }}</div>
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('venues.show', $venue) }}" class="text-lg font-black hover:text-lime-300">{{ $venue->name }}</a>
                <p class="mt-1 text-sm text-slate-400">{{ $venue->city }} · from ₹{{ number_format($venue->base_price) }}/hr</p>
            </div>
            <div class="rounded-lg bg-lime-400/15 px-2 py-1 text-xs font-bold text-lime-200">{{ $nextSlot ? $nextSlot->starts_at->format('h:i A') : 'Live' }}</div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($venue->sports->take(3) as $sport)<span class="rounded border border-white/10 px-2 py-1 text-xs text-slate-300">{{ $sport->name }}</span>@endforeach
        </div>
    </div>
</article>
