<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'base_price' => (float) $this->base_price,
            'opening_time' => substr((string) $this->opening_time, 0, 5),
            'closing_time' => substr((string) $this->closing_time, 0, 5),
            'rating' => round((float) $this->reviews_avg_rating, 1),
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => [
                'url' => asset($image->path),
                'alt' => $image->alt_text,
                'primary' => $image->is_primary,
            ])),
            'sports' => SportResource::collection($this->whenLoaded('sports')),
            'amenities' => $this->whenLoaded('amenities', fn () => $this->amenities->pluck('name')),
        ];
    }
}
