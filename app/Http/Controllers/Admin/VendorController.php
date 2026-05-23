<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.vendors.index', [
            'vendors' => Vendor::withCount(['venues', 'earnings'])->with('user')->latest()->paginate(20),
        ]);
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,blocked'],
            'rejection_reason' => ['nullable', 'max:500'],
        ]);

        $vendor->update([
            'status' => $data['status'],
            'rejection_reason' => $data['rejection_reason'] ?? null,
            'approved_at' => $data['status'] === 'approved' ? now() : $vendor->approved_at,
            'blocked_at' => $data['status'] === 'blocked' ? now() : null,
        ]);

        $vendor->user()->update(['role' => 'vendor']);

        return back()->with('status', 'Vendor status updated.');
    }
}
