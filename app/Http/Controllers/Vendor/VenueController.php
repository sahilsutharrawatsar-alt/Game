<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $venues = $request->user()->vendorProfile->venues()->with(['sports', 'images'])->latest()->paginate(12);

        return view('vendor.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('vendor.venues.form', [
            'venue' => new Venue(),
            'sports' => Sport::orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $venue = Venue::create($this->validated($request) + [
            'vendor_id' => $request->user()->vendorProfile->id,
            'slug' => Str::slug($request->name).'-'.Str::random(4),
            'status' => 'draft',
        ]);

        $this->syncRelations($request, $venue);

        return redirect()->route('vendor.venues.index')->with('status', 'Ground created as draft. Publish it when ready.');
    }

    public function edit(Request $request, Venue $venue)
    {
        $this->authorizeVenue($request, $venue);
        $venue->load(['sports', 'amenities', 'images']);

        return view('vendor.venues.form', [
            'venue' => $venue,
            'sports' => Sport::orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Venue $venue)
    {
        $this->authorizeVenue($request, $venue);
        $venue->update($this->validated($request));
        $this->syncRelations($request, $venue);

        return back()->with('status', 'Ground updated live on the marketplace.');
    }

    public function destroy(Request $request, Venue $venue)
    {
        $this->authorizeVenue($request, $venue);
        $venue->delete();

        return back()->with('status', 'Ground removed.');
    }

    private function authorizeVenue(Request $request, Venue $venue): void
    {
        abort_unless($venue->vendor_id === $request->user()->vendorProfile->id, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
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
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);
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
                    'media_type' => str_contains($url, 'youtube') || str_contains($url, 'vimeo') ? 'video' : 'image',
                    'video_url' => str_contains($url, 'youtube') || str_contains($url, 'vimeo') ? $url : null,
                    'alt_text' => $venue->name,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
