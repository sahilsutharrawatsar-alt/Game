<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BookingSlot;
use App\Models\Sport;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $request->user()->vendorProfile;
        $venueIds = $vendor->venues()->pluck('id');

        return view('vendor.slots.index', [
            'slots' => BookingSlot::with(['venue', 'sport'])->whereIn('venue_id', $venueIds)->where('starts_at', '>=', now()->subDay())->orderBy('starts_at')->paginate(30),
            'venues' => $vendor->venues()->orderBy('name')->get(),
            'sports' => Sport::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'sport_id' => ['required', 'exists:sports,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        abort_unless(Venue::where('id', $data['venue_id'])->where('vendor_id', $request->user()->vendorProfile->id)->exists(), 403);

        BookingSlot::updateOrCreate([
            'venue_id' => $data['venue_id'],
            'sport_id' => $data['sport_id'],
            'starts_at' => Carbon::parse($data['date'].' '.$data['start_time']),
        ], [
            'ends_at' => Carbon::parse($data['date'].' '.$data['end_time']),
            'price' => $data['price'],
            'status' => 'available',
        ]);

        return back()->with('status', 'Slot is live on the website.');
    }

    public function update(Request $request, BookingSlot $slot)
    {
        abort_unless($slot->venue->vendor_id === $request->user()->vendorProfile->id, 403);
        $data = $request->validate(['status' => ['required', 'in:available,held,booked,paused']]);
        $slot->update($data);

        return back()->with('status', 'Slot availability updated live.');
    }
}
