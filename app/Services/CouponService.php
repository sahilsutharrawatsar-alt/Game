<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function discount(?string $code, float $subtotal): array
    {
        if (! $code) {
            return [null, 0.0];
        }

        $coupon = Coupon::query()
            ->where('code', strtoupper($code))
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (! $coupon || ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)) {
            return [null, 0.0];
        }

        $discount = $coupon->type === 'fixed'
            ? (float) $coupon->value
            : $subtotal * ((float) $coupon->value / 100);

        if ($coupon->max_discount) {
            $discount = min($discount, (float) $coupon->max_discount);
        }

        return [$coupon, min($discount, $subtotal)];
    }
}
