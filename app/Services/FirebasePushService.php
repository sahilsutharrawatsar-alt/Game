<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebasePushService
{
    public function send(string $title, string $message, ?string $topic = 'arenax-users'): void
    {
        $serverKey = config('services.firebase.server_key');

        if (! $serverKey) {
            Log::info('Firebase push skipped; no key configured.', compact('title', 'message', 'topic'));
            return;
        }

        try {
            Http::withToken($serverKey)->post('https://fcm.googleapis.com/fcm/send', [
                'to' => '/topics/'.$topic,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Firebase push failed', ['message' => $e->getMessage()]);
        }
    }
}
