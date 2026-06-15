<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch latest announcements as notifications
        $notifications = [];

        if (class_exists(\App\Models\Announcement::class)) {
            $notifications = \App\Models\Announcement::latest()
                ->limit(10)
                ->get(['id', 'title', 'created_at'])
                ->map(fn($a) => [
                    'id'    => $a->id,
                    'title' => $a->title,
                    'date'  => $a->created_at->format('M d, Y'),
                    'url'   => '#',
                ]);
        }

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $notifications->count(),
        ]);
    }
}