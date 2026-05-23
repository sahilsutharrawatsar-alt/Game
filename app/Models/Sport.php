<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sport extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class)->withPivot('price_per_hour')->withTimestamps();
    }
}
