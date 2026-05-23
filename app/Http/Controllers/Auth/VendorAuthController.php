<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.vendor-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role === 'vendor') {
            $request->session()->regenerate();

            if (! Auth::user()->vendorProfile?->isApproved()) {
                Auth::logout();

                return back()->withErrors(['email' => 'Your vendor account is awaiting approval or has been blocked.']);
            }

            return redirect()->route('vendor.dashboard');
        }

        Auth::logout();

        return back()->withErrors(['email' => 'Vendor credentials are invalid.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.vendor-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'business_name' => ['required', 'max:160'],
            'owner_name' => ['required', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'max:20'],
            'city' => ['required', 'max:100'],
            'address' => ['nullable', 'max:500'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['owner_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'vendor',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'business_name' => $data['business_name'],
            'owner_name' => $data['owner_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'city' => $data['city'],
            'address' => $data['address'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('vendor.login')->with('status', 'Vendor request submitted. Admin approval is required before login.');
    }
}
