@extends('layouts.app', ['title' => 'Admin Login - ArenaX'])
@section('content')
<section class="mx-auto grid min-h-[72vh] max-w-md place-items-center px-4 py-10">
    <form method="POST" action="{{ route('admin.login.submit') }}" class="w-full rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-2xl backdrop-blur">@csrf
        <p class="text-sm font-bold uppercase tracking-[.25em] text-cyan-300">Super admin</p>
        <h1 class="mt-3 text-3xl font-black">Control ArenaX</h1>
        <div class="mt-6 grid gap-4">
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Admin email" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none">
            <input name="password" type="password" placeholder="Password" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none">
            @error('email')<p class="text-sm text-red-300">{{ $message }}</p>@enderror
            <button class="rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 px-4 py-3 font-black text-slate-950">Enter admin</button>
        </div>
    </form>
</section>
@endsection
