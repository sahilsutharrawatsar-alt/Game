@extends('layouts.admin', ['title' => 'Users'])
@section('content')
<h1 class="text-3xl font-black">Users</h1>
<div class="mt-6 grid gap-3">@foreach($users as $user)<div class="grid gap-3 rounded border border-white/10 bg-white/[.04] p-4 md:grid-cols-[1fr_auto]"><div><b>{{ $user->name }}</b><p class="text-sm text-slate-400">{{ $user->email }} · {{ $user->phone }}</p></div><form method="POST" action="{{ route('admin.users.update',$user) }}">@csrf @method('PUT')<select name="role" onchange="this.form.submit()" class="rounded bg-black/30 px-3 py-2">@foreach(['user','admin'] as $role)<option @selected($user->role===$role)>{{ $role }}</option>@endforeach</select></form></div>@endforeach</div><div class="mt-5">{{ $users->links() }}</div>
@endsection
