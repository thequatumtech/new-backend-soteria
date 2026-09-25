<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Client;
use App\Services\InAppNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    // public function index(Request $request)
    // {
    //     $notifications = Notification::where('client_id', $request->user_id)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($request->get('per_page', 20));

    //     return response()->json($notifications);
    // }
    public function index(Request $request)
    {
        $client = Client::find($request->user_id);
        $lang = $client->language ?? 'en';
    
        $notifications = Notification::where('client_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
    
        $notifications->getCollection()->transform(function ($notification) use ($lang) {
            return \App\Services\NotificationTranslator::apply($notification, $lang);
        });
    
        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::where('client_id', $request->user_id)
            ->unread()
            ->count();

        return response()->json(['unread_count' => $count]);
    }
    
    public function showonlyunread(Request $request){
        
        $notifications = Notification::where('client_id', auth()->id())
            ->unread()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
            
            
        return response()->json($notifications);
    }
    
    public function markAsRead(Request $request, int $id)
    {
        $this->notificationService->markAsRead($request->user_id, $id);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('client_id', $request->user_id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}