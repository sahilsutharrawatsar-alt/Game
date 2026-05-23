@extends('layouts.app', ['title' => 'Vendor Login - ArenaX'])
@section('content')
<section class="mx-auto grid min-h-[72vh] max-w-5xl gap-6 px-4 py-10 md:grid-cols-2">
    <div class="rounded-3xl border border-white/10 bg-white/[.05] p-8">
        <p class="text-sm font-bold uppercase tracking-[.25em] text-lime-300">Ground owner</p>
        <h1 class="mt-3 text-5xl font-black">Run your venue like a premium business.</h1>
        <p class="mt-5 text-slate-300">Manage slots, pricing, offers, bookings, payouts, and reviews from one fast vendor studio.</p>
    </div>
    <form method="POST" action="{{ route('vendor.login.submit') }}" class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-2xl backdrop-blur">@csrf
        <h2 class="text-3xl font-black">Vendor login</h2>
        <div class="mt-6 grid gap-4">
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Vendor email" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none">
            <input name="password" type="password" placeholder="Password" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none">
            @error('email')<p class="text-sm text-red-300">{{ $message }}</p>@enderror
            <button class="rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 px-4 py-3 font-black text-slate-950">Open vendor studio</button>
            <a href="{{ route('vendor.register') }}" class="text-center text-sm text-cyan-300">Register your ground</a>
        </div>
    </form>
</section>
@endsection
