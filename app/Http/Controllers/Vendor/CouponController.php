<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        return view('vendor.coupons.index', [
            'coupons' => Coupon::where('vendor_id', $request->user()->vendorProfile->id)->latest()->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'max:40', 'unique:coupons,code'],
            'description' => ['nullable', 'max:255'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:1'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'expires_at' => ['nullable', 'date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        Coupon::create($data + [
            'vendor_id' => $request->user()->vendorProfile->id,
            'code' => strtoupper($data['code']),
            'is_active' => true,
        ]);

        return back()->with('status', 'Vendor coupon created.');
    }
}
