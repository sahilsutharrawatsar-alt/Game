<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember')) && Auth::user()->isAdmin()) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        Auth::logout();

        return back()->withErrors(['email' => 'Admin credentials are invalid.'])->onlyInput('email');
    }
}
