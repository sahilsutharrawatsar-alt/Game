<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Vendor;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::latest()->paginate(20),
            'vendors' => Vendor::where('status', 'approved')->orderBy('business_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'max:40', 'unique:coupons,code'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'description' => ['nullable', 'max:255'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:1'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'expires_at' => ['nullable', 'date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        Coupon::create($data + ['code' => strtoupper($data['code']), 'is_active' => true]);

        return back()->with('status', 'Coupon created.');
    }
}
