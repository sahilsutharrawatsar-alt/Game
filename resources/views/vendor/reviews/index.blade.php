@extends('layouts.vendor', ['title' => 'Venue Reviews'])
@section('content')
<h1 class="text-3xl font-black">Customer reviews</h1>
<div class="mt-6 grid gap-3">@foreach($reviews as $review)<div class="rounded-3xl border border-white/10 bg-white/[.04] p-4"><b>★ {{ $review->rating }} · {{ $review->venue->name }}</b><p class="text-sm text-slate-300">{{ $review->comment }}</p><p class="mt-2 text-xs text-slate-500">{{ $review->user->name }} · {{ $review->status }}</p></div>@endforeach</div><div class="mt-5">{{ $reviews->links() }}</div>
@endsection
