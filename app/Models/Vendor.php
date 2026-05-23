<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'owner_name',
        'phone',
        'email',
        'city',
        'address',
        'gst_number',
        'bank_name',
        'account_last_four',
        'status',
        'rejection_reason',
        'approved_at',
        'blocked_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'blocked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->blocked_at === null;
    }
}
