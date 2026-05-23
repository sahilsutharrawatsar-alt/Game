@extends('layouts.admin', ['title' => 'Manage Venues'])
@section('content')
<div class="flex items-center justify-between"><h1 class="text-3xl font-black">Venues</h1><a href="{{ route('admin.venues.create') }}" class="rounded bg-lime-400 px-4 py-2 font-bold text-slate-950">Add venue</a></div>
<div class="mt-6 grid gap-4 md:grid-cols-3">
    @foreach($venues as $venue)
        <div class="rounded-lg border border-white/10 bg-white/[.04] p-4"><b>{{ $venue->name }}</b><p class="mt-1 text-sm text-slate-400">{{ $venue->city }} · {{ $venue->status }}</p><div class="mt-4 flex gap-2"><a href="{{ route('admin.venues.edit', $venue) }}" class="rounded border border-white/15 px-3 py-2 text-sm">Edit</a><form method="POST" action="{{ route('admin.venues.destroy', $venue) }}">@csrf @method('DELETE')<button class="rounded border border-red-300/30 px-3 py-2 text-sm text-red-200">Delete</button></form></div></div>
    @endforeach
</div>
<div class="mt-6">{{ $venues->links() }}</div>
@endsection
