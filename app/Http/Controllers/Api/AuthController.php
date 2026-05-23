<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\OtpService;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'max:20'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create($data + [
            'password' => Hash::make($data['password']),
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        return response()->json(['user' => $user, 'token' => $user->api_token], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
        $user = User::where('email', $data['email'])->first();

        abort_unless($user && Hash::check($data['password'], $user->password), 422, 'Invalid credentials.');

        $user->forceFill(['api_token' => hash('sha256', Str::random(60))])->save();

        return response()->json(['user' => $user, 'token' => $user->api_token]);
    }

    public function sendOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:20']]);

        $otp->send($data['phone'], $request);

        return response()->json([
            'message' => app()->environment('production') ? 'OTP sent.' : 'Demo OTP generated. Use 123456.',
        ]);
    }

    public function verifyOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $otp->verify($data['phone'], $data['otp']);

        $user = User::firstOrCreate(['phone' => $data['phone']], [
            'name' => 'ArenaX Player',
            'email' => 'player+'.preg_replace('/\D+/', '', $data['phone']).'@arenax.local',
            'password' => Hash::make(Str::random(32)),
        ]);

        $user->forceFill(['api_token' => hash('sha256', Str::random(60))])->save();

        return response()->json(['user' => $user, 'token' => $user->api_token]);
    }
}
