@extends('layouts.app', ['title' => $venue->name.' - Play Arena'])
@section('content')
@php($primary = $venue->images->first()?->path ?? 'https://images.unsplash.com/photo-1556056504-5c7696c4c28d?auto=format&fit=crop&w=1300&q=85')
<section class="mx-auto max-w-7xl px-4 py-8">
    <div class="grid gap-4 md:grid-cols-[1.2fr_.8fr]">
        <img src="{{ $primary }}" alt="{{ $venue->name }}" class="h-[420px] w-full rounded-lg object-cover">
        <div class="grid grid-cols-2 gap-4">@foreach($venue->images->skip(1)->take(4) as $image)<img src="{{ $image->path }}" alt="{{ $image->alt_text }}" class="h-[202px] w-full rounded-lg object-cover">@endforeach</div>
    </div>
    <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_380px]">
        <article>
            <div class="flex items-start justify-between gap-4">
                <div><h1 class="text-4xl font-black">{{ $venue->name }}</h1><p class="mt-2 text-slate-400">{{ $venue->address }}, {{ $venue->city }}</p></div>
                @auth<form method="POST" action="{{ route('favorites.toggle', $venue) }}">@csrf<button class="rounded border border-white/15 px-4 py-2 text-sm">Save</button></form>@endauth
            </div>
            <p class="mt-6 leading-7 text-slate-300">{{ $venue->description }}</p>
            <h2 class="mt-8 text-xl font-black">Sports & pricing</h2>
            <div class="mt-3 grid gap-3 md:grid-cols-2">@foreach($venue->sports as $sport)<div class="rounded border border-white/10 bg-white/[.04] p-4"><b>{{ $sport->icon }} {{ $sport->name }}</b><p class="text-sm text-slate-400">₹{{ number_format($sport->pivot->price_per_hour) }}/hour</p></div>@endforeach</div>
            <h2 class="mt-8 text-xl font-black">Amenities</h2>
            <div class="mt-3 flex flex-wrap gap-2">@foreach($venue->amenities as $amenity)<span class="rounded border border-lime-300/20 bg-lime-300/10 px-3 py-2 text-sm">{{ $amenity->name }}</span>@endforeach</div>
            <h2 class="mt-8 text-xl font-black">Location</h2>
            <div class="mt-3 overflow-hidden rounded-lg border border-white/10 bg-white/[.04]">
                <iframe title="Map" class="h-72 w-full" loading="lazy" src="https://www.google.com/maps?q={{ $venue->latitude }},{{ $venue->longitude }}&output=embed"></iframe>
            </div>
            <h2 class="mt-8 text-xl font-black">Reviews</h2>
            <div class="mt-3 grid gap-3">@foreach($venue->reviews as $review)<div class="rounded border border-white/10 bg-white/[.04] p-4"><b>★ {{ $review->rating }} · {{ $review->user->name }}</b><p class="mt-2 text-sm text-slate-300">{{ $review->comment }}</p></div>@endforeach</div>
            @auth<form method="POST" action="{{ route('reviews.store', $venue) }}" class="mt-4 grid gap-3 rounded border border-white/10 bg-white/[.04] p-4">@csrf<input name="rating" type="number" min="1" max="5" placeholder="Rating 1-5" class="rounded bg-black/30 px-3 py-2"><textarea name="comment" placeholder="Share your experience" class="rounded bg-black/30 px-3 py-2"></textarea><button class="rounded bg-lime-400 px-4 py-2 font-bold text-slate-950">Submit review</button></form>@endauth
        </article>
        <aside class="h-fit rounded-lg border border-white/10 bg-white/[.05] p-5">
            <h2 class="text-xl font-black">Book a slot</h2>
            <form method="POST" action="{{ route('bookings.store') }}" class="mt-4 grid gap-3">@csrf
                <select name="booking_slot_id" required class="rounded bg-black/40 px-3 py-3 outline-none ring-1 ring-white/10">
                    <option value="">Select live slot</option>
                    @foreach($venue->bookingSlots->where('status','available')->groupBy(fn($s)=>$s->starts_at->format('D, d M')) as $day => $slots)
                        <optgroup label="{{ $day }}">@foreach($slots as $slot)<option value="{{ $slot->id }}">{{ $slot->starts_at->format('h:i A') }} - {{ $slot->ends_at->format('h:i A') }} · {{ $slot->sport->name ?? 'Sport' }} · ₹{{ number_format($slot->price) }}</option>@endforeach</optgroup>
                    @endforeach
                </select>
                <input name="coupon_code" placeholder="Promo code" class="rounded bg-black/40 px-3 py-3 outline-none ring-1 ring-white/10">
                <textarea name="notes" placeholder="Notes for venue" class="rounded bg-black/40 px-3 py-3 outline-none ring-1 ring-white/10"></textarea>
                @auth<button class="rounded bg-lime-400 px-4 py-3 font-black text-slate-950">Continue to payment</button>@else<a href="{{ route('login') }}" class="rounded bg-lime-400 px-4 py-3 text-center font-black text-slate-950">Login to book</a>@endauth
            </form>
        </aside>
    </div>
</section>
@endsection
