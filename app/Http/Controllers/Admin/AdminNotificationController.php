<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function __construct(protected AdminNotificationService $notificationService) {}

    public function index(Request $request)
    {
        $adminId = Auth::guard('admin')->id();

        $notifications = AdminNotification::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($notifications);
    }

    public function unreadCount()
    {
        $adminId = Auth::guard('admin')->id();

        $count = AdminNotification::where('admin_id', $adminId)
            ->unread()
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    public function markAsRead(int $id)
    {
        $adminId = Auth::guard('admin')->id();

        $this->notificationService->markAsRead($adminId, $id);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead()
    {
        $adminId = Auth::guard('admin')->id();

        AdminNotification::where('admin_id', $adminId)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}