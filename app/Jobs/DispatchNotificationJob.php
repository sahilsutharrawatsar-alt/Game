<?php

namespace App\Jobs;

use App\Models\AppNotification;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\FirebasePushService;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class DispatchNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ?int $userId,
        public string $title,
        public string $message,
        public string $type = 'system',
        public array $channels = ['web', 'push']
    ) {
    }

    public function handle(SmsService $sms, FirebasePushService $push): void
    {
        $users = $this->userId ? User::whereKey($this->userId)->get() : User::where('role', 'user')->get();

        foreach ($users as $user) {
            $notification = AppNotification::create([
                'user_id' => $user->id,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
            ]);

            foreach ($this->channels as $channel) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'channel' => $channel,
                    'delivery_status' => 'delivered',
                    'delivered_at' => now(),
                ]);
            }

            if (in_array('sms', $this->channels, true) && $user->phone) {
                $sms->sendOtp($user->phone, substr(hash('crc32b', $this->message), 0, 6));
            }

            if (in_array('email', $this->channels, true) && $user->email) {
                Mail::raw($this->message, fn ($mail) => $mail->to($user->email)->subject($this->title));
            }
        }

        if (in_array('push', $this->channels, true)) {
            $push->send($this->title, $this->message);
        }
    }
}
