<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'vendor_id',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'latitude',
        'longitude',
        'base_price',
        'opening_time',
        'closing_time',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'base_price' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Venue $venue) {
            if (! $venue->slug) {
                $venue->slug = Str::slug($venue->name).'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(VenueImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class)->withPivot('price_per_hour')->withTimestamps();
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'venue_amenities')->withTimestamps();
    }

    public function bookingSlots(): HasMany
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
