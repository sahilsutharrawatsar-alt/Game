<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\OtpService;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard'));
        }

        return back()->withErrors(['email' => 'The credentials do not match our records.'])->onlyInput('email');
    }

    public function sendOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:20']]);

        $otp->send($data['phone'], $request);

        return back()->with('status', app()->environment('production')
            ? 'OTP sent to your mobile number.'
            : 'Demo OTP sent. Use 123456 to continue.');
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
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function redirectGoogle(Request $request)
    {
        abort_unless(config('services.google.client_id'), 501, 'Google login is not configured.');

        $request->session()->put('google_oauth_state', $state = Str::random(40));

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => route('auth.google.callback'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]));
    }

    public function callbackGoogle(Request $request)
    {
        abort_unless(hash_equals((string) $request->session()->pull('google_oauth_state'), (string) $request->state), 403);

        $token = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => route('auth.google.callback'),
            'grant_type' => 'authorization_code',
            'code' => $request->code,
        ])->throw()->json();

        $profile = Http::withToken($token['access_token'])->get('https://www.googleapis.com/oauth2/v3/userinfo')->throw()->json();

        $user = User::updateOrCreate(['email' => $profile['email']], [
            'name' => $profile['name'] ?? 'ArenaX Player',
            'google_id' => $profile['sub'] ?? null,
            'avatar' => $profile['picture'] ?? null,
            'password' => Hash::make(Str::random(32)),
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
