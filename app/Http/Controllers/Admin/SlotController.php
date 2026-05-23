<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSlot;
use App\Models\Sport;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        return view('admin.slots.index', [
            'slots' => BookingSlot::with(['venue', 'sport'])->where('starts_at', '>=', now()->subDay())->orderBy('starts_at')->paginate(30),
            'venues' => Venue::orderBy('name')->get(),
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

        BookingSlot::create([
            'venue_id' => $data['venue_id'],
            'sport_id' => $data['sport_id'],
            'starts_at' => Carbon::parse($data['date'].' '.$data['start_time']),
            'ends_at' => Carbon::parse($data['date'].' '.$data['end_time']),
            'price' => $data['price'],
            'status' => 'available',
        ]);

        return back()->with('status', 'Slot created.');
    }
}
