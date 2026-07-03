<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show the notification center page with date filtering.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $notifications = $query->paginate(20);

        return view('notifications', compact('notifications'));
    }

    /**
     * Mark all notifications as read for the user.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get the unread notifications count and list of latest notifications.
     */
    public function unreadCount(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['unreadCount' => 0, 'notifications' => []]);
        }
        $unreadCount = $user->unreadNotifications->count();
        $notifications = $user->unreadNotifications()->take(20)->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'data' => $notif->data,
                'read_at' => $notif->read_at,
                'time_diff' => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unreadCount' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}

