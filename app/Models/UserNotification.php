<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = ['user_id', 'notification_id', 'channel', 'delivery_status', 'delivered_at'];

    protected function casts(): array
    {
        return ['delivered_at' => 'datetime'];
    }
}
