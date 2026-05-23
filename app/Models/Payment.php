<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'provider',
        'provider_order_id',
        'provider_payment_id',
        'provider_signature',
        'amount',
        'currency',
        'status',
        'payload',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'payload' => 'array'];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
