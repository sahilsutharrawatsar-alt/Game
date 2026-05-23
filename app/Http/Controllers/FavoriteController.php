<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Venue $venue)
    {
        $request->user()->favorites()->toggle($venue->id);

        return back()->with('status', 'Favorites updated.');
    }
}
