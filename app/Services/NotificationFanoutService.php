<?php

namespace App\Services;

use App\Jobs\DispatchNotificationJob;

class NotificationFanoutService
{
    public function toUser(int $userId, string $title, string $message, string $type = 'system', array $channels = ['web', 'push']): void
    {
        DispatchNotificationJob::dispatch($userId, $title, $message, $type, $channels);
    }

    public function broadcast(string $title, string $message, string $type = 'offer', array $channels = ['web', 'push']): void
    {
        DispatchNotificationJob::dispatch(null, $title, $message, $type, $channels);
    }
}
