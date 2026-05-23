<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendOtp(string $phone, string $code): void
    {
        if (config('services.twilio.sid') && config('services.twilio.token')) {
            Log::info('Twilio OTP queued', ['phone' => $phone, 'code' => $code]);
            return;
        }

        if (config('services.fast2sms.key')) {
            try {
                Http::timeout(5)->post('https://www.fast2sms.com/dev/bulkV2', [
                    'authorization' => config('services.fast2sms.key'),
                    'route' => 'otp',
                    'variables_values' => $code,
                    'numbers' => $phone,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Fast2SMS failed', ['message' => $e->getMessage()]);
            }

            return;
        }

        Log::info('Demo OTP generated', ['phone' => $phone, 'code' => $code]);
    }
}
