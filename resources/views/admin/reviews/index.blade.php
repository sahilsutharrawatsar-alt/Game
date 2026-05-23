@extends('layouts.admin', ['title' => 'Reviews'])
@section('content')
<h1 class="text-3xl font-black">Reviews</h1>
<div class="mt-6 grid gap-3">@foreach($reviews as $review)<div class="rounded border border-white/10 bg-white/[.04] p-4"><b>★ {{ $review->rating }} · {{ $review->venue->name }}</b><p class="text-sm text-slate-300">{{ $review->comment }}</p><form method="POST" action="{{ route('admin.reviews.update',$review) }}" class="mt-3">@csrf @method('PUT')<select name="status" onchange="this.form.submit()" class="rounded bg-black/30 px-3 py-2">@foreach(['pending','approved','rejected'] as $status)<option @selected($review->status===$status)>{{ $status }}</option>@endforeach</select></form></div>@endforeach</div><div class="mt-5">{{ $reviews->links() }}</div>
@endsection
