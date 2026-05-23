<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function __construct(private readonly SmsService $sms)
    {
    }

    public function send(string $phone, Request $request, string $purpose = 'login'): void
    {
        $code = app()->environment('production') ? (string) random_int(100000, 999999) : '123456';

        OtpCode::create([
            'phone' => $phone,
            'code' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
        ]);

        $this->sms->sendOtp($phone, $code);
    }

    public function verify(string $phone, string $code, string $purpose = 'login'): void
    {
        $otp = OtpCode::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otp || $otp->expires_at->isPast()) {
            throw ValidationException::withMessages(['otp' => 'The OTP has expired.']);
        }

        $otp->increment('attempts');

        if ($otp->attempts > 5 || ! Hash::check($code, $otp->code)) {
            throw ValidationException::withMessages(['otp' => 'Invalid OTP code.']);
        }

        $otp->update(['verified_at' => now()]);
    }
}
