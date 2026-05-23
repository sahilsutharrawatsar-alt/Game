@extends('layouts.app', ['title' => 'Login - ArenaX'])
@section('content')
<section class="mx-auto grid min-h-[78vh] max-w-6xl gap-8 px-4 py-10 lg:grid-cols-[1fr_460px]">
    <div class="relative hidden overflow-hidden rounded-3xl border border-white/10 lg:block">
        <img src="https://images.unsplash.com/photo-1551958219-acbc608c6377?auto=format&fit=crop&w=1200&q=90" alt="Sports venue" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/35 to-transparent"></div>
        <div class="absolute bottom-8 left-8 right-8">
            <p class="text-sm font-bold uppercase tracking-[.25em] text-lime-300">Member access</p>
            <h1 class="mt-3 text-5xl font-black">Book faster with your ArenaX profile.</h1>
        </div>
    </div>
    <div class="grid content-center gap-5">
        <form method="POST" action="{{ route('login') }}" class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-2xl backdrop-blur">@csrf
            <h2 class="text-3xl font-black">Email login</h2>
            <div class="mt-6 grid gap-4">
                <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" required class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none focus:border-cyan-300">
                <input name="password" type="password" placeholder="Password" required class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none focus:border-cyan-300">
                @error('email')<p class="text-sm text-red-300">{{ $message }}</p>@enderror
                <button class="rounded-xl bg-gradient-to-r from-lime-300 to-cyan-300 px-4 py-3 font-black text-slate-950">Login</button>
                <a href="{{ route('register') }}" class="text-center text-sm text-cyan-300">Create an account</a>
            </div>
        </form>
        <div class="grid gap-4 rounded-3xl border border-white/10 bg-white/[.04] p-6">
            <h2 class="text-2xl font-black">Mobile OTP</h2>
            <form method="POST" action="{{ route('login.otp.send') }}" class="grid gap-3">@csrf
                <input name="phone" value="{{ old('phone') }}" placeholder="Mobile number" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none focus:border-cyan-300">
                <button class="rounded-xl border border-cyan-300/30 px-4 py-3 font-bold text-cyan-100">Send OTP</button>
            </form>
            <form method="POST" action="{{ route('login.otp.verify') }}" class="grid gap-3">@csrf
                <input name="phone" value="{{ old('phone') }}" placeholder="Mobile number" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none focus:border-cyan-300">
                <input name="otp" placeholder="6-digit OTP" class="rounded-xl border border-white/10 bg-black/35 px-4 py-3 outline-none focus:border-cyan-300">
                <button class="rounded-xl bg-white px-4 py-3 font-black text-slate-950">Verify & continue</button>
                <p class="text-xs text-slate-500">Local demo OTP is 123456. Configure Twilio or Fast2SMS in production.</p>
            </form>
            <a href="{{ route('auth.google') }}" class="rounded-xl border border-white/15 px-4 py-3 text-center text-sm text-slate-300">Continue with Google</a>
        </div>
    </div>
</section>
@endsection
