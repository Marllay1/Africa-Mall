<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->latest()->take(30)->get();

        $request->user()->unreadNotifications->each->markAsRead();

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function badge(Request $request): JsonResponse
    {
        return response()->json(['count' => $request->user()->unreadNotifications()->count()]);
    }
}
