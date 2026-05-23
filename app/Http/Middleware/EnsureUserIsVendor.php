<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = $request->user()?->vendorProfile;

        abort_unless($request->user()?->role === 'vendor' && $vendor?->isApproved(), 403, 'Vendor account is not approved.');

        return $next($request);
    }
}
