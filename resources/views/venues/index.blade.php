@extends('layouts.app', ['title' => 'Venues - Play Arena'])
@section('content')
<section class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-4xl font-black">Sports venues near you</h1>
    <form class="mt-6 grid gap-3 rounded-2xl border border-white/10 bg-white/[.055] p-4 backdrop-blur md:grid-cols-6">
        <input name="q" value="{{ request('q') }}" placeholder="Venue or locality" class="rounded-xl bg-black/30 px-3 py-3 outline-none ring-1 ring-white/10 md:col-span-2">
        <select name="city" class="rounded-xl bg-black/30 px-3 py-3 outline-none ring-1 ring-white/10"><option value="">All cities</option>@foreach($cities as $city)<option @selected(request('city')===$city)>{{ $city }}</option>@endforeach</select>
        <select name="sport" class="rounded-xl bg-black/30 px-3 py-3 outline-none ring-1 ring-white/10"><option value="">All sports</option>@foreach($sports as $sport)<option value="{{ $sport->slug }}" @selected(request('sport')===$sport->slug)>{{ $sport->name }}</option>@endforeach</select>
        <input name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹/hr" class="rounded-xl bg-black/30 px-3 py-3 outline-none ring-1 ring-white/10">
        <button class="rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 font-black text-slate-950">Search</button>
    </form>
    <div class="mt-8 grid gap-5 md:grid-cols-3">@forelse($venues as $venue)<x-venue-card :venue="$venue" />@empty<p class="text-slate-400">No venues match your filters yet.</p>@endforelse</div>
    <div class="mt-8">{{ $venues->links() }}</div>
</section>
@endsection
