<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['vendor_id', 'booking_id', 'payment_id', 'type', 'amount', 'status', 'reference', 'meta'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'meta' => 'array'];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
