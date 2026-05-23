@extends('layouts.app', ['title' => 'Register - Play Arena'])
@section('content')
<section class="mx-auto grid min-h-[70vh] max-w-md place-items-center px-4 py-10">
    <form method="POST" class="w-full rounded-lg border border-white/10 bg-white/[.05] p-6 shadow-2xl">@csrf
        <h1 class="text-3xl font-black">Create account</h1>
        <div class="mt-6 grid gap-4">
            <input name="name" value="{{ old('name') }}" placeholder="Full name" required class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" required class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <input name="phone" value="{{ old('phone') }}" placeholder="Phone" class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <input name="password" type="password" placeholder="Password" required class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <input name="password_confirmation" type="password" placeholder="Confirm password" required class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            @if($errors->any())<p class="text-sm text-red-300">{{ $errors->first() }}</p>@endif
            <button class="rounded bg-lime-400 px-4 py-3 font-black text-slate-950">Join Play Arena</button>
        </div>
    </form>
</section>
@endsection
