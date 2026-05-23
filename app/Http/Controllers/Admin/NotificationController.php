<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\User;
use App\Services\NotificationFanoutService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'notifications' => AppNotification::with('user')->latest()->paginate(20),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, NotificationFanoutService $fanout)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'max:160'],
            'message' => ['required', 'max:1000'],
            'type' => ['required', 'max:40'],
        ]);

        if ($data['user_id'] ?? null) {
            $fanout->toUser((int) $data['user_id'], $data['title'], $data['message'], $data['type'], ['web', 'push', 'email']);
        } else {
            $fanout->broadcast($data['title'], $data['message'], $data['type'], ['web', 'push']);
        }

        return back()->with('status', 'Notification sent.');
    }
}
