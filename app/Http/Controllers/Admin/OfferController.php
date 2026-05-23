<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Vendor;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin.offers.index', [
            'offers' => Offer::with('coupon')->latest()->paginate(20),
            'coupons' => Coupon::orderBy('code')->get(),
            'vendors' => Vendor::where('status', 'approved')->orderBy('business_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:160'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'subtitle' => ['nullable', 'max:255'],
            'badge' => ['nullable', 'max:80'],
            'image' => ['nullable', 'url'],
            'coupon_id' => ['nullable', 'exists:coupons,id'],
            'ends_at' => ['nullable', 'date'],
        ]);

        Offer::create($data + ['is_active' => true]);

        return back()->with('status', 'Offer created.');
    }
}
