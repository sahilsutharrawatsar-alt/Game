<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $request->user()->vendorProfile;

        return view('vendor.offers.index', [
            'offers' => Offer::with('coupon')->where('vendor_id', $vendor->id)->latest()->paginate(20),
            'coupons' => Coupon::where('vendor_id', $vendor->id)->orWhereNull('vendor_id')->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:160'],
            'subtitle' => ['nullable', 'max:255'],
            'badge' => ['nullable', 'max:80'],
            'image' => ['nullable', 'url'],
            'coupon_id' => ['nullable', 'exists:coupons,id'],
            'ends_at' => ['nullable', 'date'],
        ]);

        Offer::create($data + ['vendor_id' => $request->user()->vendorProfile->id, 'is_active' => true]);

        return back()->with('status', 'Offer is live for users.');
    }
}
