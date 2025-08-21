<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $notifications = $this->notificationService->getUserNotifications(
            Auth::id(),
            $request->get('per_page', 15)
        );

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $success = $this->notificationService->markAsRead($id, Auth::id());

        if ($success) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead(Auth::id());

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil menandai {$count} notifikasi sebagai dibaca"
        ]);
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadCount(Auth::id());

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function getRecent()
    {
        $notifications = $this->notificationService->getUserNotifications(Auth::id(), 5);

        return response()->json([
            'notifications' => $notifications->items(),
            'unread_count' => $this->notificationService->getUnreadCount(Auth::id())
        ]);
    }
}
