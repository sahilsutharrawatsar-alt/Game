<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Sport;
use App\Models\Venue;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::with(['sports', 'images'])->latest()->paginate(12);

        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.form', [
            'venue' => new Venue(),
            'sports' => Sport::orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
            'vendors' => Vendor::where('status', 'approved')->orderBy('business_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $venue = Venue::create($this->validated($request) + ['slug' => Str::slug($request->name).'-'.Str::random(4)]);
        $this->syncRelations($request, $venue);

        return redirect()->route('admin.venues.index')->with('status', 'Venue created.');
    }

    public function edit(Venue $venue)
    {
        $venue->load(['sports', 'amenities', 'images']);

        return view('admin.venues.form', [
            'venue' => $venue,
            'sports' => Sport::orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
            'vendors' => Vendor::where('status', 'approved')->orderBy('business_name')->get(),
        ]);
    }

    public function update(Request $request, Venue $venue)
    {
        $venue->update($this->validated($request));
        $this->syncRelations($request, $venue);

        return back()->with('status', 'Venue updated.');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();

        return back()->with('status', 'Venue deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'status' => ['required', 'in:active,draft,paused'],
            'is_featured' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]) + ['is_featured' => $request->boolean('is_featured')];
    }

    private function syncRelations(Request $request, Venue $venue): void
    {
        $sportPrices = collect($request->input('sports', []))
            ->filter(fn ($price) => $price !== null && $price !== '')
            ->mapWithKeys(fn ($price, $sportId) => [$sportId => ['price_per_hour' => $price]])
            ->all();

        $venue->sports()->sync($sportPrices);
        $venue->amenities()->sync($request->input('amenities', []));

        foreach ($request->input('image_urls', []) as $index => $url) {
            if ($url) {
                $venue->images()->updateOrCreate(['path' => $url], [
                    'alt_text' => $venue->name,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
