<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Client;
use App\Models\Message;
use App\Services\FirebaseChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminChatController extends Controller
{
    protected FirebaseChatService $firebase;

    public function __construct(FirebaseChatService $firebase)
    {
        $this->firebase = $firebase;
    }
    
    
    public function newchat(){
        return view('admin.contactus.newchat');
    }
    
    
    public function startChat(Request $request)
    {
        $request->validate(['receiver_id' => 'required|integer|exists:clients,id']);

        $adminId = Auth::guard('admin')->id();

        $chat = Chat::where(function ($q) use ($adminId, $request) {
            $q->where('user_one_id', $adminId)->where('user_one_type', 'admin')
              ->where('user_two_id', $request->receiver_id)->where('user_two_type', 'client');
        })->orWhere(function ($q) use ($adminId, $request) {
            $q->where('user_one_id', $request->receiver_id)->where('user_one_type', 'client')
              ->where('user_two_id', $adminId)->where('user_two_type', 'admin');
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_one_id'   => $adminId,
                'user_one_type' => 'admin',
                'user_two_id'   => $request->receiver_id,
                'user_two_type' => 'client',
            ]);
        }

        return response()->json(['chat' => $chat]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required_without:file|nullable|string',
            'file'    => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,pdf,doc,docx|max:20480',
        ]);

        $adminId = Auth::guard('admin')->id();

        $filePath = $fileType = $fileName = $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $this->resolveFileType($file->getMimeType());
            $stored   = $file->store((string) $request->chat_id, 'chat_uploads');
            $filePath = '/uploads/chat_files/' . $stored;
        }

        $message = Message::create([
            'chat_id'     => $request->chat_id,
            'sender_id'   => $adminId,
            'sender_type' => 'admin',
            'message'     => $request->message,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
            'file_size'   => $fileSize,
        ]);

        // FIX 3: toJSON() gives ISO8601 with 'Z' — same format the REST API already returns
        $this->firebase->pushMessage($request->chat_id, [
            'id'          => $message->id,
            'sender_id'   => $adminId,
            'sender_type' => 'admin',
            'message'     => $request->message,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
            'is_read'     => false,
            'created_at'  => $message->created_at->toJSON(),
        ]);

        $this->updateChatSummaryAndNotify($request->chat_id, 'admin', $request->message, $fileType, $fileName);

        return response()->json(['status' => true, 'data' => $message]);
    }

    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->paginate(30);

        return response()->json($messages);
    }

    // public function markAsRead($chatId)
    // {
    //     $chat = Chat::findOrFail($chatId);
    //     $chat->update(['unread_count_admin' => 0]);
    //     Message::where('chat_id', $chatId)->where('sender_type', 'client')->update(['is_read' => true]);

    //     return response()->json(['status' => true]);
    // }
    
    // public function markAsRead($chatId)
    // {
    //     Message::where('chat_id', $chatId)->where('sender_type', 'client')->update(['is_read' => true]);
    //     return response()->json(['status' => true]);
    // }
    public function markAsRead($chatId)
    {
        Message::where('chat_id', $chatId)->where('sender_type', 'client')->update(['is_read' => true]);
    
        $chat = Chat::find($chatId);
        if ($chat) {
            $adminId = Auth::guard('admin')->id();
            try {
                $this->firebase->pushInboxUpdate('admin', $adminId, $chatId, [
                    'chat_id'         => $chat->id,
                    'party_id'        => $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id,
                    'party_name'      => $this->getPartyDisplay($chat, 'client')['name'],
                    'last_message'    => $chat->last_message,
                    'last_message_at' => $chat->last_message_at ? $chat->last_message_at->toJSON() : null,
                    'unread_count'    => 0,
                ]);
                \Log::info("markAsRead: Firebase push SUCCESS for chat {$chatId}, admin {$adminId}");
            } catch (\Throwable $e) {
                \Log::error("markAsRead: Firebase push FAILED for chat {$chatId} — " . $e->getMessage());
            }
        } else {
            \Log::warning("markAsRead: Chat {$chatId} not found");
        }
    
        return response()->json(['status' => true]);
    }

    // public function chatList(Request $request)
    // {
    //     $adminId = Auth::guard('admin')->id();
    //     $perPage = 30;

    //     $chats = Chat::where(function ($q) use ($adminId) {
    //             $q->where('user_one_id', $adminId)->where('user_one_type', 'admin');
    //         })->orWhere(function ($q) use ($adminId) {
    //             $q->where('user_two_id', $adminId)->where('user_two_type', 'admin');
    //         })
    //         ->orderByRaw('last_message_at IS NULL, last_message_at DESC')
    //         ->paginate($perPage);

    //     $clientIds = $chats->getCollection()->map(function ($chat) {
    //         return $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
    //     });

    //     $clients = Client::whereIn('id', $clientIds)->get()->keyBy('id');

    //     $data = $chats->getCollection()->map(function ($chat) use ($clients) {
    //         $clientId = $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
    //         $client = $clients->get($clientId);

    //         return [
    //             'chat_id'         => $chat->id,
    //             'client_id'       => $clientId,
    //             'client_name'     => $client->first_name ?? ('Client #' . $clientId),
    //             'client_email'    => $client->email_id ?? null,
    //             'last_message'    => $chat->last_message,
    //             'last_message_at' => $chat->last_message_at,
    //             'unread_count'    => $chat->unread_count_admin,
    //         ];
    //     });

    //     return response()->json([
    //         'data'     => $data,
    //         'has_more' => $chats->hasMorePages(),
    //     ]);
    // }

    public function chatList(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $perPage = 30;
    
        $chats = Chat::where(function ($q) use ($adminId) {
                $q->where('user_one_id', $adminId)->where('user_one_type', 'admin');
            })->orWhere(function ($q) use ($adminId) {
                $q->where('user_two_id', $adminId)->where('user_two_type', 'admin');
            })
            ->withCount(['messages as live_unread_count' => function ($q) {
                $q->where('sender_type', 'client')->where('is_read', false);
            }])
            ->orderByRaw('last_message_at IS NULL, last_message_at DESC')
            ->paginate($perPage);
    
        $clientIds = $chats->getCollection()->map(function ($chat) {
            return $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
        });
    
        $clients = Client::whereIn('id', $clientIds)->get()->keyBy('id');
    
        $data = $chats->getCollection()->map(function ($chat) use ($clients) {
            $clientId = $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
            $client = $clients->get($clientId);
    
            return [
                'chat_id'         => $chat->id,
                'client_id'       => $clientId,
                'client_name'     => $client->first_name ?? ('Client #' . $clientId),
                'client_email'    => $client->email_id ?? null,
                'last_message'    => $chat->last_message,
                'last_message_at' => $chat->last_message_at,
                'unread_count'    => $chat->live_unread_count,   // 👈 ab live query se, stored counter se nahi
            ];
        });
    
        return response()->json([
            'data'     => $data,
            'has_more' => $chats->hasMorePages(),
        ]);
    }
    public function getClientsForChat(Request $request)
    {
        $search = $request->get('search');

        $query = Client::select('id', 'first_name', 'email_id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('email_id', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('first_name')->paginate(30);

        return response()->json([
            'data'     => $clients->items(),
            'has_more' => $clients->hasMorePages(),
        ]);
    }

    private function resolveFileType($mime)
    {
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'video/')) return 'video';
        if ($mime === 'application/pdf') return 'pdf';
        if (in_array($mime, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) return 'doc';
        return 'other';
    }

    // FIX 1: now also returns WHO sent it (id + display name), so the frontend
    // can build a brand-new sidebar item even if that chat wasn't loaded yet
    // private function getPartyDisplay($chat, $type)
    // {
    //     if ($type === 'client') {
    //         $id = $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
    //         $client = Client::find($id);
    //         return ['id' => $id, 'name' => $client->first_name ?? ('Client #' . $id)];
    //     }

    //     $id = $chat->user_one_type === 'admin' ? $chat->user_one_id : $chat->user_two_id;
    //     // NOTE: adjust field name below to match your actual Admin model column (e.g. 'name', 'username')
    //     $admin = \App\Models\Admin::find($id);
    //     return ['id' => $id, 'name' => $admin->name ?? 'Support Admin'];
    // }
    private function getPartyDisplay($chat, $type)
    {
        if ($type === 'client') {
            $id = $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
            $client = Client::find($id);
            return ['id' => $id, 'name' => $client->first_name ?? ('Client #' . $id)];
        }
    
        $id = $chat->user_one_type === 'admin' ? $chat->user_one_id : $chat->user_two_id;
        $admin = \App\Models\Admin::find($id);
        return ['id' => $id, 'name' => $admin->full_name ?? 'Support Admin']; // 👈 name → full_name
    }

    // private function updateChatSummaryAndNotify($chatId, $senderType, $messageText, $fileType, $fileName)
    // {
    //     $chat = Chat::find($chatId);

    //     $preview = $messageText;
    //     if (!$preview && $fileType) {
    //         $preview = match ($fileType) {
    //             'image' => '📷 Photo',
    //             'video' => '🎥 Video',
    //             'pdf', 'doc' => '📄 ' . $fileName,
    //             default => '📎 Attachment',
    //         };
    //     }

    //     $receiverType = $chat->user_one_type === $senderType ? $chat->user_two_type : $chat->user_one_type;
    //     $receiverId   = $chat->user_one_type === $senderType ? $chat->user_two_id : $chat->user_one_id;

    //     $chat->update([
    //         'last_message'    => $preview,
    //         'last_message_at' => now(),
    //     ]);
    //     $chat->increment('unread_count_' . $receiverType);

    //     // FIX 1: include sender's id/name so a brand-new chat can render without refresh
    //     $senderDisplay = $this->getPartyDisplay($chat, $senderType);

    //     $this->firebase->pushInboxUpdate($receiverType, $receiverId, $chatId, [
    //         'chat_id'         => $chatId,
    //         'party_id'        => $senderDisplay['id'],
    //         'party_name'      => $senderDisplay['name'],
    //         'last_message'    => $preview,
    //         'last_message_at' => now()->toJSON(), // FIX 3
    //         'unread_count'    => $chat->fresh()->{'unread_count_' . $receiverType},
    //     ]);
    // }
    private function updateChatSummaryAndNotify($chatId, $senderType, $messageText, $fileType, $fileName)
    {
        $chat = Chat::find($chatId);
    
        $preview = $messageText;
        if (!$preview && $fileType) {
            $preview = match ($fileType) {
                'image' => '📷 Photo',
                'video' => '🎥 Video',
                'pdf', 'doc' => '📄 ' . $fileName,
                default => '📎 Attachment',
            };
        }
    
        $receiverType = $chat->user_one_type === $senderType ? $chat->user_two_type : $chat->user_one_type;
        $receiverId   = $chat->user_one_type === $senderType ? $chat->user_two_id : $chat->user_one_id;
    
        $chat->update([
            'last_message'    => $preview,
            'last_message_at' => now(),
        ]);
    
        // 👇 Live count — no more increment(), no more race condition
        $liveUnreadCount = Message::where('chat_id', $chatId)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->count();
    
        $senderDisplay = $this->getPartyDisplay($chat, $senderType);
    
        $this->firebase->pushInboxUpdate($receiverType, $receiverId, $chatId, [
            'chat_id'         => $chatId,
            'party_id'        => $senderDisplay['id'],
            'party_name'      => $senderDisplay['name'],
            'last_message'    => $preview,
            'last_message_at' => now()->toJSON(),
            'unread_count'    => $liveUnreadCount,
        ]);
    }
}