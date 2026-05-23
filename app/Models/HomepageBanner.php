<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageBanner extends Model
{
    protected $fillable = ['title', 'subtitle', 'image', 'cta_label', 'cta_url', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
