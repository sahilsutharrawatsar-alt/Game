@extends('layouts.app', ['title' => 'Booking confirmed - Play Arena'])
@section('content')
<section class="mx-auto grid min-h-[60vh] max-w-2xl place-items-center px-4 py-10 text-center">
    <div class="rounded-lg border border-lime-300/30 bg-lime-300/10 p-8">
        <p class="text-sm font-bold uppercase tracking-[.25em] text-lime-300">Confirmed</p>
        <h1 class="mt-3 text-4xl font-black">{{ $booking->booking_number }}</h1>
        <p class="mt-4 text-slate-300">{{ $booking->venue->name }} is booked for {{ $booking->starts_at->format('d M Y, h:i A') }}.</p>
        <a href="{{ route('dashboard') }}" class="mt-6 inline-block rounded bg-lime-400 px-5 py-3 font-black text-slate-950">Go to dashboard</a>
    </div>
</section>
@endsection
