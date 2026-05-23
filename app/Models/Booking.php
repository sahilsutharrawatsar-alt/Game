<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'venue_id',
        'sport_id',
        'booking_slot_id',
        'booking_date',
        'starts_at',
        'ends_at',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'payment_status',
        'coupon_id',
        'notes',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class, 'booking_slot_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
