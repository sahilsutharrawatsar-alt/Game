@extends('layouts.app', ['title' => 'Profile - Play Arena'])
@section('content')
<section class="mx-auto max-w-xl px-4 py-10">
    <form method="POST" action="{{ route('profile.update') }}" class="rounded-lg border border-white/10 bg-white/[.05] p-6">@csrf @method('PUT')
        <h1 class="text-3xl font-black">Profile</h1>
        <div class="mt-6 grid gap-4">
            <input name="name" value="{{ old('name', $user->name) }}" class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <input value="{{ $user->email }}" disabled class="rounded bg-black/30 px-4 py-3 text-slate-400 outline-none ring-1 ring-white/10">
            <input name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone" class="rounded bg-black/30 px-4 py-3 outline-none ring-1 ring-white/10">
            <button class="rounded bg-lime-400 px-4 py-3 font-black text-slate-950">Save profile</button>
        </div>
    </form>
</section>
@endsection
