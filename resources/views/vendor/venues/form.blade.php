@extends('layouts.vendor', ['title' => $venue->exists ? 'Edit Ground' : 'Add Ground'])
@section('content')
<h1 class="text-3xl font-black">{{ $venue->exists ? 'Edit' : 'Add' }} ground</h1>
<form method="POST" action="{{ $venue->exists ? route('vendor.venues.update',$venue) : route('vendor.venues.store') }}" class="mt-6 grid gap-5">@csrf @if($venue->exists)@method('PUT')@endif
    <div class="grid gap-4 md:grid-cols-2">
        @foreach(['name'=>'Name','city'=>'City','state'=>'State','address'=>'Address','latitude'=>'Latitude','longitude'=>'Longitude','base_price'=>'Base price','opening_time'=>'Opening time','closing_time'=>'Closing time','meta_title'=>'SEO title','meta_description'=>'SEO description'] as $field=>$label)
            <label class="grid gap-2 text-sm text-slate-300">{{ $label }}<input name="{{ $field }}" value="{{ old($field, $venue->$field ?: ($field==='state'?'Punjab':'')) }}" class="rounded-xl bg-black/30 px-3 py-3 text-white ring-1 ring-white/10"></label>
        @endforeach
        <label class="grid gap-2 text-sm text-slate-300">Status<select name="status" class="rounded-xl bg-black/30 px-3 py-3 text-white ring-1 ring-white/10">@foreach(['active','draft','paused'] as $status)<option @selected(old('status',$venue->status ?: 'draft')===$status)>{{ $status }}</option>@endforeach</select></label>
    </div>
    <label class="grid gap-2 text-sm text-slate-300">Description<textarea name="description" rows="5" class="rounded-xl bg-black/30 px-3 py-3 text-white ring-1 ring-white/10">{{ old('description', $venue->description) }}</textarea></label>
    <div class="rounded-3xl border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Sports pricing</h2><div class="mt-3 grid gap-3 md:grid-cols-4">@foreach($sports as $sport)<label class="grid gap-1 text-sm">{{ $sport->name }}<input name="sports[{{ $sport->id }}]" value="{{ old('sports.'.$sport->id, optional($venue->sports->firstWhere('id',$sport->id)?->pivot)->price_per_hour) }}" placeholder="₹/hour" class="rounded-xl bg-black/30 px-3 py-2 ring-1 ring-white/10"></label>@endforeach</div></div>
    <div class="rounded-3xl border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Amenities</h2><div class="mt-3 flex flex-wrap gap-3">@foreach($amenities as $amenity)<label class="text-sm"><input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked($venue->amenities->contains($amenity))> {{ $amenity->name }}</label>@endforeach</div></div>
    <div class="rounded-3xl border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Gallery image/video URLs</h2>@for($i=0;$i<6;$i++)<input name="image_urls[]" value="{{ old('image_urls.'.$i, $venue->images[$i]->path ?? '') }}" placeholder="https://..." class="mt-3 w-full rounded-xl bg-black/30 px-3 py-2 ring-1 ring-white/10">@endfor</div>
    @if($errors->any())<div class="rounded-xl border border-red-300/30 bg-red-500/10 p-3 text-sm text-red-200">{{ $errors->first() }}</div>@endif
    <button class="w-fit rounded-xl bg-lime-300 px-5 py-3 font-black text-slate-950">Save ground</button>
</form>
@endsection
