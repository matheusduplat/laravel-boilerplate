<?php

namespace App\Domains\Notifications\Http\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $readNotification = Auth::user()->readNotifications;
        $unreadNotification = Auth::user()->unreadNotifications;

        return response()->json([
            'readNotifications' => $readNotification,
            'unreadNotifications' => $unreadNotification,
        ]);
    }
    public function read(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => __('Marked as successfully read.')], 201);
    }
    public function readAll(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['message' => __('All marked as successfully read.')], 201);
    }
}
