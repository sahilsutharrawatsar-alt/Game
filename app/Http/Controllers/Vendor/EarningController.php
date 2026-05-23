<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Transaction;
use Illuminate\Http\Request;

class EarningController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $request->user()->vendorProfile;

        return view('vendor.earnings.index', [
            'earnings' => Earning::with('booking.venue')->where('vendor_id', $vendor->id)->latest()->paginate(20),
            'transactions' => Transaction::where('vendor_id', $vendor->id)->latest()->take(20)->get(),
            'total' => Earning::where('vendor_id', $vendor->id)->sum('net_amount'),
        ]);
    }
}
