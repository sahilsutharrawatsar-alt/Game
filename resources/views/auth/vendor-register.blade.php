@extends('layouts.app', ['title' => 'Vendor Registration - ArenaX'])
@section('content')
<section class="mx-auto max-w-3xl px-4 py-10">
    <form method="POST" action="{{ route('vendor.register.submit') }}" class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-2xl">@csrf
        <p class="text-sm font-bold uppercase tracking-[.25em] text-lime-300">Partner with ArenaX</p>
        <h1 class="mt-3 text-4xl font-black">Register your sports venue</h1>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <input name="business_name" placeholder="Business name" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="owner_name" placeholder="Owner name" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="email" type="email" placeholder="Email" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="phone" placeholder="Phone" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="city" placeholder="City" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="address" placeholder="Address" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="password" type="password" placeholder="Password" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
            <input name="password_confirmation" type="password" placeholder="Confirm password" class="rounded-xl bg-black/35 px-4 py-3 ring-1 ring-white/10">
        </div>
        @if($errors->any())<p class="mt-4 text-sm text-red-300">{{ $errors->first() }}</p>@endif
        <button class="mt-6 rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 px-5 py-3 font-black text-slate-950">Submit for approval</button>
    </form>
</section>
@endsection
