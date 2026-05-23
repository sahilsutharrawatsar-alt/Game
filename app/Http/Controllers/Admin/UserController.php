<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', ['users' => User::latest()->paginate(20)]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate(['role' => ['required', 'in:user,admin']]);
        $user->update($data);

        return back()->with('status', 'User role updated.');
    }
}
