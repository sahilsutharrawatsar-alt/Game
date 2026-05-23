@extends('layouts.admin', ['title' => 'Offers'])
@section('content')
<h1 class="text-3xl font-black">Offers</h1>
<form method="POST" action="{{ route('admin.offers.store') }}" class="mt-6 grid gap-3 rounded-2xl border border-white/10 bg-white/[.04] p-4 md:grid-cols-4">@csrf
    <input name="title" placeholder="Title" class="rounded bg-black/30 px-3 py-2">
    <input name="subtitle" placeholder="Subtitle" class="rounded bg-black/30 px-3 py-2">
    <input name="badge" placeholder="Badge" class="rounded bg-black/30 px-3 py-2">
    <select name="coupon_id" class="rounded bg-black/30 px-3 py-2"><option value="">No coupon</option>@foreach($coupons as $coupon)<option value="{{ $coupon->id }}">{{ $coupon->code }}</option>@endforeach</select>
    <select name="vendor_id" class="rounded bg-black/30 px-3 py-2"><option value="">Platform offer</option>@foreach($vendors as $vendor)<option value="{{ $vendor->id }}">{{ $vendor->business_name }}</option>@endforeach</select>
    <input name="image" placeholder="Image URL" class="rounded bg-black/30 px-3 py-2 md:col-span-3">
    <input name="ends_at" type="date" class="rounded bg-black/30 px-3 py-2">
    <button class="rounded bg-lime-400 px-4 py-2 font-bold text-slate-950 md:col-span-4">Create offer</button>
</form>
<div class="mt-6 grid gap-3 md:grid-cols-3">@foreach($offers as $offer)<div class="rounded-2xl border border-white/10 bg-white/[.04] p-5"><b>{{ $offer->title }}</b><p class="mt-2 text-sm text-slate-400">{{ $offer->subtitle }}</p><p class="mt-3 text-xs text-lime-200">{{ $offer->coupon->code ?? 'No coupon' }}</p></div>@endforeach</div>
@endsection
