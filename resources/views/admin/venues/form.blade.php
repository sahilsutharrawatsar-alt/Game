@extends('layouts.admin', ['title' => $venue->exists ? 'Edit Venue' : 'Add Venue'])
@section('content')
<h1 class="text-3xl font-black">{{ $venue->exists ? 'Edit' : 'Add' }} venue</h1>
<form method="POST" action="{{ $venue->exists ? route('admin.venues.update', $venue) : route('admin.venues.store') }}" class="mt-6 grid gap-5">@csrf @if($venue->exists)@method('PUT')@endif
    <div class="grid gap-4 md:grid-cols-2">
        @foreach(['name'=>'Name','city'=>'City','state'=>'State','address'=>'Address','latitude'=>'Latitude','longitude'=>'Longitude','base_price'=>'Base price','opening_time'=>'Opening time','closing_time'=>'Closing time','meta_title'=>'SEO title','meta_description'=>'SEO description'] as $field=>$label)
            <label class="grid gap-2 text-sm text-slate-300">{{ $label }}<input name="{{ $field }}" value="{{ old($field, $venue->$field ?: ($field==='state'?'Punjab':'')) }}" class="rounded bg-black/30 px-3 py-3 text-white ring-1 ring-white/10"></label>
        @endforeach
        <label class="grid gap-2 text-sm text-slate-300">Vendor<select name="vendor_id" class="rounded bg-black/30 px-3 py-3 text-white ring-1 ring-white/10"><option value="">Platform owned</option>@foreach($vendors as $vendor)<option value="{{ $vendor->id }}" @selected(old('vendor_id',$venue->vendor_id)==$vendor->id)>{{ $vendor->business_name }}</option>@endforeach</select></label>
        <label class="grid gap-2 text-sm text-slate-300">Status<select name="status" class="rounded bg-black/30 px-3 py-3 text-white ring-1 ring-white/10">@foreach(['active','draft','paused'] as $status)<option @selected(old('status',$venue->status)===$status)>{{ $status }}</option>@endforeach</select></label>
        <label class="flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$venue->is_featured))> Featured</label>
    </div>
    <label class="grid gap-2 text-sm text-slate-300">Description<textarea name="description" rows="5" class="rounded bg-black/30 px-3 py-3 text-white ring-1 ring-white/10">{{ old('description', $venue->description) }}</textarea></label>
    <div class="rounded-lg border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Sports pricing</h2><div class="mt-3 grid gap-3 md:grid-cols-4">@foreach($sports as $sport)<label class="grid gap-1 text-sm">{{ $sport->name }}<input name="sports[{{ $sport->id }}]" value="{{ old('sports.'.$sport->id, optional($venue->sports->firstWhere('id',$sport->id)?->pivot)->price_per_hour) }}" placeholder="₹/hour" class="rounded bg-black/30 px-3 py-2 ring-1 ring-white/10"></label>@endforeach</div></div>
    <div class="rounded-lg border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Amenities</h2><div class="mt-3 flex flex-wrap gap-3">@foreach($amenities as $amenity)<label class="text-sm"><input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked($venue->amenities->contains($amenity))> {{ $amenity->name }}</label>@endforeach</div></div>
    <div class="rounded-lg border border-white/10 bg-white/[.03] p-4"><h2 class="font-bold">Image URLs</h2>@for($i=0;$i<5;$i++)<input name="image_urls[]" value="{{ old('image_urls.'.$i, $venue->images[$i]->path ?? '') }}" placeholder="https://..." class="mt-3 w-full rounded bg-black/30 px-3 py-2 ring-1 ring-white/10">@endfor</div>
    @if($errors->any())<div class="rounded border border-red-300/30 bg-red-500/10 p-3 text-sm text-red-200">{{ $errors->first() }}</div>@endif
    <button class="w-fit rounded bg-lime-400 px-5 py-3 font-black text-slate-950">Save venue</button>
</form>
@endsection
