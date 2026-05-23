@extends('layouts.admin', ['title' => 'Notifications'])
@section('content')
<h1 class="text-3xl font-black">Notifications</h1>
<form method="POST" action="{{ route('admin.notifications.store') }}" class="mt-6 grid gap-3 rounded-2xl border border-white/10 bg-white/[.04] p-4 md:grid-cols-3">@csrf
    <select name="user_id" class="rounded bg-black/30 px-3 py-2"><option value="">All users</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select>
    <input name="title" placeholder="Title" class="rounded bg-black/30 px-3 py-2">
    <input name="type" value="system" class="rounded bg-black/30 px-3 py-2">
    <textarea name="message" placeholder="Message" class="rounded bg-black/30 px-3 py-2 md:col-span-3"></textarea>
    <button class="rounded bg-lime-400 px-4 py-2 font-bold text-slate-950 md:col-span-3">Send notification</button>
</form>
<div class="mt-6 grid gap-3">@foreach($notifications as $note)<div class="rounded-2xl border border-white/10 bg-white/[.04] p-4"><b>{{ $note->title }}</b><p class="text-sm text-slate-400">{{ $note->message }}</p><p class="mt-2 text-xs text-slate-500">{{ $note->user->name ?? 'Broadcast' }} · {{ $note->type }}</p></div>@endforeach</div>
@endsection
